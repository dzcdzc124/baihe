<?php

use Phalcon\CLI\Dispatcher;

include __DIR__ . '/common.php';

$di->set('dispatcher', function () use ($config) {
    $dispatcher = new Dispatcher;

    $dispatcher->setDefaultTask('App\\Tasks\\Main');
    $dispatcher->setDefaultAction('main');

    return $dispatcher;
});