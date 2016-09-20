<?php

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'gd0508210',
        'dbname' => 'act_attach',
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
]);
