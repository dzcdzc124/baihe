<?php

namespace App\Lib;

use Phalcon\Mvc\User\Component;

class Mutex extends Component
{
    private $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->getShared('cache');
    }

    public function isLock($name)
    {
        if ($this->cache->exists($name))
            return true;

        return false;
    }

    public function lock($name, $ttl = null, $force = false)
    {
        if ( ! $force && $this->isLock($name))
            return false;

        if (is_null($ttl))
            $ttl = $this->getDI()->getShared('setting')->get('lockTime', 600);

        $this->cache->save($name, true, $ttl);
        return true;
    }

    public function update($name, $ttl = null)
    {
        return $this->lock($name, $ttl, true);
    }

    public function unlock($name)
    {
        if ($this->cache->exists($name))
            $this->cache->delete($name);

        return true;
    }
}