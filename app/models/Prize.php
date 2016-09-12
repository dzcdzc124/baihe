<?php

namespace App\Models;


class Prize extends ModelBase
{
    public $id;

    public $name;

    public $type = 0;

    public $used = 0;

    public $total = 0;

    public $weight = 0;

    public $sort = 0;

    public $message;

    public $default = 0;

    public $created;

    public $updated;

    public static $types = [
        0 => '不中奖',
        1 => '实物奖',
        2 => '红包',
        3 => '虚拟卡',
    ];

    public static function updateData(array $totals, array $weights)
    {
        $obj = new self;
        $connection = $obj->getWriteConnection();
        $tableName = $obj->getSource();
        foreach ($totals as $id => $total) {
            $id = intval($id);
            if ($id > 0) {
                $total = intval($total);
                $weight = isset($weights[$id]) ? intval($weights[$id]) : 0;
                $sql = "UPDATE {$tableName} SET `total` = {$total}, `weight` = {$weight} WHERE `id` = \"{$id}\"";
                $connection->execute($sql);
            }
        }
    }

    public static function getOne($prizeId)
    {
        $obj = new self;
        $connection = $obj->getWriteConnection();
        $tableName = $obj->getSource();
        $prizeId = intval($prizeId);
        $sql = "UPDATE {$tableName} SET `used` = IF(`used` < `total`, `used` + 1, `used`) WHERE `id` = \"{$prizeId}\"";
        $connection->execute($sql);
        return $connection->affectedRows() == 1;
    }

    public function getTypeLabel()
    {
        return array_element(self::$types, $this->type);
    }
}