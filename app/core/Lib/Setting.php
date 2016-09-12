<?php

namespace App\Lib;

use Phalcon\DI;
use Phalcon\Mvc\User\Component;

use App\Models\Setting as SettingModel;

class Setting extends Component implements \ArrayAccess
{
    private $data = [];

    public function __construct()
    {return;
        $cache = $this->getDI()->getShared('cache');

        if ($cache->exists('settings')) {
            $this->data = $cache->get('settings');
        } else {
            $this->reload();
        }
    }

    public function __get($name)
    {
        return $this[$name];
    }

    public function __set($name, $value)
    {
        $this[$name] = $value;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            if ( ! $this->isSame($offset, $value)) {
                $this->data[$offset] = $value;
                $this->save($offset, $value);
            }
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
        $this->delete($offset);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function isSame($name, $value)
    {
        if ( ! isset($this->data[$name]))
            return false;

        return $this->data[$name] === $value;
    }

    public function get($name, $default = null, $filter = null)
    {
        if (isset($this[$name])) {
            $value = $this[$name];

            if ( ! is_null($filter)) {
                try {
                    $value = call_user_func($filter, $value);
                } catch (\Exception $e) {
                    return $default;
                }
            }

            return $value;
        }

        return $default;
    }

    public function reload()
    {
        $cache = $this->getDI()->getShared('cache');

        $settingList = SettingModel::find();
        $settings = [];
        foreach ($settingList as $setting) {
            $settings[$setting->name] = $setting->getValue();
        }
        $cache->save('settings', $settings, 86400 * 365);
        $this->data = $settings;
    }

    public function all()
    {
        return $this->data;
    }

    public function save($name, $value = null)
    {
        SettingModel::saveIt($name, $value);
    }

    public function delete($name)
    {
        SettingModel::deleteId($name);
    }

    public function getInt($name, $default = 0)
    {
        return $this->get($name, $default, 'intval');
    }

    public function getFloat($name, $default = 0.0)
    {
        return $this->get($name, $default, 'floatval');
    }

    public function getArray($name, $default = [])
    {
        return $this->get($name, $default, function($value){
            if (is_array($value))
                return $value;

            return preg_split('/[\\s,\\|]+/', $value);
        });
    }

    public function getTimestamp($name, $default = 0)
    {
        return $this->get($name, $default, 'strtotime');
    }

    public function getFromModel($name, $model, $field = 'id')
    {
        $value = $this->get($name);
        if (empty($value))
            return null;

        $method = 'findFirstBy'.ucfirst($field);
        return call_user_func(['App\\Models\\'.ucfirst($model), $method], $value);
    }
}