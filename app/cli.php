<?php

use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;

$config = include __DIR__.'/config/config.php';

$di = new CliDI();

include __DIR__.'/config/loader.php';
include __DIR__.'/config/services/cli.php';

// Create a console application
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = ['task' => 'App\\Tasks\\Main', 'action' => 'main', 'params' => []];
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $a = explode('/', $arg);

        $arguments['task'] = 'App\\Tasks\\' . ucfirst($a[0]);
        $arguments['action'] = isset($a[1]) ? $a[1] : 'main';
    } elseif ($k >= 2) {
        $arguments['params'][] = $arg;
    }
}

// Define global constants for the current task and action
define('CURRENT_TASK',   $arguments['task']);
define('CURRENT_ACTION', $arguments['action']);

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}
