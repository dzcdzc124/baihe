<?php

namespace App\Modules\Pay;

use App\Lib\Routes as RoutesBase;

class Routes extends RoutesBase
{
    public function initialize()
    {
        parent::initialize();

        $this->add('/:controller/:action.js', [
            'controller' => 1,
            'action' => 2,
        ]);
    }
}