<?php

namespace App\Models;


class District extends ModelBase
{
    public $id;

    public $name;

    public $created;

    public static function saveAll($data)
    {
        $data = (array) $data;

        foreach ($data as $item) {
            $id = trim(array_element($item, 'id'));
            $name = trim(array_element($item, 'name', ''));

            if ($name) {
                $model = $id ? self::findFirstById($id) : null;
                if (empty($model)) {
                    $model = new self;
                    $model->id = uniqid();
                }

                $model->name = $name;
                @$model->save();
            }
        }
    }
}