<?php

namespace App\Modules\Api\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Models\User;
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
                }else{
                    $this->user = User::findById(1);
                }
            }
            return true;
        }

        return false;
    }

}