<?php

namespace App\Wechat\Messages;

class Text extends MessageBase
{
    public $type = 'text';

    public $text = NULL;

    public function __construct($text = NULL)
    {
        $this->setText($text);
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function outputXML($from, $to)
    {
        $msg = array(
            'ToUserName' => $to,
            'FromUserName' => $from,
            'MsgType' => $this->type,
            'Content' => $this->text,
            'CreateTime' => TIMESTAMP,
        );
        return $this->XMLEncode($msg);
    }

    public function outputJson($to)
    {
        $msg = array(
            'touser' => $to,
            'msgtype' => $this->type,
            'text' => array(
                'content' => $this->text,
            ),
        );
        return self::formatJson($msg);
    }
}