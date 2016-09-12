<?php

namespace App\Lib;

use Phalcon\Session\Adapter\Files as FileSession;

class Session extends FileSession implements \ArrayAccess
{
    public function reset($id = null)
    {
        if ( ! is_null($id))
            $this->setId($id);

        session_reset();
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}