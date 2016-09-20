<?php

// Define path's constans
defined('IN_APP') || define('IN_APP', true);
defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__.'/../../').'/');
defined('APP_PATH') || define('APP_PATH', BASE_PATH . 'app/');
defined('WEB_PATH') || define('WEB_PATH', BASE_PATH);


defined('APP_START_TIME') || define('APP_START_TIME', microtime(true));
ob_start();

// Include the common functions.
include __DIR__ . '/functions.php';

// Read the configuration
$config = include __DIR__ . '/conf.dev.php';
$env = getenv('PHP_ENV');
$isBaihe = getenv('baihe');
if ($isBaihe || $env == 'production')
    $env = 'prod';

$envConfigFile = __DIR__ . '/conf.' . strtolower($env) . '.php';
if (is_file($envConfigFile)) {
    $envConfig = include $envConfigFile;
    $config->merge($envConfig);
}

date_default_timezone_set($config->application->timezone);

defined('TIMESTAMP') || define('TIMESTAMP', time());
defined('TODAY_START') || define('TODAY_START', mktime(0, 0, 0));
defined('TODAY_END') || define('TODAY_END', mktime(23, 59, 59));
defined('DATE_STR') || define('DATE_STR', date('Ymd', TIMESTAMP));
defined('TIME_STR') || define('TIME_STR', date('r', TIMESTAMP));


return $config;