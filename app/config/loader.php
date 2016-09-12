<?php
defined('PKG_PATH') || define('PKG_PATH', realpath(__DIR__ . '/../../package'));

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'App\Models'      => $config->application->modelsDir,
    'App\Modules'     => $config->application->modulesDir,
    'App\Tasks'       => $config->application->tasksDir,
    'App'             => $config->application->coreDir,

    'PHPExcel'        => PKG_PATH . '/PHPExcel/',
    'QrCode'          => PKG_PATH . '/QrCode/',
));

$loader->register();

// Use composer autoloader to load vendor classes
if (is_file(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}
