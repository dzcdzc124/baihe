<?php

namespace App\Wechat\Messages;

class Music extends MessageBase
{
    public $type = 'music';

    public $title = NULL;
    public $description = NULL;
    public $musicUrl = NULL;
    public $hqMusicUrl = NULL;
    public $thumbMediaId = NULL;

    public function __construct(array $music = array())
    {
        $this->setMusic($music);
    }

    public function setMusic(array $music)
    {
        $this->title = array_get($music, 'title');
        $this->description = array_get($music, 'description');
        $this->musicUrl = array_get($music, 'musicUrl');
        $this->hqMusicUrl = array_get($music, 'hqMusicUrl');
        $this->thumbMediaId = array_get($music, 'thumbMediaId');
    }

    public function outputXML($from, $to)
    {
        $msg = array(
            'ToUserName' => $to,
            'FromUserName' => $from,
            'MsgType' => $this->type,
            'Music' => array(
                'Title' => $this->title,
                'Description' => $this->description,
                'MusicUrl' => $this->musicUrl,
                'HQMusicUrl' => $this->hqMusicUrl,
                'ThumbMediaId' => $this->thumbMediaId,
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
            'music' => array(
                'title' => $this->title,
                'description' => $this->description,
                'musicurl' => $this->musicUrl,
                'hqmusicurl' => $this->hqMusicUrl,
                'thumb_media_id' => $this->thumbMediaId,
            ),
        );
        return self::formatJson($msg);
    }
}