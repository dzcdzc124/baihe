<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Order;
use App\Lib\Paginator;

class OrderController extends ControllerBase
{
    public function indexAction()
    {
        $pay = $this->request->getQuery('pay', 'int');


        if( $pay ){
            $query = Order::query()
                ->where("status = :status:")
                ->bind(["status" => 1])
                ->order("created desc");
        }else{
            $query = Order::query()->order("created desc");
        }

        $currentPage = $this->request->getQuery('page', 'int');
        $paginator = new Paginator([
            'query' => $query,
            'limit' => 20,
            'page' => $currentPage,
        ]);

        $page = $paginator->getPaginate();

        $this->view->setVars([
            'page' => $page,
            'pay' => $pay
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