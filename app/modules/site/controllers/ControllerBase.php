<?php

namespace App\Modules\Site\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Helpers\Wechat as WechatHelper;


class ControllerBase extends Controller
{
    protected $openId;

    protected $isDomain = false;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            if( strpos($_SERVER["HTTP_HOST"],".com")!==false ){
                $this->isDomain = true;
                $this->openId = WechatHelper::loginRequired("snsapi_userinfo");
                $this->view->setVar('openId', $this->openId);
            }

            return true;
        }

        return false;
    }
}