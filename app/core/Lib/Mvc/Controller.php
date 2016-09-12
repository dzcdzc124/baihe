<?php

namespace App\Lib\Mvc;

use Phalcon\Mvc\Controller as PhController;
use Phalcon\Mvc\Dispatcher as PhDispatcher;

class Controller extends PhController
{
    protected $uniqueId;

    public function beforeExecuteRoute(PhDispatcher $dispatcher)
    {
        return true;
    }

    public function serveJson()
    {
        $args = func_get_args();
        $data = ['errmsg' => null, 'errcode' => -1];

        switch (func_num_args()) {
            case 1:
                if (is_string($args[0])) {
                    $data['errmsg'] = $args[0];
                } elseif (is_int($args[0])) {
                    $data['errcode'] = $args[0];
                } elseif (is_array($args[0])) {
                    $data = array_merge($data, $args[0]);
                }
                break;

            case 2:
                if (is_string($args[0]) && is_int($args[1])) {
                    $data['errmsg'] = $args[0];
                    $data['errcode'] = $args[1];
                } elseif (is_int($args[0]) && is_array($args[1])) {
                    $data['errcode'] = $args[0];
                    $data = array_merge($data, $args[1]);
                } elseif (is_string($args[0]) && is_array($args[1])) {
                    $data['errmsg'] = $args[0];
                    $data = array_merge($data, $args[1]);
                }
                break;

            case 3:
                $data['errmsg'] = $args[0];
                $data['errcode'] = $args[1];
                $data = array_merge($data, (array) $args[2]);
                break;
            
            default:
                $data = null;
                break;
        }

        $this->response->setContent(json_encode($data));
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Cache-Control', 'no-cache, must-revalidate');
        $this->response->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $this->response->send();
        exitmsg();
    }

    public function output($content)
    {
        $this->response->setContent($content);
        $this->response->send();
        exitmsg();
    }

    public function redirect($url)
    {
        $this->response->redirect($url);
        $this->response->send();
        exitmsg();
    }
}