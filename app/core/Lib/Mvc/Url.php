<?php

namespace App\Lib\Mvc;

use Phalcon\Mvc\Url as PhUrl;
use Phalcon\DI;

class Url extends PhUrl
{
    function get($uri = null, $args = null, $local = null)
    {
        if (is_string($uri) && strpos($uri, '?') !== false) {
            $tmpArr = explode('?', $uri);
            $uri = $tmpArr[0];
            $query = $tmpArr[1];
            @parse_str($query, $q);
            $args = array_merge((array) $q, (array) $args);
        }

        $return = parent::get($uri, $args, $local);

        if ((substr($return, 0, 7) != 'http://') && substr($return, 0, 8) != 'https://') {
            $request = $this->getDI()->getShared('request');
            $httpHost = $request->getHttpHost();
            if (empty($httpHost) || $httpHost == ':') {
                $config = $this->getDI()->getShared('config');
                $httpHost = $config->application->host;
            }
            $return = 'http://' . $httpHost . '/' . ltrim($return, '/');
        }

        return $return;
    }
}