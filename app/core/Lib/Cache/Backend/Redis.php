<?php

namespace App\Lib\Cache\Backend;

use Phalcon\Cache\Backend\Redis as PhRedis;
use Phalcon\Cache\Exception;


class Redis extends PhRedis
{
    public function _connect()
    {
        $options = $this->_options;
        if (function_exists('pool_server_create')) {
            $redis = new \redisProxy;
        } else {
            $redis = new \Redis;
        }

        $host = isset($options['host']) ? $options['host'] : null;
        $port = isset($options['port']) ? $options['port'] : null;
        $persistent = isset($options['persistent']) ? $options['persistent'] : null;

        if (is_null($host) || is_null($port) || is_null($persistent))
            throw new Exception("Unexpected inconsistency in options");

        if ($persistent) {
            $success = $redis->pconnect($host, $port);
        } else {
            $success = $redis->connect($host, $port);
        }

        if ( ! $success)
            throw new Exception("Could not connect to the Redisd server ".$host.":".$port);
            
        if (isset($options['auth'])) {
            $auth = $options['auth'];
            $success = $redis->auth($auth);

            if ( ! $success)
                throw new Exception("Failed to authenticate with the Redisd server");
        }

        if (isset($options['index'])) {
            $index = $options['index'];
            $success = $redis->select($index);

            if ( ! $success)
                throw new Exception("Redisd server selected database failed");
        }

        $this->_redis = $redis;
    }

    public function release()
    {
        if (function_exists('pool_server_create')) {
            $this->_redis->release();
        }
    }
}