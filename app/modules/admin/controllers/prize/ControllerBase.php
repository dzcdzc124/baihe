<?php

namespace App\Modules\Admin\Controllers\Prize;

use Phalcon\Mvc\Dispatcher;

use App\Modules\Admin\Controllers\ControllerBase as _ControllerBase;


class ControllerBase extends _ControllerBase
{
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();
        $view = 'prize/'.$controllerName.'/'.$actionName;
        $this->view->pick($view);
    }
}