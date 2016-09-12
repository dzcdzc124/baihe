<?php

namespace App\Models;


class Imei extends ModelBase
{
    public $imei;

    public $openId;

    public $name;

    public $mobile;

    public $completed = 0;

    public $awarded = 0;

    public $district;

    public $districtName;

    public $ipAddr;

    public $userAgent;

    public $created;
}