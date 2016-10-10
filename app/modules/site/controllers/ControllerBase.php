<?php

namespace App\Modules\Site\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Helpers\Wechat as WechatHelper;
use App\Models\Users;


class ControllerBase extends Controller
{
    protected $openId;

    public $user;

    protected $isDomain = false;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            if( strpos($_SERVER["HTTP_HOST"],".com")!==false ){
                $this->isDomain = true;
                $this->user = WechatHelper::loginRequired("snsapi_userinfo");
                $wxConfig = WechatHelper::sign();

                $this->view->setVar('user', $this->user);
                $this->view->setVar('wxConfig', $wxConfig);
            }

            return true;
        }

        return false;
    }

}