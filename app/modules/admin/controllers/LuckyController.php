<?php

namespace App\Modules\Admin\Controllers;


class LuckyController extends ControllerBase
{
    public function indexAction()
    {
        $prizeType = (int) $this->request->getQuery('type', 'int');
        if ($prizeType <= 0)
            $prizeType = 1;

        $this->view->setVars([
            'type' => $prizeType,
        ]);
    }
}
