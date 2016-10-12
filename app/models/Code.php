<?php

namespace App\Models;


class Code extends ModelBase
{
    public $id;

    public $code;

    public $module;

    public $status = 0;

    public $user_id;

    public $created;

    public static function findByCode($code)
    {
        $c = self::findFirst([
            'conditions' => 'code = :code:',
            'bind' => array(
                'code' => $code
            ),
        ]);
        return $c;
    }

    public static function createCode(){
        return md5(uniqid(mt_rand()));
    }
}