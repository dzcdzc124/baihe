<?php

namespace App\Models;


class JsapiTicket extends ModelBase
{
    public $module;

    public $value;

    public $expire_at;

    public $created;

    public $updated;

    public static function findByModule($module)
    {
        $ticket = self::findFirst([
            'conditions' => 'module = :module:',
            'bind' => array(
                'module' => $module
            ),
        ]);
        return $ticket;
    }
}