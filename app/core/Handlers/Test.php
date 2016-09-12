<?php

namespace App\Handlers;

use App\Helpers\Wechat as WechatHelper;


class Test extends HandlerBase
{
    public function run()
    {
        if ( ! self::getShared('config')->application->debug)
            $this->next();

        $msgType = strtolower($this->client->getRevMsgType());

        if ($msgType == 'text') {
            $userId = $this->client->getRevFromUserName();

            $content = $this->client->getRevContent();
            return $this->client->replyTextMessage('测试成功：' . $content);
            $this->stop();            
        }
    }
}
