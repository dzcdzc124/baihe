<?php

if ( ! function_exists("fastcgi_finish_request")) {
    function fastcgi_finish_request()
    {
        return true;
    }
}

if ( ! function_exists('elapsed_time')) {
    function elapsed_time()
    {
        if ( ! defined('APP_START_TIME'))
            return 0;

        $startTime = APP_START_TIME;
        return sprintf('%.4f', microtime(true) - $startTime);
    }
}

if ( ! function_exists('convert_size')) {
    function convert_size($size)
    {
        $units = ['B','KB','MB','GB','TB','PB'];
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $units[$i];
    }
}

if ( ! function_exists('memory_usage')) {
    function memory_usage()
    {
        return convert_size(memory_get_usage(true));
    }
}

if ( ! function_exists('array_element')) {
    function array_element($arr, $key, $default = null)
    {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}

if ( ! function_exists('exitmsg')) {
    function exitmsg($content = null)
    {
        exit($content);
    }
}