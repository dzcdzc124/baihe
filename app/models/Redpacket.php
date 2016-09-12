<?php

namespace App\Models;


class Redpacket extends ModelBase
{
    public $id;

    public $minimum = 0;

    public $maximum = 0;

    public $amount = 0;

    public $used = 0;

    public $total = 0;

    public $weight = 0;

    public $sort = 0;

    public $created;

    public $updated;

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

    public static function getOne($redpacketId)
    {
        $obj = new self;
        $connection = $obj->getWriteConnection();
        $tableName = $obj->getSource();
        $redpacketId = intval($redpacketId);
        $sql = "UPDATE {$tableName} SET `used` = IF(`used` < `total`, `used` + 1, `used`) WHERE `id` = \"{$redpacketId}\"";
        $connection->execute($sql);
        return $connection->affectedRows() == 1;
    }
}