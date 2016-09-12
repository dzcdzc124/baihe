<?php

namespace App\Modules\Admin\Controllers;

use App\Lib\Paginator;
use App\Models\Imei;


class ImeiController extends ControllerBase
{
    public function indexAction()
    {
        $query = Imei::query();

        $currentPage = $this->request->getQuery('page', 'int');
        $paginator = new Paginator([
            'query' => $query,
            'limit' => 20,
            'page' => $currentPage,
        ]);

        $page = $paginator->getPaginate();

        $this->view->setVars([
            'page' => $page,
        ]);
    }
}
