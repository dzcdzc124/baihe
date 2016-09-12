<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\System as SystemHelper;


class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVars([
            'userTotal' => 0,
            'signupTotal' => 0,
            'deleteTotal' => 0,
            'viewTotal' => 0,
        ]);
    }

    public function testAction()
    {
        SystemHelper::runTask('sms/send', ['virtualCardId' => 1]);
    }
}
