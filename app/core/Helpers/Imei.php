<?php

namespace App\Helpers;


class Imei extends HelperBase
{
    private static $link;

    public static function query($imei)
    {
        if ( ! preg_match('/^\d{15}$/', $imei))
            throw new \Exception('IMEI码格式不正确');

        $data = null;
        self::connect();
        $sql = sprintf("SELECT * FROM UV_product WHERE IMEI='%s'", $imei);
        $query = @mssql_query($sql, self::$link);

        if (mssql_num_rows($query)) {
            $rawData = mssql_fetch_assoc($query);

            $data = [
                'imei' => $rawData['IMEI'],
                'sn' => strtoupper($rawData['SN']),
                'machine' => $rawData['Machine'],
                'color' => iconv('gbk', 'utf8', $rawData['Color']),
                'opTime' => strtotime($rawData['OpTime']),
            ];
        }

        return $data;
    }

    public static function connect()
    {
        if ( ! isset(self::$link)) {
            self::$link = @mssql_connect('172.20.125.101:1433', 'bbkweb', 'bbkweb');
            mssql_select_db('mms', self::$link);
        }
    }
}