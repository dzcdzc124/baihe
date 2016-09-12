<?php

namespace App\Models;


class VirtualCard extends ModelBase
{
    public $id;

    public $luckyLogId;

    public $data1;

    public $data2;

    public $data3;

    public $data4;

    public $data5;

    public $created;

    public $updated;

    public static function fetchOne($luckyLogId)
    {
        $luckyLogId = intval($luckyLogId);
        $model = self::findFirstByLuckyLogId($luckyLogId);
        if (empty($model)) {
            $obj = new self;
            $connection = $obj->getWriteConnection();
            $tableName = $obj->getSource();

            $sql = "UPDATE {$tableName} SET `luckyLogId` = {$luckyLogId} WHERE `luckyLogId` = 0 ORDER BY RAND() LIMIT 1";
            $connection->execute($sql);

            $model = self::findFirstByLuckyLogId($luckyLogId);
        }

        return $model;
    }
}