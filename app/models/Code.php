<?php

namespace App\Models;


class Code extends ModelBase
{
    public $id;

    public $code;

    public $module;

    public $given = 0;

    public $status = 0;

    public $user_id;

    public $created;

    public $updated;

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

    public static function updateData(array $givens)
    {
        $obj = new self;
        $connection = $obj->getWriteConnection();
        $tableName = $obj->getSource();
        foreach ($givens as $id => $given ) {
            $id = intval($id);
            if ($id > 0) {
                $given = intval($given);
                $sql = "UPDATE {$tableName} SET  `given` = '{$given}' WHERE `id` = \"{$id}\"";
                $connection->execute($sql);
            }
        }
    }
}