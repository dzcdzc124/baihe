<?php

namespace App\Modules\Site\Controllers;

use App\Helpers\Imei as ImeiHelper;
use App\Models\Question;


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

        $this->view->setVars([
            'questionList' => $questionList,
        ]);
    }

}