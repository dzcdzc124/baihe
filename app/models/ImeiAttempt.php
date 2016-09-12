<?php

namespace App\Models;


class ImeiAttempt extends ModelBase
{
    public $id;

    public $openId;

    public $imei;

    public $ipAddr;

    public $userAgent;

    public $created;
}