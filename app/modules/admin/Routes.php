<?php

namespace App\Modules\Admin;

use App\Lib\Routes as RoutesBase;

class Routes extends RoutesBase
{
    public function initialize()
    {
        parent::initialize();

        $this->add('/prize', [
            'namespace' => 'App\\Modules\\Admin\\Controllers\\Prize',
            'controller' => 'index',
        ]);

        $this->add('/prize/:controller', [
            'namespace' => 'App\\Modules\\Admin\\Controllers\\Prize',
            'controller' => 1,
        ]);

        $this->add('/prize/:controller/:action', [
            'namespace' => 'App\\Modules\\Admin\\Controllers\\Prize',
            'controller' => 1,
            'action' => 2,
        ]);
    }
}