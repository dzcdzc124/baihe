<?php

namespace App\Modules\Admin;

use Phalcon\DI;


class Menu
{
    private $_menu;

    private $_submenu = [];

    private $_current;

    public function __construct()
    {
        $menuFile = __DIR__ . '/../data/menu.json';
        if (file_exists($menuFile)) {
            $menuStr = file_get_contents($menuFile);
            $menu = json_decode($menuStr, true);
        } else {
            $menu = [];
        }

        $this->_menu = $menu;
    }

    public function tree()
    {
        if (isset($this->_menu))
            return $this->filterMenu($this->_menu);

        return [];
    }

    public function sub($menu = null)
    {
        if (is_null($menu))
            return $this->_submenu;

        $this->_submenu = (array) $menu;
    }

    public function setCurrent($nav)
    {
        if (empty($nav))
            $this->_current = null;

        $this->_current = explode('/', $nav);
    }

    public function current($index = 0, $point = 'index')
    {
        if (empty($this->_current))
            return (empty($point) || $point == 'index') ? true : false;

        $_curNav = empty($this->_current[$index]) ? 'index' : $this->_current[$index];
        return $_curNav == $point;
    }

    public function url($item)
    {
        if (empty($item) || empty($item['url']))
            return '#';

        $urls = (array) $item['url'];
        $url = array_shift($urls);
        $params = array_shift($urls);
        
        return DI::getDefault()->getShared('url')->get($url, $params);
    }

    private function filterMenu(array $menu)
    {
        $user = DI::getDefault()->getShared('auth')->user();

        foreach ($menu as $key => $item) {
            $access = isset($item['access']) ? $item['access'] : null;
            $hasAccess = true;

            if ( ! empty($access))
                $hasAccess = empty($user) ? false : $user->hasAccess($access);

            if ($hasAccess && isset($item['children'])) {
                $item['children'] = $this->filterMenu($item['children']);
                $menu[$key] = $item;
            } elseif ( ! $hasAccess) {
                unset($menu[$key]);
            }
        }

        return $menu;
    }
}