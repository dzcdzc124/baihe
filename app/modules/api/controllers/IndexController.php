<?php

namespace App\Modules\Api\Controllers;


class IndexController extends ControllerBase
{
    public function indexAction()
    {
        exitmsg('Access Denied.');
    }

    public function testAction()
    {
        if (isset($_COOKIE['current-district']))
            var_dump($_COOKIE['current-district']);
        else
            echo 'None.';

        exit;
    }
}