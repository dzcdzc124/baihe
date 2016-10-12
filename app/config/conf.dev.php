<?php

return new \Phalcon\Config([
    'appName' => 'baihe',

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

        'modules' => ['site', 'admin', 'api', 'pay'],
        'defaultModule' => 'site',

        'coreDir' => APP_PATH . 'core/',
        'modelsDir' => APP_PATH . 'models/',
        'modulesDir' => APP_PATH . 'modules/',
        'tasksDir' => APP_PATH . 'tasks/',
        'migrationsDir'  => APP_PATH . 'migrations/',
        'pluginsDir'     => APP_PATH . 'plugins/',

        'host' => '127.0.0.1',
        'baseUri' => '/',
        'staticUri' => '/static/',
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
                'prefix' => '__baihe__',
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
    'wechat' => [
        'appId' => 'wxcdde906c2ef572c5',
        'appSecret' => '7252ee70c5b38bf9799c8b74ff71c9e4',
        'token' => '',
        'encodingAESKey' => '',

        'mchId' => '1396588502',
        'mchKey' => 'PWFntjQoWysri6ovCucHEFqgnEq31D4n',
        'certPath' => __DIR__ . '/apiclient_cert.pem',
        'keyPath' => __DIR__ . '/apiclient_key.pem',
    ],
]);
