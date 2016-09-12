<?php

use Phalcon\Logger\Adapter\File as Logger;
use Phalcon\Cache\Frontend\Data as DataFrontend;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Mvc\Model\MetaData\Files as PhMetaData;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Security;
use Phalcon\Crypt;
use Phalcon\Http\Response\Cookies;
use Phalcon\Filter;

use App\Lib\Db\Adapter\Mysql;
use App\Lib\Cache\Backend\Redis as RedisCache;
use App\Lib\Http\Request;
use App\Lib\Mvc\Url;
use App\Lib\Auth;
use App\Lib\UserAgent;
use App\Lib\Setting;
use App\Lib\Mutex;
use App\Lib\Broker;
use App\Lib\Session;

/**
 * Register the global configuration as config
 */
$di->set('config', $config);

$di->set('request', function () {
    $request = new Request;
    return $request;
}, true);

$di->set('filter', function () {
    $filter = new Filter;

    $filter->add('boolean', function($value){
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    })->add('boolint', function($value){
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    });

    return $filter;
}, true);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new Url();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

$di->set('logger', function () use ($config) {
    $logger = new Logger($config->application->logger->file);
    return $logger;
}, true);

$di->set('cache', function () use ($config) {
    // Get the parameters
    $frontend = new DataFrontend(array(
        'lifetime' => $config->application->cache->lifeTime,
    ));

    $cache = new RedisCache($frontend, (array) $config->application->cache->server);

    return $cache;
}, true);

$di->set('db', function () use ($config) {
    $connection = new Mysql(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname,
        "charset" => $config->database->charset,
        "persistent" => $config->database->persistent,
    ));


    $eventsManager = new EventsManager;
    $logger = new Logger($config->database->logpath . date('Y-m-d') . '-db.log');
    $eventsManager->attach('db', function ($event, $connection) use ($config, $logger) {
        if ($event->getType() == 'beforeQuery') {
            if ($config->application->debug) {
                $logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
            }
        } elseif ($event->getType() == 'afterQuery') {
            $connection->release();
        }
    });
    $connection->setEventsManager($eventsManager);


    return $connection;
}, true);

$di->set('modelsManager', function () {
    return new ModelManager;
}, true);

if (! $config->application->debug) {
    $di->set('modelsMetadata', function () use ($config) {
        $metaData = new PhMetaData(array(
            'metaDataDir' => $config->database->logpath,
            'lifetime' => 86400,
            'prefix' => 'my-prefix',
        ));
        
        return $metaData;
    }, true);
}

$di->set('security', function () {
    $security = new Security;
    $security->setWorkFactor(16);
    return $security;
}, true);

$di->set('crypt', function () use ($config) {
    $crypt = new Crypt;
    $crypt->setKey($config->application->cookies->cryptKey);
    return $crypt;
}, true);

$di->set('cookies', function () use ($config) {
    $cookies = new Cookies;
    $cookies->useEncryption($config->application->cookies->encryption);
    return $cookies;
}, true);

$di->set('setting', function () {
    return new Setting();
}, true);

$di->set('auth', function () {
    return new Auth;
}, true);

$di->set('userAgent', function () {
    return new UserAgent;
}, true);

$di->set('mutex', function () {
    return new Mutex;
}, true);

$di->set('broker', function () {
    return new Broker;
}, true);

$di->set('session', function () {
    $session = new Session();
    $session->start();
    return $session;
}, true);