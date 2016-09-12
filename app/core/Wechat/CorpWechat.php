<?php

namespace App\Wechat;

use App\Wechat\Messages\MessageBase;
use App\Wechat\Utils\WXBizMsgCrypt;
use App\Wechat\Utils\ErrorCode;


class CorpWechat extends Base
{
    protected $type = 'corp';

    private $agentId;

    private $corpId;

    private $secret;

    private $apiHost = 'qyapi.weixin.qq.com';

    private $allowedMediaTypes = array('image', 'voice', 'video', 'file');

    public function __construct(array $options = array())
    {
        $this->token = self::element($options, 'token');
        $this->encodingAESKey = self::element($options, 'encodingAESKey');
        $this->agentId = self::element($options, 'agentId');
        $this->corpId = self::element($options, 'corpId');
        $this->secret = self::element($options, 'secret');
        $this->storage = self::element($options, 'storage', []);
        // $this->accessToken = self::element($options, 'accessToken');
        // $this->ticket = self::element($options, 'ticket');

        if ($this->encodingAESKey)
            $this->crypt = new WXBizMsgCrypt($this->token, $this->encodingAESKey, $this->corpId);

        if ( ! isset(self::$obj))
            self::$obj = $this;
    }

    public function validate(&$echoStr)
    {
        $sVerifyMsgSig = self::element($_GET, 'msg_signature', '');
        $sVerifyTimeStamp = self::element($_GET, 'timestamp', '');
        $sVerifyNonce = self::element($_GET, 'nonce', '');
        $sVerifyEchoStr = self::element($_GET, 'echostr', '');

        $errcode = $this->crypt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $echoStr);

        return $errcode == 0;
    }

    /* 发送消息 */
    public function sendMessage($type, $data, $user = null, $party = null, $tag = null, $agentId = null, $safe = false)
    {
        $type = strtolower($type);
        if ( ! in_array($type, ['text', 'image', 'voice', 'video', 'file', 'news', 'mpnews']))
            return false;

        $agentId = $agentId ? $agentId : $this->getRev('AgentID');
        $agentId = $agentId ? $agentId : $this->agentId;
        $safe = intval($safe);

        switch ($type) {
            case 'text':
                $data = is_string($data) ? ['content' => $data] : (array) $data;
                break;

            case 'image':
            case 'voice':
            case 'video':
            case 'file':
                $data = is_string($data) ? ['media_id' => $data] : (array) $data;
                break;

            case 'news':
            case 'mpnews':
                if (($type == 'mpnews') && is_string($data)) {
                    $data = ['media_id' => $data];
                } else {
                    $data = (array) $data;
                    $data = isset($data['articles']) ? $data : ['articles' => $data];
                }
                break;
        }

        $message = [
            'msgtype' => $type,
            'agentid' => (int) $agentId,
            $type => $data,
            'safe' => (int) $safe,
        ] + $this->_parseUser($user, $party, $tag);

        $message = self::formatJson($message);

        return $this->post('/cgi-bin/message/send', $message);
    }

    /* OAuth2 授权 */
    public function getAuthorizeURL($url = null, $state = null)
    {
        $params = array();
        $params['appid'] = $this->corpId;
        $params['redirect_uri'] = $url;
        $params['response_type'] = 'code';
        $params['scope'] = 'snsapi_base';
        $params['state'] = $state;
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . http_build_query($params) . "#wechat_redirect";
    }

    public function getUserInfo($code)
    {
        return $this->get('/cgi-bin/user/getuserinfo', ['code' => $code]);
    }

    /* 管理通讯录 */
    // 成员关注企业号
    public function authSuccess($userId)
    {
        return $this->get('/cgi-bin/user/authsucc', ['userid' => $userId]);
    }

    // 创建部门
    public function createDepartment($name, $parentId = 1, $order = null, $id = null)
    {
        $data = ['name' => $name, 'parentid' => $parentId];
        if ( ! is_null($order)) $data['order'] = $order;
        if ( ! is_null($id)) $data['id'] = $id;

        return $this->post('/cgi-bin/department/create', self::formatJson($data));
    }

    // 更新部门
    public function updateDepartment($id, $name = null, $parentId = null, $order = null)
    {
        $data = ['id' => $id];
        if ( ! is_null($name)) $data['name'] = $name;
        if ( ! is_null($parentId)) $data['parentid'] = $parentId;
        if ( ! is_null($order)) $data['order'] = $order;

        return $this->post('/cgi-bin/department/update', self::formatJson($data));
    }

    // 删除部门
    public function deleteDepartment($id)
    {
        return $this->get('/cgi-bin/department/delete', ['id' => $id]);
    }

    // 获取部门列表
    public function listDepartment($id = 1)
    {
        $data = [];
        if ( ! is_null($id)) $data['id'] = $id;

        return $this->post('/cgi-bin/department/list', self::formatJson($data));
    }

    // 创建成员
    public function createUser($userId, $name, $data = [])
    {
        $data['userid'] = $userId;
        $data['name'] = $name;

        return $this->post('/cgi-bin/user/create', self::formatJson($data));
    }

    // 更新成员
    public function updateUser($userId, $data = [])
    {
        $data['userid'] = $userId;

        return $this->post('/cgi-bin/user/update', self::formatJson($data));
    }

    // 删除成员
    public function deleteUser($userId)
    {
        return $this->get('/cgi-bin/user/delete', ['userid' => $userId]);
    }

    // 批量删除成员
    public function batchDeleteUser(array $userIdList)
    {
        $userIdList = (array) $userIdList;
        return $this->post('/cgi-bin/user/batchdelete', self::formatJson(['useridlist' => $userIdList]));
    }

    // 获取成员
    public function getUser($userId)
    {
        return $this->get('/cgi-bin/user/get', ['userid' => $userId]);
    }

    // 获取部门成员
    public function listUser($departmentId, $fetchChild = true, $status = 0)
    {
        return $this->get('/cgi-bin/user/simplelist', [
            'department_id' => $departmentId,
            'fetch_child' => $fetchChild ? 1 : 0,
            'status' => $status,
        ]);
    }

    // 获取部门成员(详情)
    public function listUserInfo($departmentId, $fetchChild = true, $status = 0)
    {
        return $this->get('/cgi-bin/user/list', [
            'department_id' => $departmentId,
            'fetch_child' => $fetchChild ? 1 : 0,
            'status' => $status,
        ]);
    }

    // 邀请成员关注
    public function inviteUser($userId)
    {
        return $this->post('/cgi-bin/invite/send', self::formatJson([
            'userid' => $userId,
        ]));
    }

    // 创建标签
    public function createTag($name, $id = null)
    {
        $data = ['tagname' => $name];
        if ( ! is_null($id)) $data['tagid'] = $id;

        return $this->post('/cgi-bin/tag/create', self::formatJson($data));
    }

    // 更新标签名字
    public function updateTag($id, $name)
    {
        return $this->post('/cgi-bin/tag/update', self::formatJson([
            'tagid' => $id,
            'tagname' => $name,
        ]));
    }

    // 删除标签
    public function deleteTag($id)
    {
        return $this->get('/cgi-bin/tag/delete', [
            'tagid' => $id,
        ]);
    }

    // 获取标签成员
    public function listTagUser($id)
    {
        return $this->get('/cgi-bin/tag/get', [
            'tagid' => $id,
        ]);
    }

    // 增加标签成员
    public function addTagUser($id, array $userIdList = [], array $partyIdList = [])
    {
        return $this->post('/cgi-bin/tag/addtagusers', self::formatJson([
            'tagid' => $id,
            'userlist' => $userIdList,
            'partylist' => $partyIdList,
        ]));
    }

    // 删除标签成员
    public function deleteTagUser($id, array $userIdList = [], array $partyIdList = [])
    {
        return $this->post('/cgi-bin/tag/deltagusers', self::formatJson([
            'tagid' => $id,
            'userlist' => $userIdList,
            'partylist' => $partyIdList,
        ]));
    }

    // 获取标签列表
    public function listTag()
    {
        return $this->get('/cgi-bin/tag/list');
    }

    // 获取企业应用
    public function getAgent($agentId = null)
    {
        if (is_null($agentId))
            $agentId = $this->agentId;

        return $this->get('/cgi-bin/agent/get', [
            'agentid' => $agentId,
        ]);
    }

    // 设置企业号应用
    public function setAgent($data, $agentId = null)
    {
        if (is_null($agentId))
            $agentId = $this->agentId;

        $data = (array) $data;
        $data['agentid'] = $agentId;

        return $this->post('/cgi-bin/agent/set', self::formatJson($data));
    }

    // userid 转换成 OpenID
    public function convertOpenId($userId, $agentId = null)
    {
        $data = ['userid' => $userId];
        if ($agentId !== false)
            $data['agentid'] = empty($agentId) ? $this->agentId : $agentId;

        return $this->post('/cgi-bin/user/convert_to_openid', self::formatJson($data));
    }

    // OpenID 转换成 userid
    public function convertUserId($openId)
    {
        return $this->post('/cgi-bin/user/convert_to_userid', self::formatJson([
            'openid' => $openId,
        ]));
    }

    public function grantAccessToken()
    {
        $parameters = array(
            'corpid' => $this->corpId,
            'corpsecret' => $this->secret,
        );

        $res = $this->get('/cgi-bin/gettoken', $parameters);

        if (is_callable([$this->storage, 'save'])) {
            $this->storage->save('accessToken', $res['access_token'], $res['expires_in']);
        } else {
            $this->storage['accessToken'] = $res['access_token'];
        }

        return $res;
    }

    public function grantJsapiTicket()
    {
        $res = $this->get('/cgi-bin/get_jsapi_ticket');

        if (is_callable([$this->storage, 'save'])) {
            $this->storage->save('jsapiTicket', $res['ticket'], $res['expires_in']);
        } else {
            $this->storage['jsapiTicket'] = $res['ticket'];
        }

        return $res;
    }

    public function uploadMedia($mediaType, $filePath)
    {
        if (empty($mediaType))
            return false;

        if ( ! in_array($mediaType, $this->allowedMediaTypes))
            return false;

        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
            $data = ['media' => new \CURLFile($filePath)];
        } else {
            $data = ['media' => '@'.$filePath];
        }
        $parameters = array('type' => $mediaType);
        return $this->post('/cgi-bin/media/upload', $data, $parameters, true);
    }

    public function getMedia($mediaId)
    {
        if(empty($mediaId))
            return false;

        return $this->get('/cgi-bin/media/get', array('media_id'=>$mediaId), false, true);
    }

    public function getMenu()
    {
        return $this->get('/cgi-bin/menu/get', [
            'agentid' => $this->agentId,
        ]);
    }

    public function createMenu($menu)
    {
        $menu = array('button' => $menu);
        $menuJson = self::formatJson($menu);

        return $this->post('/cgi-bin/menu/create', $menuJson, [
            'agentid' => $this->agentId,
        ]);
    }

    public function deleteMenu()
    {
        return $this->get('/cgi-bin/menu/delete', [
            'agentid' => $this->agentId,
        ]);
    }

    public function jsapiSign($url)
    {
        $jsapiTicket = $this->getJsapiTicket();
        $nonceStr = self::createNonceStr();
        $timestamp = strval(time());

        $string = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
        $signature = sha1($string);

        $signPackage = [
            "appId"     => $this->corpId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
        ];
        return $signPackage;
    }

    public function get($url, $parameters = array(), $ssl = true, $is_media = false)
    {
        $port = $ssl ? 443 : 80;
        $request = new HttpRequest($this->apiHost, $url, $port);
        if ($parameters) {
            if ( ! isset($parameters['corpsecret'])) {
                $parameters['access_token'] = $this->getAccessToken();
            } else {
                $parameters = http_build_query($parameters);
            }
        } else {
            $parameters = 'access_token='.$this->getAccessToken();
        }
        $request->setGetData($parameters);
        $request->execute();
        if ( ! $is_media) {
            $result = $request->getResponseText();
            $request->close();
            if ($result) {
                return json_decode($result, true);
            } else {
                return false;
            }
        } else {
            $result = $request->getResponse();
            $request->close();
            $ext = explode('/', $result['content_type']);
            $ext = $ext[1];

            $content = $result['responseText'];
            if (is_array(@json_decode($content, true))) {
                return json_decode($content, true);
            } else {
                return array('ext'=>$ext, 'content'=>$content);
            }
        }
    }

    public function post($url, $data = array(), $parameters = array(), $ssl = true)
    {
        $port = $ssl ? 443 : 80;
        $request = new HttpRequest($this->apiHost, $url, $port);
        $request->setType('POST');
        //$request->setHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');

        if ($parameters) {
            if( ! isset($parameters['access_token']))
                $parameters['access_token'] = $this->getAccessToken();
            $parameters = http_build_query($parameters);
        } else {
            $parameters = 'access_token='.$this->getAccessToken();
        }
        $request->setGetData($parameters);
        $request->setData($data);

        $request->execute();
        $result = $request->getResponseText();
        $request->close();
        if ($result) {
            return json_decode($result, true);
        } else {
            return false;
        }
    }

    private function getAccessToken()
    {
        $accessToken = $this->storage->get('accessToken');
        if (empty($accessToken)) {
            $res = $this->grantAccessToken();
            $accessToken = empty($res['errcode']) ? $res['access_token'] : null;
        }

        return $accessToken;
    }

    private function getJsapiTicket()
    {
        $jsapiTicket = $this->storage->get('jsapiTicket');
        if (empty($jsapiTicket)) {
            $res = $this->grantJsapiTicket();
            $jsapiTicket = empty($res['errcode']) ? $res['ticket'] : null;
        }

        return $jsapiTicket;
    }

    private function _parseUser($user = null, $party = null, $tag = null)
    {
        if (is_null($user) && is_null($party) && is_null($tag))
            $user = $this->getRev('FromUserName');

        $user = is_array($user) ? implode('|', $user) : $user;
        $party = is_array($party) ? implode('|', $party) : $party;
        $tag = is_array($tag) ? implode('|', $tag) : $tag;

        return [
            'touser' => $user,
            'toparty' => $party,
            'totag' => $tag,
        ];
    }
}