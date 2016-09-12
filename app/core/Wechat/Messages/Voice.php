<?php

namespace App\Wechat\Messages;

class Voice extends MessageBase
{
    public $type = 'voice';

    public $mediaId = NULL;

    public function __construct($mediaId = NULL)
    {
        $this->setMedia($mediaId);
    }

    public function setMedia($mediaId)
    {
        $this->mediaId = $mediaId;
    }

    public function outputXML($from, $to)
    {
        $msg = array(
            'ToUserName' => $to,
            'FromUserName' => $from,
            'MsgType' => $this->type,
            'Voice' => array(
                'MediaId' => $this->mediaId,
            ),
            'CreateTime' => TIMESTAMP,
        );
        return $this->XMLEncode($msg);
    }

    public function outputJson($to)
    {
        $msg = array(
            'touser' => $to,
            'msgtype' => $this->type,
            'voice' => array(
                'media_id' => $this->mediaId,
            ),
        );
        return self::formatJson($msg);
    }
}