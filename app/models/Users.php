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


}