<?php

namespace App\Wechat;

use App\Wechat\Messages\MessageBase;
use App\Wechat\Utils\WXBizMsgCrypt;


class Wechat extends Base
{
    protected $type = 'mp';

    private $appId;

    private $appSecret;

    private $mchId;

    private $mchKey;

    private $certPath;

    private $keyPath;

    private $_oauth;

    private $apiHost = 'api.weixin.qq.com';

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

    public function validate(&$echoStr)
    {
        $signature = self::element($_GET, 'signature', '');
        $timestamp = self::element($_GET, 'timestamp', '');
        $nonce = self::element($_GET, 'nonce', '');
        $echoStr = self::element($_GET, 'echostr', '');

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr == $signature;
    }

    public function getOAuth()
    {
        if ( ! isset($this->_oauth))
            $this->_oauth = new OAuth($this->appId, $this->appSecret);

        return $this->_oauth;
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

    private function createRedpacketRequestData(array $data)
    {
        $data += [
            'act_name' => '',
            'client_ip' => '',
            //'logo_imgurl'=>'',
            'mch_billno' => '',
            'mch_id' => $this->mchId,
            'mch_key' => $this->mchKey,
            'nonce_str' => strtolower(self::createNonceStr()),
            're_openid' => '',
            'remark' => '',
            'send_name' => '',
            //'share_content'=>'',
            //'share_imgurl'=>'',
            //'share_url'=>'',
            'total_amount' => '',
            'total_num' => 1,
            'wishing' => '',//祝福语
            'wxappid' => $this->appId,
        ];

        // 商户密钥不参与转换
        $key = $data['mch_key'];
        unset($data['mch_key']);

        ksort($data);
        $signData = urldecode(http_build_query($data, null, '&', PHP_QUERY_RFC3986));
        $signData .= '&key=' . $key;

        $data['sign'] = strtoupper(md5($signData));
        $xml = "<xml>
                <act_name><![CDATA[{$data['act_name']}]]></act_name>
                <client_ip><![CDATA[{$data['client_ip']}]]></client_ip>
                <mch_billno><![CDATA[{$data['mch_billno']}]]></mch_billno>
                <mch_id><![CDATA[{$data['mch_id']}]]></mch_id>
                <nonce_str><![CDATA[{$data['nonce_str']}]]></nonce_str>
                <re_openid><![CDATA[{$data['re_openid']}]]></re_openid>
                <remark><![CDATA[{$data['remark']}]]></remark>
                <send_name><![CDATA[{$data['send_name']}]]></send_name>
                <total_amount><![CDATA[{$data['total_amount']}]]></total_amount>
                <total_num><![CDATA[{$data['total_num']}]]></total_num>
                <wishing><![CDATA[{$data['wishing']}]]></wishing>
                <wxappid><![CDATA[{$data['wxappid']}]]></wxappid>
                <sign><![CDATA[{$data['sign']}]]></sign>
                </xml>";
        return $xml;
    }
}