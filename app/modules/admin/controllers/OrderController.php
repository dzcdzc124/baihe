<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Order;


class OrderController extends ControllerBase
{
    public function indexAction()
    {
        $orderList = Order::find([
            'order' => 'created DESC',
        ]);

        $this->view->setVars([
            'orderList' => $orderList,
        ]);

    }

    public function createAction()
    {
        $this->dispatcher->forward([
            'controller' => 'index',
            'action' => 'update',
        ]);
    }

   
}