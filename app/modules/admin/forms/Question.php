<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;

use App\Lib\Forms\Form;
use App\Lib\Forms\Element\Boolean;
use App\Models\Question as QuestionModel;


class Question extends Form
{
    public function initialize($entity, $userOptions)
    {
        $question = new Text('question');
        $question->addValidator(new PresenceOf([
            'message' => '请输入问题',
        ]));
        $this->add($question);

        $sort = new Numeric('sort');
        $sort->addValidator(new Digit([
            'message' => '题号必需为数字',
        ]));
        $this->add($sort);

        $reverse = new Boolean('reverse');
        $this->add($reverse);
    }
}