<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;

use App\Lib\Forms\Form;

class Register extends Form
{
    public function initialize($entity, $userOptions)
    {
        $username = new Text('username');
        $username->addValidator(new PresenceOf([
            'message' => '请输入你的用户名',
        ]));
        $this->add($username);

        $password = new Password('password');
        $password->addValidator(new PresenceOf([
            'message' => '请输入密码',
        ]));
        $this->add($password);

        $passwordAgain = new Password('passwordAgain');
        $passwordAgain->addValidator(new Confirmation([
            'message' => '两次输入的密码不一致',
            'with' => 'password',
        ]));
        $this->add($passwordAgain);
    }
}