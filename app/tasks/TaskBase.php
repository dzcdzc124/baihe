<?php

namespace App\Tasks;

use Phalcon\CLI\Task;

use App\Helpers\System as SystemHelper;


class TaskBase extends Task
{
    protected $_data;

    protected function output($output)
    {
        if ( ! is_string($output))
            $output = var_export($output, true);

        echo $output . "\n";
        ob_flush();
    }

    protected function fetchParam($name = null)
    {
        if ( ! isset($this->_data))
            return null;

        if (is_null($name))
            return $this->_data;

        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    protected function initParams($params)
    {
        $dataId = @array_shift($params);
        $data = $dataId ? (array) SystemHelper::receiveData($dataId) : [];
        $this->_data = $data;

        return $this;
    }

    protected function log($msg)
    {
        if ($this->config->application->debug)
            file_put_contents(APP_PATH . 'cache/logs/task.log', var_export($msg, true)."\n", FILE_APPEND);
    }
}