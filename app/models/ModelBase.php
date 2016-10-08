<?php

namespace App\Models;

use Phalcon\Mvc\Model;


class ModelBase extends Model
{
    protected $_isNew = true;

    protected $tableName = null;

    public function getSource()
    {
        if (empty($this->tableName)) {
            $className = get_class($this);
            $tableName = substr($className, strrpos($className, '\\') + 1);
            $this->tableName = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $tableName));
        }
        return $this->tableName;
    }

    public function initialize()
    {
        $this->useDynamicUpdate(true);
    }

    public function beforeValidationOnCreate()
    {
        if (property_exists($this, 'created'))
            $this->created = $this->created ? $this->created : TIMESTAMP;

        if (property_exists($this, 'updated'))
            $this->updated = TIMESTAMP;

        try {
            $request = $this->getDI()->getShared('request');
        } catch (\Exception $e) {
            $request = null;
        }

        if ($request) {
            if (property_exists($this, 'userAgent')) {
                $this->userAgent = $request->getUserAgent();
            }
            if (property_exists($this, 'ipAddr')) {
                $this->ipAddr = $request->getClientAddress();
            }
        }
    }

    public function beforeValidationOnUpdate()
    {
        if (property_exists($this, 'updated'))
            $this->updated = TIMESTAMP;
    }

    public function afterFetch()
    {
        $this->_isNew = false;
    }

    public function isNewRecord()
    {
        return $this->_isNew;
    }

    protected function filterGet($name, $filter)
    {
        if ( ! property_exists($this, $name))
            throw new \Exception('Property ' . $name . ' not exists');

        $value = $this->$name;
        switch ($filter) {
            case 'array':
                $value = empty($value) ? [] : preg_split('/[\\s,]+/', $value);
                break;

            case 'json':
                $value = empty($value) ? null : json_decode($value);
                break;
        }

        return $value;
    }

    protected function filterSet($name, $value, $filter)
    {
        if ( ! property_exists($this, $name))
            throw new \Exception('Property ' . $name . ' not exists');

        switch ($filter) {
            case 'array':
                $this->$name = implode(', ', (array) $value);
                break;

            case 'json':
                $this->$name = json_encode($value);
                break;
            
            default:
                $this->$name = $value;
                break;
        }
    }
}