<?php

namespace App\Helpers;

class System extends HelperBase
{
    public static function runTask($task, $params = [], $output = null)
    {
        $php = self::getShared('setting')->get('phpBinPath', '/usr/bin/env php');
        $cmd = sprintf('%s %scli.php %s', $php, APP_PATH, $task);

        if ($params) {
            $dataId = self::sendData($params);
            $cmd .= ' ' . $dataId;
        }

        if ($output)
            $cmd .= ' >> ' . $output;

        $handle = popen($cmd.' &', 'r');
        pclose($handle);
    }

    public static function clearCache($prefix = null)
    {
        $cache = self::getShared('cache');
        if (is_null($prefix))
            return $cache->flush();

        $config = self::getShared('config');
        $originPrefix = (string) $config->application->cache->server->prefix;
        $len = strlen($originPrefix);
        $keys = $cache->queryKeys($originPrefix . $prefix);
        foreach ($keys as $key) {
            $cacheKey = substr($key, $len);
            $cache->delete($cacheKey);
        }
        return true;
    }

    public static function idcardCheck($idcard)
    {
        $idcard = strtoupper(trim($idcard));
        if ( ! preg_match('/^\\d{17}[\\dxX]$/', $idcard))
            return false;

        $weights = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $results = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $arr1 = str_split($idcard);
        $arr2 = [];
        for ($i = 0; $i <= 16; $i ++) {
            $arr2[] = intval($arr1[$i]) * $weights[$i];
        }
        $x = array_sum($arr2) % 11;
        $x = $results[$x];
        return $x == $arr1[17];
    }

    public static function sendData($data)
    {
        $data = serialize($data);
        $uniqueId = time() . uniqid();

        self::getShared('cache')->save($uniqueId, $data, 600);
        return $uniqueId;
    }

    public static function receiveData($uniqueId)
    {
        $cache = self::getShared('cache');
        if ($cache->exists($uniqueId)) {
            $data = @unserialize($cache->get($uniqueId));
            return $data;
        } else {
            return null;
        }
    }
}