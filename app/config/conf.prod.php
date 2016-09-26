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
