<?php

namespace App\Lib\Validator;

use Phalcon\Validation\Validator\Regex;

class Mobile extends Regex
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->setOption('pattern', '/^1(3|4|5|7|8)[\\d]{9}$/');
    }
}