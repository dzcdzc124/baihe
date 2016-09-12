<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;

use App\Lib\Forms\Form;
use App\Lib\Forms\Element\Boolean;
use App\Models\Prize as PrizeModel;


class Prize extends Form
{
    public function initialize($entity, $userOptions)
    {
        $name = new Text('name');
        $name->addValidator(new PresenceOf([
            'message' => '请输入奖品名称',
        ]));
        $this->add($name);

        $type = new Select('type', PrizeModel::$types);
        $this->add($type);

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

        $message = new Text('message');
        $this->add($message);

        $default = new Boolean('default');
        $this->add($default);
    }
}