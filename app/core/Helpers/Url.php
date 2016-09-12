<?php

namespace App\Helpers;


class Url extends HelperBase
{
    public static function redirect($url)
    {
        $response = self::getShared('response');
        $response->redirect($url);
        $response->send();
        exitmsg();
    }

    public static function staticUrl($path)
    {
        $config = self::get('config');

        $staticPrefix = $config->application->staticPrefix;
        if ( ! in_array(substr($path, 0, 1), ['.', '~']))
            return $staticPrefix . ltrim($path, '/');

        $path = substr($path, 1);
        $return = $config->application->staticUri . ltrim($path, '/');

        if ((substr($return, 0, 7) != 'http://') && substr($return, 0, 8) != 'https://') {
            $request = self::getShared('request');
            $httpHost = $request->getHttpHost();
            $return = 'http://' . $httpHost . '/' . ltrim($return, '/');
        }

        if (substr($path, -1) == '#') {
            $return = substr($return, 0, -1);
        } else {
            $config = self::getShared('config');
            if ($config->application->debug) {
                $version = TIMESTAMP;
            } else {
                $version = $config->application->staticVer;
            }
            $return .= (strpos($return, '?') === FALSE ? '?' : '&') . '_=' . $version;
        }

        return $return;
    }

    public static function staticPath($path)
    {
        $config = self::get('config');

        return $config->application->staticPath . ltrim($path, '/');
    }
}
