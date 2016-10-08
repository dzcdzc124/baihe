<?php

namespace App\Modules\Site\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Helpers\Wechat as WechatHelper;


class ControllerBase extends Controller
{
    protected $openId;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            //$this->openId = WechatHelper::loginRequired("snsapi_userinfo");
            //$this->view->setVar('openId', $this->openId);

            return true;
        }

        return false;
    }
}