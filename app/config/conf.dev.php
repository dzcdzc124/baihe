<?php

return new \Phalcon\Config([
    'appName' => 'attach',

    'database' => [
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '123456',
        'dbname' => 'act_attach',
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

    'wechat' => [
        'appId' => 'wx1860a110b3f0e559',
        'appSecret' => '888441698bf3b8e2329c0fd08dd946b3',
        'token' => '66xAhDrWbuP6icSMabAAuZnZ',
        'encodingAESKey' => 'NIje97Rdn0H1Qmm6JFkLse5VuLWXrhDQkSIJ9mjwmTY',

        'mchId' => '1217736201',
        'mchKey' => '40b4eb3129b7e57bc7b0c2fe12085d68',
        'certPath' => __DIR__ . '/apiclient_cert.pem',
        'keyPath' => __DIR__ . '/apiclient_key.pem',
    ],
]);
