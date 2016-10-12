<?php

namespace App\Modules\Site\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\Product;
use App\Helpers\Wechat as WechatHelper;



class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVars([
            'isWeiXin' => $this->userAgent->isWeixin(),
            'isMobile' => $this->userAgent->isMobile()
        ]);

        $questionList = Question::find([
            'order' => 'sort',
        ]);

        $product = Product::findByModule("pdq");

        if( $this->isDomain ){
            $wxConfig = WechatHelper::sign();
            $this->view->setVar('wxConfig', $wxConfig);
        }


        $this->view->setVars([
            'questionList' => $questionList,
            'product' => $product
        ]);


    }
}