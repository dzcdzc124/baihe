<?php

namespace App\Modules\Site;

use App\Lib\Routes as RoutesBase;

class Routes extends RoutesBase
{
    public function initialize()
    {
        parent::initialize();

        $this->add('/([a-zA-Z0-9]+)', [
            'controller' => 'index',
            'action' => 'index',
            'district' => 1,
        ]);
    }
}