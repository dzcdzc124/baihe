<?php

namespace App\Lib\Mvc;

use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Events\Manager as PhEventsManager;

class Dispatcher extends PhDispatcher
{
    public function __construct()
    {
        var_dump(get_class_methods(new parent));
        die();
        parent::__construct();

        $eventManager = new PhEventsManager();

        // $eventManager->attach('dispatch', function($event, $dispatcher, $exception){
        //     if($event->getType() == 'beforeNotFoundAction') {
        //         $dispatcher->forward(array(
        //             'controller' => 'error',
        //             'action' => 'notfound',
        //         ));
        //         return FALSE;
        //     }

        //     if($event->getType() == 'beforeException') {
        //         switch($exception->getCode()) {
        //             case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
        //             case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
        //                 $dispatcher->forward(array(
        //                     'controller' => 'error',
        //                     'action' => 'notfound',
        //                 ));
        //                 return FALSE;
        //         }
        //     }
        // });

        $this->setEventsManager($eventManager);
    }

    public function getCurrentURI()
    {
        $di = $this->getDI();
        $request = $di->getShared('request');
        $httpHost = $request->getHttpHost();
        $uri = $request->getURI();

        return ($request->isSecureRequest() ? 'https://' : 'http://') . $httpHost . $uri;
    }
}