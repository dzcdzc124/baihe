<?php

namespace App\Modules\Admin\Controllers;

use App\Models\User;
use App\Lib\Paginator;

class UserController extends ControllerBase
{
    public function indexAction()
    {
        //$wxpay = $this->request->getQuery('wxpay', 'int');
        //$code = $this->request->getQuery('code', 'int');

        $query = User::query()
                    ->order("created desc");

        $currentPage = $this->request->getQuery('page', 'int');
        $paginator = new Paginator([
            'query' => $query,
            'limit' => 20,
            'page' => $currentPage,
        ]);

        $page = $paginator->getPaginate();

        $this->view->setVars([
            'page' => $page
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