<?php

namespace App\Models;


class Question extends ModelBase
{
    public $id;

    public $question;

    public $reverse = 0;

    public $sort = 0;

    public $module = '';

    public static function updateData(array $questions, array $sorts, array $reverses)
    {
        $obj = new self;
        $connection = $obj->getWriteConnection();
        $tableName = $obj->getSource();
        foreach ($sorts as $id => $sort) {
            $id = intval($id);
            if ($id > 0) {
                $sort = intval($sort);
                $reverse = isset($reverses[$id]) ? intval($reverses[$id]) : 0;
                $question = $questions[$id];
                $sql = "UPDATE {$tableName} SET  `question` = '{$question}', `sort` = {$sort}, `reverse` = {$reverse} WHERE `id` = \"{$id}\"";
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