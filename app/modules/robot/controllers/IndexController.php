<?php

namespace App\Modules\Robot\Controllers;

use App\Helpers\Activity as ActivityHelper;
use App\Helpers\System as SystemHelper;
use App\Helpers\Wechat as WechatHelper;
use App\Handlers\HandlerBase;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $client = WechatHelper::corpClient();

        if ($this->request->isPost()) {
            $client->input();
            // $this->log($client->getRevXML());

            $userId = $client->getRevFromUserName();
            $session =& WechatHelper::session($userId);

            $handlersDir = APP_PATH . 'core/Handlers/';
            $handlers = (array) $this->config->handlers;

            foreach ($handlers as $name) {
                $handlerName = ucfirst($name);
                $file = $handlersDir . $handlerName . '.php';
                if (is_file($file)) {
                    include_once($file);
                    $class = 'App\\Handlers\\' . $handlerName;
                    try {
                        $handler = new $class($client, $session);
                        $result = $handler->run();
                        if ($result && is_string($result))
                            $this->output($result);
                    } catch (App\Hanlders\Stop $e) {
                        break;
                    } catch (App\Hanlders\Next $e) {
                        continue;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            $this->output('success');
        } else {
            if ($client->validate($echoStr)) {
                $this->output($echoStr);
            }

            $this->output('Access Denied.');
        }
    }
}