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
