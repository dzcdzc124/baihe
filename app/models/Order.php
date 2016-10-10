<?php

namespace App\Models;


class Order extends ModelBase
{
    public $id;

    public $user_id;

    public $order_id;

    public $prepay_id = '';

    public $module = '';

    public $total_fee = 0;

    public $created;

    public $updated;
}