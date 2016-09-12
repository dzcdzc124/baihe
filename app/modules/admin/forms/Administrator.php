<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

use App\Lib\Forms\Form;
use App\Lib\Forms\Element\Boolean;
use App\Lib\Validator\Uniqueness;


class Administrator extends Form
{
    public function initialize($entity, $userOptions)
    {
        if ($entity->isNewRecord()) {
            $username = new Text('username');
            $username->addValidator(new PresenceOf([
                'message' => '用户名不能为空',
            ]))->addValidator(new Uniqueness([
                'message' => '该用户名已存在',
                'model' => 'App\\Models\\Administrator',
            ]));
            $this->add($username);
        }

        $newPassword = new Password('newPassword');
        if ($entity->isNewRecord()) {
            $newPassword->addValidator(new PresenceOf([
                'message' => '密码不能为空',
            ]));
        }
        $this->add($newPassword);

        $email = new Text('email');
        $email->addValidator(new Uniqueness([
            'allowEmpty' => true,
            'message' => '该Email已绑定其他用户',
            'model' => 'App\\Models\\Administrator',
            'filter' => function($record) use ($entity) {
                if ($entity->isNewRecord())
                    return false;

                return $record->id == $entity->id;
            },
        ]));
        $this->add($email);

        $mobile = new Text('mobile');
        $mobile->addValidator(new Uniqueness([
            'allowEmpty' => true,
            'message' => '该手机号码已绑定其他用户',
            'model' => 'App\\Models\\Administrator',
            'filter' => function($record) use ($entity) {
                if ($entity->isNewRecord())
                    return false;

                return $record->id == $entity->id;
            },
        ]));
        $this->add($mobile);

        $auth = $this->getDI()->getShared('auth');
        if ($entity->isNewRecord() || ($auth->id() != $entity->id)) {
            $sa = new Boolean('sa');
            $this->add($sa);

            $activated = new Boolean('activated');
            $this->add($activated);
        }
    }
}