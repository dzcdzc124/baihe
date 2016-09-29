<?php

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'gd0508210',
        'dbname' => 'act_pdq',
        'charset' => 'utf8',
        'persistent' => false,
    ],

    'application' => [
        'debug' => false,

        'host' => '127.0.0.1',

        'cache' => [
            'lifeTime' => 86400,
            'server' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'persistent' => false,
                'index' => 7,
            ],
        ],
    ],
    'wechat' => [
        'appId' => 'wxdee05db36f224903',
        'appSecret' => '7e883ea741e983b70f30c03aab40328a',
        'token' => '',
        'encodingAESKey' => '',

        'mchId' => '',
        'mchKey' => '',
        'certPath' => __DIR__ . '/apiclient_cert.pem',
        'keyPath' => __DIR__ . '/apiclient_key.pem',
    ],
]);
