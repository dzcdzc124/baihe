<?php

namespace App\Helpers;


class Sms extends HelperBase
{
    private static $client;

    private static $compCode;

    private static $account;

    private static $password;

    private static function init()
    {
        if (empty(self::$client)) {
            $config = self::getShared('config');

            self::$client = new \SoapClient($config->sms->url);
            self::$compCode = $config->sms->compCode;
            self::$account = $config->sms->account;
            self::$password = $config->sms->password;
        }
    }

    public static function send($mobile, $content)
    {
        self::init();

        if (empty($content))
            return false;

        if (strpos($content, '【vivo】') === false) {
            $content = '【vivo】' . $content;
        }

        $params = array(
            'CompCode' => self::$compCode,
            'Account' => self::$account,
            'Password' => strtolower(md5(self::$password)),
            'Phone' => $mobile,
            'Content' => $content,
            'SendTime' => '',
        );
        $res = self::$client->sendSmsLongJson($params);
        $result = $res->sendSmsLongJsonResult;
        $result = json_decode($result, true);

        return $result['taskID'] > 0;
    }

    public static function sendTemplate($mobile, $tmpl, $data = [])
    {
        $parsedData = [];
        foreach ($data as $key => $value) {
            $parsedData['{'.strtoupper($key).'}'] = $value;
        }

        $content = str_replace(array_keys($parsedData), array_values($parsedData), $tmpl);
        return self::send($mobile, $content);
    }
}