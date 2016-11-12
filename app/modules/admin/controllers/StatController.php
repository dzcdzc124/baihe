<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\System as SystemHelper;
use App\Models\Order;


class StatController extends ControllerBase
{
    public function indexAction()
    {
        $obj = new Order;
            $connection = $obj->getReadConnection();

            $sql = 'select * from `order` order by id';
            $result = $connection->query($sql);

            //$result->setFetchMode(Db::FETCH_ASSOC);
            $data = $result->fetchAll();


        $stat = Order::find([
            'columns' => 'data, sex, count(*) as total',
            'group' => 'data,sex',
            'order' => "SUBSTRING(data,1,3), FIELD(SUBSTRING(data,5,2),'轻度','典型','重度')"
        ]);



        $this->view->setVars([
            'stat' => $stat,
        ]);
    }

   
}