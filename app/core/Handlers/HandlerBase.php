<?php

namespace App\Handlers;

use Phalcon\DI;
use App\Helpers\System;


class Next extends \Exception {}

class Stop extends \Exception {}

class HandlerBase
{
    protected $client;

    protected $session;

    protected static $di;

    public function __construct(&$client, &$session)
    {
        $this->client = &$client;
        $this->session = &$session;
    }

    public static function __callStatic($name, $args)
    {
        $shared = true;
        $parameters = [];

        if (count($args) == 1) {
            $arg1 = $args[0];
            if (is_array($arg1))
                $parameters = $arg1;
            else
                $shared = (boolean) $arg1;
        } elseif (count($args) == 2) {
            $shared = (boolean) $args[0];
            $parameters = (array) $args[1];
        }

        if ($shared)
            return self::getShared($name, $parameters);
        else
            return self::get($name, $parameters);
    }

    public function run()
    {
        return false;
    }

    public function next()
    {
        throw new Next();
    }

    public function stop()
    {
        throw new Stop();
    }

    protected static function getDI()
    {
        if ( ! isset(self::$di)) {
            self::$di = DI::getDefault();
        }

        return self::$di;
    }

    protected static function get($name, $parameters = [])
    {
        return self::getDI()->get($name, $parameters);
    }

    protected static function getShared($name, $parameters = [])
    {
        return self::getDI()->getShared($name, $parameters);
    }

    protected static function runTask($task, $params = [], $output = null)
    {
        return System::runTask($task, $params, $output);
    }

    protected static function sendData($data)
    {
        return self::getShared('broker')->send($data);
    }
}