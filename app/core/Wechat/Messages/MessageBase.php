<?php

namespace App\Wechat\Messages;

class MessageBase
{
    const MSGTYPE_TEXT = 'text';
    const MSGTYPE_IMAGE = 'image';
    const MSGTYPE_LOCATION = 'location';
    const MSGTYPE_LINK = 'link';
    const MSGTYPE_EVENT = 'event';
    const MSGTYPE_MUSIC = 'music';
    const MSGTYPE_NEWS = 'news';
    const MSGTYPE_VOICE = 'voice';
    const MSGTYPE_VIDEO = 'video';
    
    public $type = NULL;

    public static function formatJson(array $data = array())
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function XMLSafeStr($str)
    {   
        return '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str) . ']]>';   
    } 

    public static function dataToXML($data) {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  (is_array($val) || is_object($val)) ? self::dataToXML($val)  : self::XMLSafeStr($val);
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }    

    public function XMLEncode($data, $root = 'xml', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8') {
        $xml = null;
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml   .= "<{$root}{$attr}>";
        $xml   .= self::dataToXML($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }

    public function outputXML($from, $to)
    {
        return NULL;
    }

    public function outputJson($to)
    {
        return NULL;
    }
}