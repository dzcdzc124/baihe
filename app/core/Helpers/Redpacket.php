<?php

namespace App\Helpers;

use App\Models\Redpacket as RedpacketModel;
use App\Models\RedpacketLog;
use App\Lib\Collection;


class Redpacket extends HelperBase
{
    public static function initCache()
    {
        $result = [
            'all' => [],
        ];

        $list = RedpacketModel::find();
        foreach ($list as $item) {
            $data = new Collection([
                'id' => $item->id,
                'minimum' => (int) $item->minimum,
                'maximum' => (int) $item->maximum,
                'total' => (int) $item->total,
                'weight' => (int) $item->weight,
                'sort' => (int) $item->sort,
            ]);

            $result['all'][] = $data;
        }

        self::saveCache($result);
    }

    public static function fetchAll($name = 'all', $filter = false)
    {
        self::hasCache($name) || self::initCache();
        $list = self::fetchCache($name);

        if ($filter) {
            $cache = self::getShared('cache');
            array_walk($list, function ($item, $key) use (&$list, $cache) {
                $cacheName = 'lucky_redpacket_' . $item->id;
                $currentTotal = intval($cache->get($cacheName));
                if ($currentTotal >= $item->total)
                    unset($list[$key]);
            });
        }

        return $list;
    }

    public static function send($rpLog)
    {
        if ($rpLog->amount <= 0)
            throw new \Exception('红包金额为0');
            
        $setting = self::getShared('setting');

        $data = [
            'mch_billno' => $rpLog->getBillNo(),
            're_openid' => $rpLog->openId,
            'total_amount' => $rpLog->amount,
            'total_num' => 1,
            'client_ip' => $rpLog->ipAddr,
            'send_name' => $setting['sendName'],
            'act_name' => $setting['actName'],
            'remark' => $setting['remark'],
            'wishing' => $setting['wishing'],
        ];

        $res = Wechat::client()->sendRedpacket($data);
        // $rawData = $res['raw'];
        $result = (array) $res['result'];

        if ($result) {
            $rpLog->assign([
                'code' => array_element($result, 'return_code'),
                'msg' => array_element($result, 'return_msg'),
                'resultCode' => array_element($result, 'result_code'),
                'errCode' => array_element($result, 'err_code'),
                'errCodeDes' => array_element($result, 'err_code_des'),
                'mchBillNo' => array_element($result, 'mch_billno'),
                'mchId' => array_element($result, 'mch_id'),
                'sentAt' => array_element($result, 'send_time'),
                'sentListId' => array_element($result, 'send_listid'),
            ]);

            $rpLog->save();

            if (array_element($result, 'return_code') == 'SUCCESS' &&
                array_element($result, 'result_code') == 'SUCCESS')
                return true;
            else
                return false;
        }

        return false;
    }

    public static function exchange($luckyLog)
    {
        $redpacketLog = RedpacketLog::findFirstByLuckyLogId($luckyLog->id);
        if (empty($redpacketLog)) {
            $list = self::fetchAll('all', true);
            if (empty($list))
                throw new \Exception('没有可用的红包');
            
            $weightTotal = self::weightSum($list);
            if ($weightTotal <= 0)
                throw new \Exception('没有可用的红包');

            $num = mt_rand(0, $weightTotal - 1);
            $currentNum = 0;
            $currentItem = null;
            foreach ($list as $item) {
                if (($currentNum <= $num) && ($num < $currentNum + $item->weight)) {
                    $currentItem = $item;
                    break;
                } else {
                    $currentNum += $item->weight;
                }
            }

            if (empty($currentItem))
                throw new \Exception('抽取红包出错！');
            elseif ( ! self::hasChance($currentItem))
                throw new \Exception('该红包已发放完毕');

            $amount = mt_rand($currentItem->minimum, $currentItem->maximum);
            if ($amount <= 0)
                throw new \Exception('红包金额为0');

            $redpacketLog = new RedpacketLog;
            $redpacketLog->assign([
                'redpacketId' => $currentItem->id,
                'luckyLogId' => $luckyLog->id,
                'openId' => $luckyLog->openId,
                'amount' => $amount,
                'ipAddr' => $luckyLog->ipAddr,
                'created' => $luckyLog->created,
            ]);

            $redpacketLog->save();
        }

        if ( ! self::send($redpacketLog))
            throw new \Exception('红包发送错误');

        return $redpacketLog;
    }

    public static function hasChance($item)
    {
        $keepOne = RedpacketModel::getOne($item->id);
        if ($keepOne) {
            $cache = self::getShared('cache');
            $cacheName = 'lucky_redpacket_' . $item->id;
            $cache->increment($cacheName);
        }

        return $keepOne;
    }

    private static function saveCache($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $n => $v) {
                self::saveCache($n, $v);
            }
        } else {
            $cache = self::getShared('cache');
            $cache->save('redpacket_' . $name, $value, 86400 * 60);
        }
    }

    private static function fetchCache($name)
    {
        $cache = self::getShared('cache');
        return $cache->get('redpacket_' . $name);
    }

    private static function hasCache($name)
    {
        $cache = self::getShared('cache');
        return $cache->exists('redpacket_' . $name);
    }

    private static function weightSum($list)
    {
        $total = array_reduce((array) $list, function($carry, $item) {
            if (isset($item['weight']))
                $carry += $item['weight'];

            return $carry;
        }, 0);

        return $total;
    }
}