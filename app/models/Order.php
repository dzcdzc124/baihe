<?php

namespace App\Models;


class Order extends ModelBase
{
    public $id;

    public $user_id;

    public $product_id;

    public $order_id;

    public $prepay_id = '';

    public $transaction_id = '';

    public $total_fee = 0;

    public $status = 0;

    public $data;

    public $expire_at;
    
    public $created;

    public $updated;

    public static function findByUserId($user_id)
    {
        $orders = self::find([
            'conditions' => 'user_id = :user_id:',
            'bind' => array(
                'user_id' => $user_id
            ),
        ]);
        return $orders;
    }

    public static function findByOrderId($order_id)
    {
        $order = self::find([
            'conditions' => 'order_id = :order_id:',
            'bind' => array(
                'order_id' => $order_id
            ),
        ]);
        return $order;
    }

     public static function findByNewestOrderByUserId($user_id)
    {
        $order = self::find([
            'conditions' => 'user_id = :user_id:',
            'order' => 'created desc',
            'limit' => 1,
            'bind' => array(
                'user_id' => $user_id
            ),
        ]);
        return $order;
    }

    public static function createOrderId(){
        return date("YmdHis")."_".uniqid();
    }
}