<?php

namespace App\Wechat\Messages;

class Video extends MessageBase
{
    public $type = 'video';

    public $mediaId = NULL;
    public $title = NULL;
    public $description = NULL;

    public function __construct($mediaId = NULL, $title = NULL, $description = NULL)
    {
        $this->setMedia($mediaId, $title, $description);
    }

    public function setMedia($mediaId, $title = NULL, $description = NULL)
    {
        $this->mediaId = $mediaId;
        $this->title = $title;
        $this->description = $description;
    }

    public function outputXML($from, $to)
    {
        $msg = array(
            'ToUserName' => $to,
            'FromUserName' => $from,
            'MsgType' => $this->type,
            'Video' => array(
                'MediaId' => $this->mediaId,
                'Title' => $this->title,
                'Description' => $this->description,
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
            'video' => array(
                'media_id' => $this->mediaId,
                'title' => $this->title,
                'description' => $this->description,
            ),
        );
        return self::formatJson($msg);
    }
}