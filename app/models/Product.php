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

    public static function updateData(array $names, array $details, array $total_fees)
    {
        $obj = new self;
        $connection = $obj->getWriteConnection();
        $tableName = $obj->getSource();
        foreach ($names as $id => $name) {
            $id = intval($id);
            if ($id > 0) {
                $name = $name;
                $detail = $details[$id];
                $total_fee = (int) $total_fees[$id];
                $sql = "UPDATE {$tableName} SET  `total_fee` = {$total_fee}, `name` = '{$name}', `detail` = '{$detail}' WHERE `id` = \"{$id}\"";
                $connection->execute($sql);
            }
        }
    }
}