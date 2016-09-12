<?php

return new \Phalcon\Config([
    'appName' => 'x7',

    'database' => [
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'toor',
        'dbname' => 'act_x7',
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

        'host' => 'act.vivotest.pw',
        'baseUri' => '/x7',
        'staticUri' => '/x7/static/',
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
                'prefix' => '__x7__',
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

    'sms' => [
        'url' => 'http://219.130.39.253:8888/WebSvc/SmsService.asmx?wsdl',
        'account' => '10943731',
        'password' => 'vivo10943731',
        'compCode' => '10088',
    ],

    'games' => [
        '奔跑吧兄弟4-撕名牌大战' => 'http://info.appstore.vivo.com.cn/detail/496237?source=5',
        '宾果消消乐' => 'http://info.appstore.vivo.com.cn/detail/59063?source=5',
        '欢喜斗地主' => 'http://info.appstore.vivo.com.cn/detail/880064?source=5',
        '火柴人联盟' => 'http://info.appstore.vivo.com.cn/detail/99535?source=5',
        '饥饿鲨进化' => 'http://info.appstore.vivo.com.cn/detail/78844?source=5',
    ],
]);
