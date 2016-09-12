<?php

namespace App\Lib;

use Phalcon\DI;
use Phalcon\Mvc\User\Component;

use App\Models\Administrator;
use App\Models\LoginAttempt;

class Auth extends Component
{
    private $userId;

    private $user;

    public function __construct()
    {
        $config = $this->getDI()->getShared('config');
        $this->saveName = 'identity-' . $config->appName;

        $this->tryAutoLogin();
    }

    public function id()
    {
        return $this->userId;
    }

    public function user()
    {
        return $this->user;
    }
    
    public function check(\stdClass $credentials, $attemptLimit = 5)
    {
        $user = null;
        $login = $credentials->username;
        if (preg_match('#^\d{11}$#', $login)) {
            $user = Administrator::findFirstByMobile($login);
        } elseif (preg_match('#^.+@.+\..+$#', $login)) {
            $user = Administrator::findFirstByEmail($login);
        }

        if (empty($user))
            $user = Administrator::findFirstByUsername($login);

        if (empty($user))
            throw new Exception("用户不存在", 1001);
        elseif (empty($user->activated))
            throw new Exception("该用户已被禁用！", 1001);

        if ($attemptLimit > 0) {
            $conditions = '';
            $bind = [];

            if ($user) {
                $conditions = 'adminId = :adminId:';
                $bind['adminId'] = $user->id;
            } else {
                $conditions = 'login = :login:';
                $bind['login'] = $credentials->username;
            }

            $conditions .= ' AND created >= :created:';
            $bind['created'] = TIMESTAMP - 3600;

            $attemptTotal = LoginAttempt::count([
                'conditions' => $conditions,
                'bind' => $bind,
            ]);

            if ($attemptTotal >= $attemptLimit)
                throw new Exception("输入错误次数过多，请稍后再试", 1002);
        }

        if ($user && $user->validatePassword($credentials->password)) {
            $this->login($user, (bool) $credentials->rememberMe);
        } else {
            $loginAttempt = new LoginAttempt;
            $loginAttempt->assign([
                'adminId' => $user ? $user->id : 0,
                'login' => $credentials->username,
                'password' => $credentials->password,
            ]);
            $loginAttempt->save();

            throw new Exception("用户名不存在或密码不匹配", 1001);
        }

        return $user;
    }

    public function login($user = null, $remember = true) 
    {
        if ($user instanceof Administrator) {
            $identity = $user->id;

            $this->userId = $user->id;
            $this->user = $user;
        } else {
            $identity = null;
        }

        $this->session->set($this->saveName, $identity);
        if ($remember)
            $this->createAutoLogin($user);

        return true;
    }

    public function logout()
    {
        $this->session->destroy();
        $this->cookies->set($this->saveName, null);
    }

    public function isGuest()
    {
        return empty($this->userId);
    }

    public function isLogined()
    {
        return ! $this->isGuest();
    }

    private function tryAutoLogin()
    {
        $userId = $this->loadFromSession();
        if (empty($userId))
            $userId = $this->loadFromCookie();

        if ($userId) {
            $this->userId = $userId;
            $this->user = Administrator::findFirstById($userId);
        }
    }

    private function loadFromSession()
    {
        return $this->session->get($this->saveName);
    }

    private function loadFromCookie()
    {
        $cookieUserId = $this->cookies->get($this->saveName);
        $userId = $cookieUserId ? $cookieUserId->getValue() : null;
        $userId = $userId ? $this->crypt->decrypt($userId) : null;
        $userId = trim($userId);

        return $userId;
    }

    private function createAutoLogin($user)
    {
        $userId = $user->id;
        if ($userId) {
            $userId = $this->crypt->encrypt($userId);
            $this->cookies->set($this->saveName, $userId, TIMESTAMP + 86400 * 30);
        }
    }
}