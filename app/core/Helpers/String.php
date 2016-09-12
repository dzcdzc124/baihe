<?php

namespace App\Helpers;

use Phalcon\Tag;


class String extends HelperBase
{
    public static function truncateUtf8String($string, $length, $etc = ' ...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0)
                    break;

                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        
        if ($i < $strlen && $etc !== false)
            $result .= $etc;
        
        return $result;
    }

    public static function uniRegex($str)
    {
        $strList = preg_split('/\s+/u', $str);

        foreach ($strList as $key => $str) {
            $value = self::toUnicode($str, true);
            $value = array_map(function($v){return '\\x{'.dechex($v).'}';}, $value);

            $strList[$key] = implode('', $value);
        }

        $unicode = implode('\\s*', $strList);

        return $unicode;
    }

    public static function toUnicode($input, $array = false) {
        $value = '';
        $val   = array();
     
        for ($i=0; $i< strlen( $input ); $i++) {
            $ints = ord ( $input[$i] );
         
            $z     = ord ( $input[$i] );
            $y     = ord ( $input[$i+1] ) - 128;
            $x     = ord ( $input[$i+2] ) - 128;
            $w     = ord ( $input[$i+3] ) - 128;
            $v     = ord ( $input[$i+4] ) - 128;
            $u     = ord ( $input[$i+5] ) - 128;
            
            if ( $ints >= 0 && $ints <= 127 ) {
                // 1 bit
                $value[] = $z;
                $value1[]= dechex($z);
            }
            
            if ( $ints >= 192 && $ints <= 223 ) {
            // 2 bit
                $value[] = $temp = ($z-192) * 64 + $y;
                $value1[]= dechex($temp);
            }  
              
            if ( $ints >= 224 && $ints <= 239 ) {
                // 3 bit
                $value[] = $temp = ($z-224) * 4096 + $y * 64 + $x;
                $value1[]= dechex($temp);
            } 
            
            if ( $ints >= 240 && $ints <= 247 ){
                // 4 bit
                $value[] = $temp = ($z-240) * 262144 + $y * 4096 + $x * 64 + $w;
                $value1[]= dechex($temp);
            } 
             
            if ( $ints >= 248 && $ints <= 251 ){
                // 5 bit
                $value[] = $temp = ($z-248) * 16777216 + $y * 262144 + $x * 4096 + $w * 64 + $v;
                $value1[]= dechex($temp);
            }
            
            if ( $ints == 252 || $ints == 253 ){
                // 6 bit
                $value[] = $temp = ($z-252) * 1073741824 + $y * 16777216 + $x * 262144 + $w * 4096 + $v * 64 + $u;
                $value1[]= dechex($temp);
            }
        }
     
        if ( $array === false ) {
            $unicode = '';
            foreach ($value as $value) {
                $unicode .= '&#'.$value.';';
            
            }
            return $unicode;
            
        }

        if ($array === true ) {
           return $value;
        }
    }

    public static function uuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public static function html_a($params)
    {
        $params = (array) $params;
        $text = array_shift($params);
        return Tag::renderAttributes('<a', $params) . '>' . $text . '</a>';
    }

    public static function formatMoney($val)
    {
        return sprintf('%.02f', $val / 100);
    }
}