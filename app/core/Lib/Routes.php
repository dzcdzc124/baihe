<?php

namespace App\Lib;

use Phalcon\DI;
use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    protected $module = NULL;

    public $prefix = NULL;

    public $initDefault = TRUE;

    public function __construct($module = NULL, $paths = NULL)
    {
        $this->module = $module;
        parent::__construct($paths);
    }

    public function initialize()
    {
        $module = $this->module;

        if (empty($module)) {
            $className = get_class($this);
            $tmpArr = explode('\\', $className);

            try {
                $module = strtolower($tmpArr[2]);
            } catch (Exception $e) {
                throw $e;
            }
        }

        $di = DI::getDefault();
        $config = $di->getShared('config');

        if ( ! empty($module)) {
            $this->setPaths([
                'module' => $module,
            ]);
        }

        if ( ! empty($this->prefix)) {
            $this->setPrefix($this->prefix);
        } elseif ( ! empty($module)) {
            if ($module != $config->application->defaultModule) {
                $this->setPrefix('/' . $module);
            }
        }

        if ($this->initDefault) {
            $this->_initDefault();
        }
    }

    protected function _initDefault()
    {
        $this->add('/:controller', [
            'controller' => 1,
        ]);

        $this->add('/:controller/:action', [
            'controller' => 1,
            'action' => 2,
        ]);
    }
}