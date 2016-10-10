<?php

namespace App\Wechat;


class OAuth
{
    private $openId;

    private $appId;

    private $appSecret;

    private $accessToken;

    private $refreshToken;

    private $apiHost = 'api.weixin.qq.com';

    public function authorizeURL($scope = 'snsapi_base') { return $scope == 'snsapi_login' ? 'https://open.weixin.qq.com/connect/qrconnect' : 'https://open.weixin.qq.com/connect/oauth2/authorize'; }

    public function __construct($appId = null, $appSecret = null, $accessToken = null, $refreshToken = null)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function getAuthorizeURL($url, $responseType = 'code', $scope = 'snsapi_base', $state = null)
    {
        $params = array();
        $params['appid'] = $this->appId;
        $params['redirect_uri'] = $url;
        $params['response_type'] = $responseType;
        $params['scope'] = $scope;
        $params['state'] = $state;
        return $this->authorizeURL($scope) . "?" . http_build_query($params) . "#wechat_redirect";
    }

    public function getAccessToken($code, $grantType = 'authorization_code')
    {
        $params = array();
        $params['appid'] = $this->appId;
        $params['secret'] = $this->appSecret;
        $params['code'] = $code;
        $params['grant_type'] = $grantType;

        $token = $this->get('/sns/oauth2/access_token', $params);
        if ( is_array($token) && empty($token['errcode']) ) {
            $this->accessToken = $token['access_token'];
            $this->refreshToken = $token['refresh_token'];

            $this->openId = $token['openid'];
        } else {
            throw new Exception("get access token failed." . $token['errmsg']);
        }

        return $token;
    }

    public function refreshToken($refreshToken, $grantType = 'refresh_token')
    {
        $params = array();
        $params['appid'] = $this->appId;
        $params['grant_type'] = $grantType;
        $params['refresh_token'] = $refreshToken;

        $token = $this->get('/sns/oauth2/refresh_token', $params);
        if ( is_array($token) && empty($token['errcode']) ) {
            $this->accessToken = $token['access_token'];
            $this->refreshToken = $token['refresh_token'];

            $this->openId = $token['openid'];
        } else {
            throw new Exception("refresh token failed." . $token['errmsg']);
        }

        return $token;
    }

    public function getUserInfo($openId = null, $lang = 'zh_CN')
    {
        $params = array();
        $params['access_token'] = $this->accessToken;
        $params['openid'] = is_null($openId) ? $this->openId : $openId;
        $params['lang'] = $lang;

        $userinfo = $this->get('/sns/userinfo', $params);
        if ( is_array($userinfo) && empty($userinfo['errcode']) ) {
            return $userinfo;
        } else {
            throw new Exception("get user info failed." . $userinfo['errmsg']);
        }
    }

    public function getApiAccessToken() {
        $timestamp = TIMESTAMP;

        $apiUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $apiUrl = sprintf($apiUrl, $this->appId, $this->appSecret);
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, TRUE);

        if(isset($data['access_token']))
            return $data['access_token'];
        else
            return NULL;
    }

    public function getApiTicket(){
        $accessToken = $this->getApiAccessToken();

        if($accessToken){
            $parameters = array(
                'access_token' => $accessToken,
                'type' => 'jsapi'
            );

            $res = $this->get('/cgi-bin/ticket/getticket', $parameters);
            if ($res && empty($res['errcode'])) {
                return $res;
            }
        }
        return NULL;
    }

    public static function ticket() {
        $ticket = self::getShared('dataBag')->get('ticket', NULL, 'core');

        if (empty($ticket) || ($ticket['expire_at'] <= TIMESTAMP)) {
            $accessToken = self::getApiAccessToken();
            $parameters = array(
                'access_token' => $accessToken,
                'type' => 'jsapi'
            );

            $res = self::httpGet('api.weixin.qq.com', '/cgi-bin/ticket/getticket', $parameters);
            if ($res && empty($res['errcode'])) {
                $ticket = array(
                    'ticket' => $res['ticket'],
                    'expire_at' => TIMESTAMP + $res['expires_in'],
                );
                self::getShared('dataBag')->set('ticket', $ticket, 'core');
            }
        }

        return $ticket['ticket'];
    }

    protected function get($url, $parameters = [], $ssl = true)
    {
        $port = $ssl ? 443 : 80;
        $request = new HttpRequest($this->apiHost, $url, $port);
        $request->setGetData($parameters);
        $request->execute();
        $result = $request->getResponseText();
        $request->close();

        if ($result) {
            return @json_decode($result, true);
        } else {
            return false;
        }
    }
}