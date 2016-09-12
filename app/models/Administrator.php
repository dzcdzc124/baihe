<?php

namespace App\Models;


class Administrator extends ModelBase
{
    public $id;

    public $username;

    public $avatar;

    protected $password;

    public $email;

    public $mobile;

    public $sa = 0;

    public $activated = 1;

    public $created;

    public $updated;

    public function getPassword()
    {
        return '';
    }

    public function setPassword($password)
    {
        if (empty($password))
            return false;
        
        $uniqueId = uniqid();
        $salt = substr($uniqueId, 0, 6);
        $this->password = $this->cryptPassword($password, $salt);
    }
    
    public function validatePassword($password)
    {
        if (strpos($this->password, '$') === false) {
            return false;
        }

        $tmpArr = explode('$', $this->password);
        $salt = $tmpArr[0];

        return $this->password == $this->cryptPassword($password, $salt);
    }
    
    public function cryptPassword($password, $salt)
    {
        $encrypted = md5($password . $salt);
        $encrypted = sha1($encrypted . $password);
        return $salt . '$' . $encrypted;
    }

    public function hasAccess($access)
    {
        if ($access == 'sa' && ! $this->sa)
            return false;

        return true;
    }
}
