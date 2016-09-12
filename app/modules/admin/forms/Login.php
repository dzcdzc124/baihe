<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

use App\Lib\Forms\Form;
use App\Lib\Forms\Element\Boolean;

class Login extends Form
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

        $rememberMe = new Boolean('rememberMe');
        $this->add($rememberMe);
    }
}