<?php

namespace App\Helpers;

use App\Models\Attachment;
use App\Models\Administrator;


class Upload extends HelperBase
{
    public static function icon($ext)
    {
        return $ext;
    }

    public static function url($path)
    {
        $setting = self::getShared('setting');
        $baseUri = $setting->get('uploadUrl', '/uploaded/');
        $return = $baseUri . ltrim($path, '/');

        if ((substr($return, 0, 7) != 'http://') && substr($return, 0, 8) != 'https://') {
            $request = self::getShared('request');
            $httpHost = $request->getHttpHost();
            if (empty($httpHost) || $httpHost == ':') {
                $config = self::getShared('config');
                $httpHost = $config->application->host;
            }

            $return = 'http://' . $httpHost . '/' . ltrim($return, '/');
        }

        return $return;
    }

    public static function randPath($ext = null)
    {
        $setting = self::getShared('setting');
        $dirRule = $setting->get('uploadDirRule', 'Y/m-d/');
        $directory = date($dirRule);
        $realDir = self::realPath($directory);

        if ( ! is_dir($realDir))
            mkdir($realDir, 0777, true);

        $fileName = time() . uniqid();
        $path = $directory . $fileName . ($ext ? '.'.$ext : '');
        
        return [
            'path' => $path,
            'realPath' => self::realPath($path),
        ];
    }

    public static function realPath($path, $mkdir = false)
    {
        $setting = self::getShared('setting');
        $basePath = $setting->get('uploadPath', 'uploaded/');
        if (substr($basePath, 0, 1) != '/')
            $basePath = WEB_PATH . $basePath;

        $path = $basePath . ltrim($path, '/');
        if ($mkdir && ! is_dir($path))
            mkdir($path, 0777, true);

        return $path;
    }

    public static function saveBase64($user, $data)
    {
        return self::save($user, $data, true);
    }

    public static function save($user, $data, $isBase64 = false, $allowExtensions = null, $isTmp = false)
    {
        $isImage = $path = $thumbPath = $name = $ext = $type = null;
        $size = $width = $height = 0;
        $saved = false;

        $userId = $user ? $user->id : 0;
        $userType = 'user';
        if ($user instanceOf Administrator) {
            $userId = $user->id;
            $userType = 'administrator';
        }

        $setting = self::getShared('setting');
        $dirRule = $setting->get('uploadDirRule', 'Y/m-d/');
        $sizeLimit = $setting->getInt('uploadSizeLimit', 0);
        $allowExtensions = is_null($allowExtensions) ? $setting->getArray('uploadAllowExtensions', []) : (array) $allowExtensions;

        $directory = date($dirRule);
        $realDir = self::realPath($directory, true);
        $fileName = time() . uniqid();

        if ($isBase64) {
            if ( ! preg_match('/^data:(image\/(.+));base64,(.+)$/i', $data, $matches))
                throw new \Exception('文件数据格式不正确', 1000);

            $type = $matches[1];
            $ext = $matches[2];
            $imgData = $matches[3];

            if (count($allowExtensions) > 0 && ! in_array($ext, $allowExtensions))
                throw new \Exception('不允许上传该文件格式', 1001);

            $name = 'base64.' . $ext;
            $tmpFile = tempnam(sys_get_temp_dir(), uniqid());
            file_put_contents($tmpFile, base64_decode($imgData));

            $size = filesize($tmpFile);
            if ($sizeLimit > 0 && $size > $sizeLimit)
                throw new \Exception('文件大小超出允许的范围', 1002);

            $path = $directory . $fileName . ($ext ? '.'.$ext : '');
            $realPath = self::realPath($path);

            $isImage = intval(substr($type, 0, 6) == 'image/');
            $width = $height = 0;
            $thumbPath = null;
            if ($isImage)
                list($width, $height, $t, $a) = getimagesize($tmpFile);

            if (copy($tmpFile, $realPath)) {
                $saved = true;
                @unlink($tmpFile);

                return [
                    'path' => $path,
                    'url' => self::url($path),
                ];
            } else {
                throw new \Exception('上传文件无法保存', 1003);
            }
        } else {
            $request = self::getShared('request');
            foreach ($request->getUploadedFiles() as $file) {
                if ($file->getKey() != $data) continue;

                $name = $file->getName();
                $ext = self::getExt($name);
                if (count($allowExtensions) > 0 && ! in_array($ext, $allowExtensions))
                    throw new \Exception('不允许上传该文件格式', 1001);

                $size = $file->getSize();
                if ($sizeLimit > 0 && $size > $sizeLimit)
                    throw new \Exception('文件大小超出允许的范围', 1002);

                if ($isTmp) {
                    $tmpDir = $setting->get('tmpDir', null);
                    $tmpDir = empty($tmpDir) ? sys_get_temp_dir() : $tmpDir;
                    if ( ! is_dir($tmpDir))
                        mkdir($tmpDir, 0777, true);

                    $uniqueId = uniqid();
                    $tmpName = $tmpDir . $uniqueId . '.' . $ext;
                    $file->moveTo($tmpName);

                    return [
                        'id' => $uniqueId,
                        'filename' => $name,
                        'size' => $size,
                        'path' => $tmpName,
                    ];
                }

                if ( ! is_dir($realDir))
                    mkdir($realDir, 0777, true);

                $type = $file->getRealType();

                $path = $directory . $fileName . ($ext ? '.'.$ext : '');
                $realPath = self::realPath($path);

                $isImage = intval(substr($type, 0, 6) == 'image/');
                $width = $height = 0;
                $thumbPath = null;
                if ($isImage)
                    list($width, $height, $t, $a) = getimagesize($file->getTempName());

                if ($file->moveTo($realPath)) {
                    if ($isImage) {
                        $thumbPath = $directory . $fileName . '_thumb' . ($ext ? '.'.$ext : '');
                        self::makeThumb($path, $thumbPath);
                    }

                    $attachment = new Attachment;
                    $attachment->assign([
                        'userId' => $userId,
                        'userType' => $userType,
                        'path' => $path,
                        'thumbPath' => $thumbPath,
                        'name' => $name,
                        'ext' => $ext,
                        'size' => $size,
                        'mimetype' => $type,
                        'isImage' => $isImage,
                        'width' => $width,
                        'height' => $height,
                    ]);

                    if ($attachment->save())
                        return $attachment;
                }

                throw new \Exception('上传文件无法保存', 1003);
            }

            throw new \Exception('请先上传文件', 1004);
        }
    }

    private static function getName($ext = null)
    {
        $name = time() . uniqid();
        return $ext ? $name . '.' . $ext : $name;
    }

    private static function getExt($name)
    {
        $ext = null;
        if (strrpos($name, '.') !== false) {
            $ext = substr($name, strrpos($name, '.') + 1);
            $ext = strtolower($ext);
        }

        return $ext;
    }

    private static function makeThumb($path, $thumbPath)
    {
        $realPath = self::realPath($path);
        $realThumbPath = self::realPath($thumbPath);

        $thumbBorder = 200;
        list($width, $height) = self::getImageSize($realPath);

        if ($width > $height) {
            $rHeight = $thumbBorder;
            $rWidth = intval($width * $rHeight / $height);

            $x = intval(($rWidth - $thumbBorder) / 2);
            $y = 0;
        } else {
            $rWidth = $thumbBorder;
            $rHeight = intval($height * $rWidth / $width);

            $x = 0;
            $y = intval(($rHeight - $thumbBorder) / 2);
        }

        if (class_exists('\\Imagick')) {
            $image = new \Imagick($realPath);
            $image->resizeImage($rWidth, $rHeight, \Imagick::FILTER_LANCZOS, 1);
            $image->cropImage($thumbBorder, $thumbBorder, $x, $y);
            $image->writeImage($realThumbPath);
        } elseif (function_exists('gd_info')) {
            $im = self::openImage($realPath);
            $im2 = imagecreate($rWidth, $rHeight);
            imagecopyresized($im2, $im, 0, 0, 0, 0, $rWidth, $rHeight, $width, $height);
            $im3 = imagecrop($im2, [
                'x' => $x,
                'y' => $y,
                'width' => $thumbBorder,
                'height' => $thumbBorder,
            ]);
            self::saveImage($im3, $realThumbPath);
        } else {
            copy($realPath, $realThumbPath);
        }
        
        return true;
    }

    private static function getImageSize($path)
    {
        $width = $height = 0;
        if (function_exists('getimagesize')) {
            list($width, $height, $type, $attr) = getimagesize($path);
        } elseif (class_exists('\\Imagick')) {
            $image = new \Imagick($path);
            $d = $image->getImageGeometry();
            $width = intval($d['width']);
            $height = intval($d['height']);
        }

        return [$width, $height];
    }

    private static function openImage($path)
    {
        $size = getimagesize($path);

        switch ($size['mime']) {
            case 'image/jpeg':
                $im = imagecreatefromjpeg($path);
                break;

            case 'image/gif':
                $im = imagecreatefromgif($path);
                break;

            case 'image/png':
                $im = imagecreatefrompng($path);
                break;

            default: 
                $im = false;
        }

        return $im;
    }

    private static function saveImage($im, $path)
    {
        $ext = strtolower(substr($path, strrpos($path, '.') + 1));

        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($im, $path);
                break;
            
            case 'png':
                imagepng($im, $path);
                break;

            case 'gif':
                imagegif($im, $path);
                break;
        }
    }
}