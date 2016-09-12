<?php

namespace App\Models;


class AwardLog extends ModelBase
{
    public $id;

    public $openId;

    public $prizeId;

    public $ipAddr;

    public $userAgent;

    public $created;
}