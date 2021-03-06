<?php

namespace App\Helpers;

use App\Wechat\Wechat as Client;
use App\Models\User;
use App\Models\JsapiTicket as Ticket;


class Wechat extends HelperBase
{
    private static $client;

    public static function &client()
    {
        if ( ! isset(self::$client)) {
            $config = self::getShared('config');
            $wxConfig = $config->wechat;
            $cache = self::getShared('cache');
            $appId = $wxConfig->appId;

            self::$client = new Client([
                'appId' => $appId,
                'appSecret' => $wxConfig->appSecret,
                'token' => $wxConfig->token,
                'encodingAESKey' => $wxConfig->encodingAESKey,

                'mchId' => $wxConfig->mchId,
                'mchKey' => $wxConfig->mchKey,
                'certPath' => $wxConfig->certPath,
                'keyPath' => $wxConfig->keyPath,

                'storage' => new Storage($cache, $appId),
            ]);
        }

        return self::$client;
    }

    public static function loginRequired($scope = 'snsapi_base')
    {
        $sessionName = $cookieName = 'current-auth-openId';

        $session = self::getShared('session');
        $cookies = self::getShared('cookies');
        $crypt = self::getShared('crypt');
        $dispatcher = self::getShared('dispatcher');
        $request = self::getShared('request');

        $openId = $session->get($sessionName);
        if (empty($openId)) {
            $cookieAuth = $cookies->get($cookieName);
            $cryptToken = $cookieAuth ? $cookieAuth->getValue() : null;
            try {
                $token = $cryptToken ? $crypt->decrypt($cryptToken) : null;
                $openId = self::checkToken($token);
            } catch (\Exception $e) {
                $openId = null;
            }
        }else{
            return self::login($openId);
        }

        if (empty($openId)) {
            $currentUrl = $dispatcher->getCurrentURI();
            $currentUrl = preg_replace("/:\d+/", "", $currentUrl);
            
            //重定向回来后带code参数
            $code = $request->getQuery('code');
            if (empty($code)) {
                $redirectUrl = self::parseRedirectUrl($currentUrl);
                $authUrl = self::client()->oAuth->getAuthorizeURL($redirectUrl, 'code', $scope, $scope);

                Url::redirect($authUrl);
            } else {
                try {
                    $accessToken = self::client()->oAuth->getAccessToken($code);
                    $openId = $accessToken['openid'];
                } catch (\Exception $e) {
                    Url::redirect($currentUrl);
                }
                
                $session->set($sessionName, $openId);
                //$cookies->set($cookieName, $openId, TIMESTAMP + 86400 * 180);
                $cookies->set($cookieName, $crypt->encrypt(self::authToken($openId)), TIMESTAMP + 86400 * 180);

                $state = $request->getQuery('state');
                $userinfo = [];
                
                if($state == "snsapi_userinfo"){
                    $userinfo = self::client()->oAuth->getUserInfo();
                }
                
                return self::login($openId, $userinfo, $accessToken);
            }
        }else{
            return self::login($openId);
        }

        return NULL;
    }

    public static function login($openId, $userInfo = [], $accessToken = []){
        $user = User::findByOpenId($openId);
        if (empty($user) || empty($user->id)) {
            $user = new User;
            $user->openId = $openId;
            $user->created = TIMESTAMP;
            $user->updated = TIMESTAMP;
        }

        if( isset($userInfo['nickname'])){
            $user->nickname = $userInfo['nickname'];
        }

        if( isset($userInfo['headimgurl'])){
            $user->avatar = $userInfo['headimgurl'];
        }

        if( isset($userInfo['country'])){
            $user->country = $userInfo['country'];
        }

        if( isset($userInfo['province'])){
            $user->province = $userInfo['province'];
        }

        if( isset($userInfo['city'])){
            $user->city = $userInfo['city'];
        }

        if( isset($userInfo['sex'])){
            $user->sex = $userInfo['sex'];
            $user->data = json_encode($userInfo);
        }

        if( isset($userInfo['unionId'])){
            $user->unionId = $userInfo['unionId'];
        }

        if( isset($accessToken['access_token'])){
            $user->accessToken = $accessToken['access_token'];
        }
        if( isset($accessToken['refresh_token'])){
            $user->refreshToken = $accessToken['refresh_token'];
        }
        if( isset($accessToken['expires_in'])){
            $user->tokenExpires = TIMESTAMP + (int)$accessToken['expires_in'];
        }

        $user->save();

        return $user;
    }

    public static function sign(){
        $dispatcher = self::getShared('dispatcher');

        $ticket = self::ticket();
        $timestamp = TIMESTAMP;
        $nonceStr = uniqid().uniqid();

        $currentUrl = $dispatcher->getCurrentURI();
        $currentUrl = preg_replace("/:\d+/", "", $currentUrl);

        $sortStr = "jsapi_ticket={$ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$currentUrl}";
        $signature = sha1($sortStr);

        return array(
            'nonceStr' => $nonceStr,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'appId' => self::getShared('config')->wechat->appId
        );


    }

    public static function ticket(){
        $ticket = Ticket::findByModule('pdq');
        if(empty($ticket)){
            $ticket = new Ticket;
            $ticket->created = TIMESTAMP;
            $ticket->updated = TIMESTAMP;
            $ticket->module = 'pdq';
        }

        if ( ($ticket->expire_at <= TIMESTAMP) ) {
            $res = self::client()->oAuth->getApiTicket();
            if($res){
                $ticket->value = $res['ticket'];
                $ticket->expire_at = TIMESTAMP + (int)$res['expires_in'];
            }
            $ticket->save();
        }

        return $ticket->value ? $ticket->value : NULL;
    }


    public static function parseRedirectUrl($url, $param=[])
    {
        $parts = parse_url($url);
        $host = $parts['host'];
        $path = $parts['path'];
        $query = $parts['query'];

        if(!empty($param)){
            $url .= "?".http_build_query($param);
        }

        return $url;
    }

    public static function authToken($openId)
    {
        $config = self::getShared('config');
        $encryptKey = $config->application->cookies->cryptKey;

        $token = $openId . '$$$' . sha1($encryptKey . $openId . $encryptKey);
        return $token;
    }

    public static function checkToken($token)
    {
        $token = trim($token);
        if (empty($token))
            return null;

        if (strpos($token, '$$$') === false)
            return null;

        $tmpData = explode('$$$', $token);
        if (count($tmpData) != 2)
            return null;

        if ($token == self::authToken($tmpData[0]))
            return trim($tmpData[0]);

        return null;
    }
}


class Menu
{
    private static $types = [
        'click' => 'key',
        'view' => 'url',
        'scancode_push' => 'key',
        'scancode_waitmsg' => 'key',
        'pic_sysphoto' => 'key',
        'pic_photo_or_album' => 'key',
        'pic_weixin' => 'key',
        'location_select' => 'key',
        'media_id' => 'media_id',
        'view_limited' => 'media_id',
    ];

    private $menuStr;

    public function __construct($menuStr)
    {
        $this->menuStr = $menuStr;
    }

    public function parse()
    {
        $menus = [];

        if (empty($this->menuStr))
            return $menus;

        $lines = explode("\n", $this->menuStr);
        $menu = null;
        foreach ($lines as $line) {
            $line = trim($line);
            if (substr($line, 0, 1) == '#') {
                continue;
            } elseif (substr($line, 0, 1) == '-') {
                if (empty($menu)) {
                    continue;
                } else {
                    $menuItem = $this->parseItem(substr($line, 1));
                    if ($menuItem) {
                        if ( ! isset($menu['sub_button']))
                            $menu['sub_button'] = [];
                        $menu['sub_button'][] = $menuItem;
                    }
                }
            } else {
                if ( ! empty($menu))
                    $menus[] = $menu;

                $menu = $this->parseItem($line);
            }
        }

        if ( ! empty($menu))
            $menus[] = $menu;

        return $menus;
    }

    private function parseItem($text)
    {
        $text = trim($text, "- \t\n\r\0\x0B");
        if (empty($text))
            return null;

        $tempArgs = preg_split('/[\\s,]+/', $text);
        $menu = ['name' => $tempArgs[0]];
        if (isset($tempArgs[1]))
            $value = $tempArgs[1];
        else
            $value = $tempArgs[0];

        if (isset($tempArgs[2])) {
            $menuType = $tempArgs[2];
        } else {
            if (substr($value, 0, 7) == 'http://' || substr($value, 0, 8) == 'https://')
                $menuType = 'view';
            else
                $menuType = 'click';
        }

        if ( ! array_key_exists($menuType, self::$types))
            $menuType = 'click';

        $menu['type'] = $menuType;
        $menu[self::$types[$menuType]] = $value;
        return $menu;
    }
}

class Session implements \ArrayAccess
{
    private $cache;
    private $cacheKey;
    private $data = [];
    private $ttl = 0;
    private $modified = false;

    public function __construct($cache, $id, $ttl = 600)
    {
        $this->cache = $cache;
        $this->cacheKey = 'wx.session.'.md5($id);
        $this->ttl = $ttl;

        $this->load();
    }

    public function __destruct()
    {
        $this->save();
    }

    public function load()
    {
        if ($this->cache->exists($this->cacheKey)) {
            $this->data = (array) $this->cache->get($this->cacheKey);
        }
    }

    public function save()
    {
        if ($this->modified) {
            $this->cache->save($this->cacheKey, $this->data, $this->ttl);
        }
    }

    public function get($index, $default = null)
    {
        return $this->has($index) ? $this->data[$index] : $default;
    }

    public function set($index, $value)
    {
        $this->data[$index] = $value;
        $this->modified = true;
    }

    public function has($index)
    {
        return isset($this->data[$index]);
    }

    public function remove($index)
    {
        if ($this->has($index))
            unset($this->data[$index]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function __get($index)
    {
        return $this->get($index);
    }

    public function __set($index, $value)
    {
        $this->set($index, $value);
    }

    public function __isset($index)
    {
        return $this->has($index);
    }

    public function __unset($index)
    {
        $this->remove($index);
    }
}