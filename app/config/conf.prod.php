<?php

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => '192.168.100.124',
        'username' => 'act_x7',
        'password' => 'PJEiLnDO7otGg07c',
        'dbname' => 'act_x7',
        'charset' => 'utf8',
        'persistent' => false,
    ],

    'application' => [
        'debug' => false,

        'host' => 'act.vivo.com.cn',

        'cache' => [
            'lifeTime' => 86400,
            'server' => [
                'host' => '192.168.100.121',
                'port' => 6379,
                'persistent' => false,
                'index' => 7,
            ],
        ],
    ],
]);
