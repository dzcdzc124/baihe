<?php

namespace App\Wechat;

use App\Wechat\Wechat as Client;
use App\Models\User;


class Wxpay extends Base
{
    protected $type = 'mp';

    private $appId;

    private $appSecret;

    private $mchId;

    private $mchKey;

    private $certPath;

    private $keyPath;

    private $_oauth;

    private $apiHost = 'https://api.mch.weixin.qq.com/';

    public function __construct(array $options = array())
    {
        $this->token = self::element($options, 'token', false);
        $this->encodingAESKey = self::element($options, 'encodingAESKey');
        $this->appId = self::element($options, 'appId', false);
        $this->appSecret = self::element($options, 'appSecret', false);

        $this->mchId = self::element($options, 'mchId', false);
        $this->mchKey = self::element($options, 'mchKey', false);
        $this->certPath = self::element($options, 'certPath', false);
        $this->keyPath = self::element($options, 'keyPath', false);

        $this->storage = self::element($options, 'storage', []);

        if ($this->encodingAESKey)
            $this->crypt = new WXBizMsgCrypt($this->token, $this->encodingAESKey, $this->appId);

        if ( ! isset(self::$obj))
            self::$obj = $this;
    }

    //下单
    public function unifiedorder(array $data){
        $data['appid'] = $this->appId;
        $data['mch_id'] = $this->mchId;
        $data['nonce_str'] = strtolower(self::createNonceStr());

        $data['sign'] = self::getSign($data, $this->mchKey);    

        $res = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::dataToXML($data));
        return $res;
    }

    public function checkSign(array $data){
        if( !isset($data['sign']) ) 
            return false;

        $sign = $data['sign'];
        unset($data['sign']);

        $compare = self::getSign($data, $this->mchKey);

        if($sign == $compare){
            return true;
        }

        return false;
    }

    /**
    * curl get
    *
    * @param string $url
    * @param array $options
    * @return mixed
    */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
          curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        die(var_dump($postData));
        if (is_array($postData)) {
          $postData = http_build_query($postData);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        if (!empty($options)) {
          curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_POST, ture);
        $data = curl_exec($ch);
        curl_close($ch);

        $result = (array) simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return ['xml' => $data, 'data' => $result];
    }

    public function sendRedpacket(array $data)
    {
        $xml = $this->createRedpacketRequestData($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');

        curl_setopt($ch, CURLOPT_SSLCERT, $this->certPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->keyPath);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml', 'Accept: application/xml']);
        $ret = curl_exec($ch);
        // $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = (array) simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA);
        return ['raw' => $ret, 'result' => $result];
    }

    public static function XMLSafeStr($str)
    {   
        return '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str) . ']]>';   
    }

    public static function dataToXML($data) {
        $xml = '<xml>';
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<$key>". $val . "</$key>";
            } else {
                $xml .= "<$key>". self::XMLSafeStr($val) ."</$key>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    public static function getSign($data, $key)
    {
        ksort($data, SORT_STRING);

        $signData = urldecode(http_build_query($data, null, '&', PHP_QUERY_RFC3986));
        $signData .= '&key=' . $key;
        $signStr = strtoupper(md5($signData));
        return $signStr;
    }
}