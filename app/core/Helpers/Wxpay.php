<?php

namespace App\Helpers;

use App\Wechat\Wxpay as Client;
use App\Models\User;
use App\Models\JsapiTicket as Ticket;


class Wxpay extends HelperBase
{
    private static $client;

    public static function &client()
    {
        if ( ! isset(self::$client)) {
            $config = self::getShared('config');
            $wxConfig = $config->wechat;
            $cache = self::getShared('cache');
            $appId = $wxConfig->appId;

            self::$client = new Client([
                'appId' => $appId,
                'appSecret' => $wxConfig->appSecret,
                'token' => $wxConfig->token,
                'encodingAESKey' => $wxConfig->encodingAESKey,

                'mchId' => $wxConfig->mchId,
                'mchKey' => $wxConfig->mchKey,
                'certPath' => $wxConfig->certPath,
                'keyPath' => $wxConfig->keyPath,

                'storage' => new Storage($cache, $appId),
            ]);
        }

        return self::$client;
    }


    public static function createOrder($openId, $order, $product){
        $request = self::getShared('request');

        $data = [
            'body' => $product->name,
            'detail' => $product->detail,
            'attach' => $product->detail,
            'out_trade_no' => $order->order_id,
            'total_fee' => $order->total_fee,
            'spbill_create_ip' => $request->getClientAddress(),
            'time_start' => date("YmdHis", TIMESTAMP),
            'time_expire' => date("YmdHis", TIMESTAMP+580),
            'notify_url' => '',
            'trade_type' => 'JSAPI',
            'openid' => $openId
        ];

        //['xml' => $data, 'data' => $result]
        $res = self::client()->unifiedorder($data);
        if($res['data']['return_code'] != "SUCCESS"){
            return ['errcode'=>-1, 'errmsg'=>$res['data']['return_msg']];
        }

        if($res['data']['result_code'] != "SUCCESS"){
            return ['errcode'=>-1, 'errmsg'=>$res['data']['err_code'], 'errdes'=>$res['data']['err_code_des']];
        }

        return ['errcode'=>0, 'errmsg'=>'', 'prepay_id'=>$res['data']['prepay_id']];
    }

    public static function parseRedirectUrl($url, $param=[])
    {
        $parts = parse_url($url);
        $host = $parts['host'];
        $path = $parts['path'];
        $query = $parts['query'];

        if(!empty($param)){
            $url .= "?".http_build_query($param);
        }

        return $url;
    }

    public static function checkSign($data){
        return self::client()->checkSign($data);
    }
}


class Storage implements \ArrayAccess
{
    private $cache;
    private $prefix;
    private $data = [];

    public function __construct($cache, $prefix = '')
    {
        $this->cache = $cache;
        $this->prefix = $prefix;
    }

    public function offsetSet($offset, $value)
    {
        if (is_string($offset))
            $this->save($offset, $value);
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
         if ( ! is_string($offset))
            return null;

        return $this->get($offset);
    }

    public function get($name, $default = null)
    {
        if (isset($this->data[$name]))
            return $this->data[$name];

        $cacheName = $this->cacheName($name);
        if ($this->cache->exists($cacheName)) {
            $value = $this->cache->get($cacheName);
            $this->data[$name] = $value;

            return $value;
        }

        return $default;
    }

    public function save($name, $value, $ttl = 0)
    {
        $cacheName = $this->cacheName($name);

        $this->data[$name] = $value;
        $this->cache->save($cacheName, $value, $ttl);
    }

    private function cacheName($name)
    {
        return $this->prefix . '_' . $name;
    }
}