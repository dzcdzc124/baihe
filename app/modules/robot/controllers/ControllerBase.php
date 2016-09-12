<?php

namespace App\Modules\Robot\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Controller;


class ControllerBase extends Controller
{
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            $this->view->disable();

            return true;
        }

        return false;
    }

    protected function log($msg)
    {
        if ($this->config->application->debug)
            file_put_contents(APP_PATH . 'cache/logs/test.log', var_export($msg, true)."\n", FILE_APPEND);
    }
}