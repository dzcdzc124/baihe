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

class Storage implements \ArrayAccess
{
    private $cache;
    private $prefix;
    private $data = [];

    public function __construct($cache, $prefix = '')
    {
        $this->cache = $cache;
        $this->prefix = $prefix;
    }

    public function offsetSet($offset, $value)
    {
        if (is_string($offset))
            $this->save($offset, $value);
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
         if ( ! is_string($offset))
            return null;

        return $this->get($offset);
    }

    public function get($name, $default = null)
    {
        if (isset($this->data[$name]))
            return $this->data[$name];

        $cacheName = $this->cacheName($name);
        if ($this->cache->exists($cacheName)) {
            $value = $this->cache->get($cacheName);
            $this->data[$name] = $value;

            return $value;
        }

        return $default;
    }

    public function save($name, $value, $ttl = 0)
    {
        $cacheName = $this->cacheName($name);

        $this->data[$name] = $value;
        $this->cache->save($cacheName, $value, $ttl);
    }

    private function cacheName($name)
    {
        return $this->prefix . '_' . $name;
    }
}