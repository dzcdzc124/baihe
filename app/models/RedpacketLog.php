<?php

namespace App\Models;


class RedpacketLog extends ModelBase
{
    public $id;

    public $redpacketId;

    public $luckyLogId;

    public $openId;

    public $amount = 0;

    public $code;

    public $msg;

    public $sign;

    public $resultCode;

    public $errCode;

    public $errCodeDes;

    public $mchBillNo;

    public $mchId;

    public $sentAt;

    public $sentListId;

    public $ipAddr;

    public $created;

    public $updated;

    public function getBillNo()
    {
        return date('Ymd', $this->created) . $this->luckyLogId;
    }
}