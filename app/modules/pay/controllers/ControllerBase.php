<?php

namespace App\Modules\Pay\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Helpers\Wechat as WechatHelper;


class ControllerBase extends Controller
{
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            return true;
        }

        return false;
    }

}