<?php

namespace App\Lib\Http;

use Phalcon\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    public function getClientAddress($trustForwardedHeader = false)
    {
        if ( ! empty($_SERVER['HTTP_CDN_SRC_IP']))
            return $_SERVER['HTTP_CDN_SRC_IP'];

        return parent::getClientAddress($trustForwardedHeader);
    }

    public function relativeURI()
    {
        $uri = $this->getURI();
        $baseUri = $this->getDI()->getShared('url')->getBaseUri();
        if (strpos($uri, $baseUri) == 0)
            $uri = substr($uri, strlen($baseUri));
        return $uri;
    }

    public function isAjaxLoad()
    {
        return $this->isAjax() && (strtolower($this->getHeader('X-Request-Method')) == 'ajax');
    }
}