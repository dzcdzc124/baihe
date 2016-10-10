<?php

namespace App\Models;


class Prize extends ModelBase
{
    public $id;

    public $openId;

    public $nickname;

    public $avatar;

    public $sex;

    public $country;

    public $province;

    public $city;

    public $accessToken;

    public $tokenExpires;

    public $refreshToken;

    public $unionId;

    public $data;

    public $created;

    public $updated;

    public static function findByOpenId($openId)
    {
        $user = self::findFirst([
            'conditions' => 'openId = :openId:',
            'bind' => array(
                'openId' => $openId
            ),
        ]);
        return $user;
    }
}