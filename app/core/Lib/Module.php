<?php

namespace App\Lib;

use Phalcon\DI;
use Phalcon\Loader;
use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

use App\Lib\Mvc\View;
use App\Lib\Mvc\Dispatcher;

class Module implements ModuleDefinitionInterface
{
    public $id;

    public $config;

    public function __construct()
    {
        $di = DI::getDefault();
        $this->config = $di->getShared('config');

        $className = get_class($this);
        $tmpArr = explode('\\', $className);

        try {
            $this->id = strtolower($tmpArr[2]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function registerAutoloaders(DiInterface $dependencyInjector = null)
    {
        $id = $this->id;
        $name = ucfirst($id);
        $config = $dependencyInjector->getShared('config');

        $loader = new Loader;
        $loader->registerNamespaces(
            array(
                sprintf('App\\Modules\\%s', $name) => $config->application->modulesDir . $id . '/library',
                sprintf('App\\Modules\\%s\\Controllers', $name) => $config->application->modulesDir . $id . '/controllers',
                sprintf('App\\Modules\\%s\\Models', $name) => $config->application->modulesDir . $id . '/models',
                sprintf('App\\Modules\\%s\\Forms', $name) => $config->application->modulesDir . $id . '/forms',
                sprintf('App\\Modules\\%s\\Hooks', $name) => $config->application->modulesDir . $id . '/hooks',
            )
        );
        $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices(DiInterface $dependencyInjector)
    {
        $config = $dependencyInjector->getShared('config');
        $id = $this->id;
        $name = ucfirst($id);

        $dependencyInjector->set('dispatcher', function () use ($name) {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace(sprintf("App\\Modules\\%s\\Controllers", $name));
            return $dispatcher;
        });

        $dependencyInjector->set('view', function () use ($id, $config) {
            $view = new View();
            $view->setViewsDir($config->application->modulesDir . $id . '/views');
            return $view;
        });
    }
}
