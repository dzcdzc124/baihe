<?php

namespace App\Lib\Db\Adapter;

use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;


class Mysql extends MysqlAdapter
{
    public function connect($descriptor = null)
    {
        if (is_null($descriptor))
            $descriptor = $this->_descriptor;

        if (isset($descriptor['username'])) {
            $username = $descriptor['username'];
            unset($descriptor['username']);
        } else {
            $username = null;
        }

        if (isset($descriptor['password'])) {
            $password = $descriptor['password'];
            unset($descriptor['password']);
        } else {
            $password = null;
        }

        if (isset($descriptor['options'])) {
            $options = $descriptor['options'];
            unset($descriptor['options']);
        } else {
            $options = [];
        }

        if (isset($descriptor['persistent'])) {
            $persistent = $descriptor['persistent'];
            if ($persistent) {
                $options[\Pdo::ATTR_PERSISTENT] = true;
            }
            unset($descriptor['persistent']);
        }


        if (isset($descriptor['dialectClass'])) {
            unset($descriptor['dialectClass']);
        }

        if (isset($descriptor['dsn'])) {
            $dsnAttributes = $descriptor['dsn'];
        } else {
            $dsnParts = [];
            foreach ($descriptor as $key => $value) {
                $dsnParts[] = $key . '=' . $value;
            }
            $dsnAttributes = implode(';', $dsnParts);
        }

        $options[\Pdo::ATTR_ERRMODE] = \Pdo::ERRMODE_EXCEPTION;

        if (function_exists('pool_server_create')) {
            $this->_pdo = new \pdoProxy($this->_type . ":" . $dsnAttributes, $username, $password, $options);
        } else {
            $this->_pdo = new \Pdo($this->_type . ":" . $dsnAttributes, $username, $password, $options);
        }
    }

    public function release()
    {
        if (function_exists('pool_server_create')) {
            $this->_pdo->release();
        }
    }
}