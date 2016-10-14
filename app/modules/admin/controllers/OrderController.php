<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Lib\Paginator;

class OrderController extends ControllerBase
{
    public function indexAction()
    {
        $wxpay = $this->request->getQuery('wxpay', 'int');
        $code = $this->request->getQuery('code', 'int');


        if( $wxpay || $code){
            $type = $wxpay?"wxpay":"code";
            $query = Order::query()
                        ->leftJoin('App\Models\User', 'u.id = user_id', 'u')
                        ->where("App\Models\Order.type = :type:")
                        ->bind(["type" => $type])
                        ->columns([
                                    'App\Models\Order.id',
                                    'App\Models\Order.order_id',
                                    'App\Models\Order.total_fee',
                                    'App\Models\Order.status',
                                    'App\Models\Order.type',
                                    'App\Models\Order.data',
                                    'App\Models\Order.updated',
                                    'u.nickname',
                        ])
                        ->order("App\Models\Order.created desc")
                ;
        }else{
            $query = Order::query()
                        ->leftJoin('App\Models\User', 'u.id = user_id', 'u')
                        ->columns([
                            'App\Models\Order.id',
                            'App\Models\Order.order_id',
                            'App\Models\Order.total_fee',
                            'App\Models\Order.status',
                            'App\Models\Order.type',
                            'App\Models\Order.data',
                            'App\Models\Order.updated',
                            'u.nickname',
                        ])
                        ->order("App\Models\Order.created desc");
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
            'wxpay' => $wxpay,
            'code' => $code,
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