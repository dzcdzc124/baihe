<?php

namespace App\Modules\Admin\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Modules\Admin\Menu;


class ControllerBase extends Controller
{
    protected $currentUser;

    protected $access;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            $controllerName = $dispatcher->getControllerName();

            if ($this->auth->isGuest() && $controllerName != 'auth') {
                $dispatcher->forward([
                    'controller' => 'auth',
                    'action' => 'login',
                ]);
                return false;
            }

            $this->currentUser = $this->auth->user();
            if ($this->access && ! $this->currentUser->hasAccess($this->access))
                $this->redirect($this->url->get('/admin/'));

            $menu = new Menu;
            $this->view->setVars([
                'identity' => $this->currentUser,
                'menu' => $menu,
            ]);

            return true;
        } else {
            return false;
        }
    }
}