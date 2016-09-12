<?php

use Phalcon\DI\FactoryDefault;

$config = include __DIR__.'/config/config.php';

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault;

try {
    /**
     * Read auto-loader
     */
    include __DIR__ . '/config/loader.php';

    /**
     * Read services
     */
    include __DIR__ . '/config/services/web.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    $moduleBase = $config->application->modulesDir;
    $moduleList = (array) $config->application->modules;
    $modules = array();
    foreach ($moduleList as $entry) {
        $path = $moduleBase . $entry . '/Module.php';
        if (is_file($path)) {
            $modules[$entry] = array(
                'className' => sprintf('App\Modules\%s\Module', ucfirst($entry)),
                'path' => $path,
            );
        }
    }
    $application->registerModules($modules);

    echo $application->handle()->getContent();
} catch (Exception $e) {
    if ($config->application->debug) {
        echo $e->getMessage(), '<br>';
        echo nl2br(htmlentities($e->getTraceAsString()));
    } else {
        echo '500 Internal server error.';
    }
}