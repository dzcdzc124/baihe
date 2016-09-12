<?php

namespace App\Lib\Mvc;

use Phalcon\Mvc\View as PhView;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Phalcon\Mvc\View\Engine\Volt\Compiler as PhVoltCompiler;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DI;


class Volt extends PhVolt
{
    public function getCompiler()
    {
        if (empty($this->_compiler)) {
            $GLOBALS['volt'] = $this;
            $this->_compiler = new VoltCompiler($this->getView());
            $this->_compiler->setOptions($this->getOptions());
            $this->_compiler->setDI($this->getDI());
        }

        return $this->_compiler;
    }
}

class VoltCompiler extends PhVoltCompiler
{
    protected function _compileSource($source, $something = null)
    {
        $compiled = parent::_compileSource($source, $something);

        if (is_array($compiled)) {
            foreach ($compiled as &$entry) {
                $entry = $this->replaceThis($entry);
            }
        } else {
            $compiled = $this->replaceThis($compiled);
        }

        return $compiled;
    }

    protected function replaceThis($source)
    {
        if (is_array($source)) {
            return $source;
        }

        $source = str_replace('$this', '$GLOBALS[\'volt\']', $source);
        return $source;
    }
}

class PhpFunctionExtension
{
    /**
     * This method is called on any attempt to compile a function call
     */
    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . '('. $arguments . ')';
        }
    }
}

class View extends PhView
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->registerEngines(array(
            '.volt' => function ($this) {
                $di = DI::getDefault();
                $config = $di->getShared('config');
                $volt = new Volt($this, $di);
                $volt->setOptions([
                    'compiledPath'      => $config->application->volt->path,
                    'compiledExtension' => $config->application->volt->extension,
                    'compiledSeparator' => $config->application->volt->separator,
                    'stat'              => $config->application->volt->stat,
                    'compileAlways'     => $config->application->debug,
                ]);

                $compiler = $volt->getCompiler();

                $compiler->addFunction('utf8_substr', function ($resolvedArgs, $exprArgs) {
                    return 'App\Helpers\String::truncateUtf8String(' . $resolvedArgs . ')';
                })->addFunction('static_url', function ($resolvedArgs, $exprArgs) {
                    return 'App\Helpers\Url::staticUrl(' . $resolvedArgs . ')';
                })->addFunction('uploaded_url', function ($resolvedArgs, $exprArgs) {
                    return 'App\Helpers\Upload::url(' . $resolvedArgs . ')';
                })->addFunction('format_money', function ($resolvedArgs, $exprArgs) {
                    return 'App\Helpers\String::formatMoney(' . $resolvedArgs . ')';
                })->addFunction('html_a', function ($resolvedArgs, $exprArgs) {
                    return 'App\Helpers\String::html_a(' . $resolvedArgs . ')';
                });

                $compiler->addFilter('int', function ($resolvedArgs, $exprArgs) {
                    return 'intval(' . $resolvedArgs . ')';
                })->addFilter('hash', 'md5')->addFilter('capitalize', 'lcfirst');

                $compiler->addExtension(new PhpFunctionExtension());

                return $volt;
            }
        ));

        $di = $this->getDI();
        $this->setRenderLevel(self::LEVEL_ACTION_VIEW);
        $this->setVars([
            'setting' => $di->getShared('setting'),
            'isAjaxLoad' => $di->getShared('request')->isAjaxLoad(),
        ]);

        $eventsManager = new EventsManager();
        $eventsManager->attach('view:afterRender', function ($event, $view) use ($di) {
            $request = $di->getShared('request');
            if ($request->isAjaxLoad()) {
                $content = $view->getContent();
                if (preg_match('/<!\-\-\s*AJAX_CONTENT_START\s*\-\->(.*?)<!\-\-\s*AJAX_CONTENT_END\s*\-\->/sm', $content, $matches)) {
                    $content = $matches[1];
                    $view->setContent($content);
                }
            }
        });

        $this->setEventsManager($eventsManager);
    }
}