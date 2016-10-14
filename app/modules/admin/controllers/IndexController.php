<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\System as SystemHelper;
use App\Models\Order;
use App\Models\User;
use App\Models\Code;

class IndexController extends ControllerBase
{
    public function indexAction()
    {

        $this->view->setVars([
            'userTotal' => User::count(),
            'orderTotal' => Order::count(),
            'payTotal' => Order::count( "type = 'wxpay'"),
            'codeTotal' => Order::count( "type = 'code'")
        ]);
    }
    
}
