<?php

return new \Phalcon\Config([
    'appName' => 'attach',

    'database' => [
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '123456',
        'dbname' => 'act_pdq',
        'charset' => 'utf8',
        'persistent' => false,
        'logpath' => APP_PATH . 'cache/logs/',
    ],

    'application' => [
        'debug' => true,
        'timezone' => 'Asia/Shanghai',

        'modules' => ['site', 'admin', 'api'],
        'defaultModule' => 'site',

        'coreDir' => APP_PATH . 'core/',
        'modelsDir' => APP_PATH . 'models/',
        'modulesDir' => APP_PATH . 'modules/',
        'tasksDir' => APP_PATH . 'tasks/',
        'migrationsDir'  => APP_PATH . 'migrations/',
        'pluginsDir'     => APP_PATH . 'plugins/',

        'host' => '127.0.0.1',
        'baseUri' => '/attach',
        'staticUri' => '/attach/static/',
        'staticPath' => WEB_PATH . 'static/',
        'staticVer' => 'DaeD',
        'staticPrefix' => 'http://demofiles.oss-cn-shenzhen.aliyuncs.com/',

        'logger' => [
            'adapter' => 'File',
            'file' => APP_PATH . 'cache/logs/website.log',
            'format' => '[%date%][%type%] %message%',
        ],

        'volt' => [
            'path' => APP_PATH . 'cache/volt/',
            'extension' => '.php',
            'separator' => '%%',
            'stat' => true,
        ],

        'cache' => [
            'lifeTime' => 86400,
            'server' => [
                'prefix' => '__attach__',
                'host' => '127.0.0.1',
                'port' => 6379,
                'persistent' => false,
                'index' => 2,
            ],
        ],

        'cookies' => [
            'encryption' => false,
            'cryptKey' => 'dkinff0TSgtpYCyQuOotbVI5',
            'sessionKey' => 'm5cNUzf4KpZe$FGy$najPQRQ',
        ],
    ],

    'handlers' => ['test'],

]);
