<?php

namespace App\Modules\Api\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Helpers\Wechat as WechatHelper;


class ControllerBase extends Controller
{
    protected $loginRequired = true;

    public $user;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            if ($this->loginRequired){
                if( strpos($_SERVER["HTTP_HOST"],".com")!==false ){
                    $this->user = WechatHelper::loginRequired("snsapi_userinfo");
                    //$this->view->setVar('user', $this->user);
                }
            }
            return true;
        }

        return false;
    }

}