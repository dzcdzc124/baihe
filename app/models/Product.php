<?php

namespace App\Models;


class Product extends ModelBase
{
    public $id;

    public $name;

    public $detail;

    public $total_fee;

    public $module;

    public static function findByModule($module)
    {
        $p = self::findFirst([
            'conditions' => 'module = :module:',
            'bind' => array(
                'module' => $module
            ),
        ]);
        return $p;
    }

    public static function findById($id)
    {
        $p = self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => array(
                'id' => $id
            ),
        ]);
        return $p;
    }

    
}