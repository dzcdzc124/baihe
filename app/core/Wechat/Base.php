<?php

namespace App\Wechat;

use App\Wechat\Utils\ErrorCode;


class Base
{
    const MSGTYPE_TEXT = 'text';

    const MSGTYPE_IMAGE = 'image';

    const MSGTYPE_LOCATION = 'location';

    const MSGTYPE_LINK = 'link';

    const MSGTYPE_EVENT = 'event';

    const MSGTYPE_MUSIC = 'music';

    const MSGTYPE_NEWS = 'news';

    const MSGTYPE_VOICE = 'voice';

    const MSGTYPE_VIDEO = 'video';

    protected $type = null;

    protected $postStr;

    protected $receive = array();

    protected $token;

    protected $encodingAESKey;

    protected $storage = [];

    protected $crpyt;

    protected $inputed = false;

    protected static $obj;

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 6) == 'getRev') {
            $name = strtolower(substr($name, 6));
            return $this->getRev($name, array_shift($arguments));
        } elseif (preg_match('/^send(text|image|voice|video|file|news|mpnews)(msg|message)$/i', $name, $matches)) {
            $type = $matches[1];
            $arguments = array_merge([$type], $arguments);
            return call_user_func_array([$this, 'sendMessage'], $arguments);
        } elseif (preg_match('/^reply(text|image|voice|video|news)(msg|message)$/i', $name, $matches)) {
            $type = $matches[1];
            $arguments = array_merge([$type], $arguments);
            return call_user_func_array([$this, 'replyMessage'], $arguments);
        }

        return null;
    }

    public function __get($name)
    {
        if (substr($name, 0, 3) == 'rev') {
            $name = strtolower(substr($name, 3));
            return $this->getRev($name);
        } else {
            $method = 'get' . ucfirst($name);
            if (method_exists($this, $method))
                return call_user_func([$this, $method]);

            return null;
        }
    }

    public static function &object()
    {
        return self::$obj;
    }

    public function input($sMsg = null)
    {
        if ($this->inputed)
            return false;

        $this->inputed = true;

        if (is_null($sMsg)) {
            $postStr = file_get_contents("php://input");

            $toUserName = ($this->type == 'corp') ? $this->corpId : $this->appId;
            $encryptType = self::element($_GET, 'encrypt_type', null);

            if ($this->type == 'mp' && $encryptType != 'aes') {
                $sMsg = $postStr;
            } else {
                $rec = (array) simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

                if ($rec['ToUserName'] != $toUserName)
                    throw new Exception('Access Denied.');

                $sMsgSig = self::element($_GET, 'msg_signature', '');
                $sTimeStamp = self::element($_GET, 'timestamp', '');
                $sNonce = self::element($_GET, 'nonce', '');

                $errcode = $this->crypt->DecryptMsg($sMsgSig, $sTimeStamp, $sNonce, $postStr, $sMsg);
                if ($errcode != ErrorCode::$OK)
                    throw new Exception('Access Denied.');
            }
        }

        if ( ! empty($sMsg)) {
            $this->postStr = $sMsg;
            $this->receive = (array) simplexml_load_string($sMsg, 'SimpleXMLElement', LIBXML_NOCDATA);

            $this->receive = array_change_key_case($this->receive, CASE_LOWER);
        }

        return $this;
    }

    public function getRevXML()
    {
        if ($this->inputed)
            return $this->postStr;
        else
            return false;
    }

    public function getRev($name, $default = null)
    {
        $converts = [
            'from' => 'fromusername',
            'fromuser' => 'fromusername',
            'to' => 'tousername',
            'touser' => 'tousername',
        ];

        $name = strtolower($name);
        $name = self::element($converts, $name, $name);

        return self::element((array) $this->receive, $name, $default);
    }

    /* 被动响应消息 */
    public function reply($rawXml)
    {
        if (empty($this->crypt))
            return $rawXml;

        $nonceStr = self::createNonceStr();
        $timestamp = strval(time());
        $errcode = $this->crypt->EncryptMsg($rawXml, $timestamp, $nonceStr, $encryptXml);
        if ($errcode != ErrorCode::$OK)
            return $rawXml;

        return $encryptXml;
    }

    /* 被动响应消息 */
    public function replyMessage($type, $data)
    {
        $type = strtolower($type);
        $userData = [
            'target' => $this->getRev('FromUserName'),
            'source' => $this->getRev('ToUserName'),
        ];

        $reply = null;
        switch ($type) {
            case 'text':
                $data = is_string($data) ? ['content' => $data] : (array) $data;
                $data += $userData;
                $reply = new Replies\TextReply($data);
                break;

            case 'image':
            case 'voice':
            case 'video':
                $data = is_string($data) ? ['mediaId' => $data] : (array) $data;
                $data += $userData;
                if ($type == 'image')
                    $reply = new Replies\ImageReply($data);
                elseif ($type == 'voice')
                    $reply = new Replies\VoiceReply($data);
                else
                    $reply = new Replies\VideoReply($data);
                break;

            case 'news':
                $data = (array) $data;
                $reply = new Replies\ArticleReply;
                foreach ($data as $item) {
                    $reply->addItem($item);
                }
                break;
        }

        if (empty($reply))
            return 'success';

        $response = $reply->render();
        return $this->reply($response);
    }

    protected static function element(array $arr, $key, $default = null)
    {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }

    protected static function formatJson(array $data = array())
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    protected static function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}