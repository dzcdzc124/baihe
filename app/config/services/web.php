<?php

use Phalcon\Flash\Direct as Flash;
use Phalcon\Flash\Session as FlashSession;

include __DIR__ . '/common.php';

/**
 * Loading routes from the routes.php file
 */
$di->set('router', function () use ($config) {
    require APP_PATH . 'config/routes.php';
    return $router;
});

/**
 * Register the flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash(array(
        'error' => 'alert alert-error',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flashSession', function () {
    return new FlashSession(array(
        'error' => 'alert alert-error',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
});