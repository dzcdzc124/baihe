<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group;

use App\Lib\Routes;


$router = new Router(false);

$moduleList = (array) $config->application->modules;

$router->setDefaultModule($config->application->defaultModule);
$router->setDefaultController('index');
$router->setDefaultAction('index');

$router->removeExtraSlashes(true);

$moduleBase = $config->application->modulesDir;
foreach ($moduleList as $entry) {
    $router->add('/'.$entry, [
        'module' => $entry,
    ]);

    $routesFile = $moduleBase . $entry . '/Routes.php';
    if (is_file($routesFile)) {
        include_once $routesFile;

        $className = sprintf('App\\Modules\\%s\\Routes', ucfirst($entry));
        $group = new $className;
        $router->mount($group);
    } else {
        $group = new Routes($entry);
        $router->mount($group);
    }
}
