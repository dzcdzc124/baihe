<?php

namespace App\Models;


class LoginAttempt extends ModelBase
{
    public $id;

    public $adminId;

    public $login;

    public $password;

    public $ipAddr;

    public $userAgent;

    public $created;
}