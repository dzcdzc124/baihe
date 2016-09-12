<?php

namespace App\Modules\Admin;

use Phalcon\Loader;
use Phalcon\DiInterface;

use App\Lib\Module as ModuleBase;

class Module extends ModuleBase
{
    public function registerAutoloaders(DiInterface $dependencyInjector = null)
    {
        parent::registerAutoloaders($dependencyInjector);
        
        $loader = new Loader;
        $loader->registerNamespaces([
            'App\\Modules\\Admin\\Controllers\\Prize' => __DIR__ . '/controllers/prize',
        ]);
        $loader->register();
    }
}