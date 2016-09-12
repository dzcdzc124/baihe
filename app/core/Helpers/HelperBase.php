<?php

namespace App\Helpers;

use Phalcon\DI;

class HelperBase
{
    protected static $di;

    protected static function getDI()
    {
        if ( ! isset(self::$di)) {
            self::$di = DI::getDefault();
        }

        return self::$di;
    }

    protected static function get($name, $parameters = array())
    {
        return self::getDI()->get($name, $parameters);
    }

    protected static function getShared($name, $parameters = array())
    {
        return self::getDI()->getShared($name, $parameters);
    }
}