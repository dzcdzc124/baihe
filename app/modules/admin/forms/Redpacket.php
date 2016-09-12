<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Digit;

use App\Lib\Forms\Form;


class Redpacket extends Form
{
    public function initialize($entity, $userOptions)
    {
        $minimum = new Numeric('minimum');
        $minimum->addValidator(new Between([
            'minimum' => 100,
            'maximum' => 20000,
            'message' => '金额范围必需在100 ~ 20000之间',
        ]))->addFilter('int!');
        $this->add($minimum);

        $maximum = new Numeric('maximum');
        $maximum->addValidator(new Between([
            'minimum' => 100,
            'maximum' => 20000,
            'message' => '金额范围必需在100 ~ 20000之间',
        ]))->addFilter('int!');
        $this->add($maximum);

        $total = new Numeric('total');
        $total->addValidator(new Digit([
            'message' => '数量必需为数字',
        ]));
        $this->add($total);

        $weight = new Numeric('weight');
        $weight->addValidator(new Digit([
            'message' => '中奖权重必需为数字',
        ]));
        $this->add($weight);

        $sort = new Numeric('sort');
        $sort->addValidator(new Digit([
            'message' => '排序必需为数字',
        ]));
        $this->add($sort);
    }
}