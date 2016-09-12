<?php

namespace App\Modules\Api\Controllers;

use App\Helpers\Upload as UploadHelper;


class UploadController extends ControllerBase
{
    protected $loginRequired = false;

    public function indexAction()
    {
        $imgData = $this->request->get('image', 'trim');
    
        try {
            $data = (array) UploadHelper::saveBase64(null, $imgData);
        } catch (\Exception $e) {
            $this->serveJson($e->getMessage());
        }

        $this->serveJson('上传成功', 0, $data);
    }

    //starshop 明星门店H5签名图片接口
    public function getSignAction(){
        $filename = "star-shop-sign.json";
        $result = [];

        $num = (int) $this->request->get("num", "trim");
        $num = $num > 0 ? $num : 4;

        $setting = $this->getDI()->getShared('setting');
        $basePath = $setting->get('uploadPath', 'uploaded/');
        if (substr($basePath, 0, 1) != '/')
            $basePath = WEB_PATH . $basePath;

        $path = $basePath . $filename;
        if(file_exists($path)){
            $str = file_get_contents($path);
            $data = json_decode($str, true);

            if(count($data) <= $num){
                $result = $data;
            }else{
                $keys = array_rand($data, $num);
                if($num > 1){
                    foreach ($keys as $value) {
                        $result[] = $data[$value];
                    }
                }else{
                    $result[] = $data[$keys];
                }
            }
        }

        $this->serveJson('获取成功', 0, ["result" => $result] );
    }  

    public function saveSignAction(){
        $filename = "star-shop-sign.json";

        $imgData = $this->request->get('image', 'trim');
    
        try {
            $result = (array) UploadHelper::saveBase64(null, $imgData);
        } catch (\Exception $e) {
            $this->serveJson($e->getMessage());
        }

        
        //把上传的文件保存在设定json文件中
        $setting = $this->getDI()->getShared('setting');
        $basePath = $setting->get('uploadPath', 'uploaded/');
        if (substr($basePath, 0, 1) != '/')
            $basePath = WEB_PATH . $basePath;

        $path = $basePath . $filename;
        $data;
        if(file_exists($path)){
            $str = file_get_contents($path);
            $data = json_decode($str, true);
            $data[] = $result["path"];
        }else{
            $data = [$result["path"]];
        } 
        file_put_contents($path, json_encode($data));

        $this->serveJson('上传成功', 0, $result);
    }
}