<?php

namespace App\Models;


class LuckyLog extends ModelBase
{
    public $id;

    public $openId;

    public $imei;

    public $prizeId;

    public $prizeType = 0;

    public $name;

    public $mobile;

    public $address;

    public $idcard;

    public $exchanged = 0;

    public $cancelled = 0;

    public $ipAddr;

    public $userAgent;

    public $created;

    public $updated;

    public function initialize()
    {
        parent::initialize();

        $this->belongsTo('prizeId', 'App\\Models\\Prize', 'id', [
            'alias' => 'Prize',
        ]);
    }
}