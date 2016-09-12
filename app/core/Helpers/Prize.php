<?php

namespace App\Helpers;

use App\Models\Prize as PrizeModel;
use App\Models\PrizeRate;
use App\Models\VirtualCard;
use App\Lib\Collection;


class Prize extends HelperBase
{
    public static function initCache()
    {
        $result = [
            'all' => [],
            'fails' => [],
            'lucky' => [],
            'defaults' => [],
        ];

        $list = PrizeModel::find();
        foreach ($list as $item) {
            $data = new Collection([
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'total' => (int) $item->total,
                'weight' => (int) $item->weight,
                'sort' => (int) $item->sort,
                'default' => (boolean) $item->default,
            ]);

            $result['all'][] = $data;

            if ($item->type == 0)
                $result['fails'][] = $data;
            else
                $result['lucky'][] = $data;

            if ($item->default)
                $result['defaults'][] = $data;
        }

        self::saveCache($result);
    }

    public static function award($type = 'all')
    {
        $list = self::fetchAll($type);
        if (empty($list))
            return null;

        $weightTotal = self::weightSum($list);
        if ($weightTotal <= 0)
            return null;

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

        if ($currentItem) {
            if (self::hasChance($currentItem)) {
                $keepOne = PrizeModel::getOne($currentItem->id);
                if ($currentItem->type && ! $keepOne)
                    return $currentItem->default ? null : self::awardDefault();
            } else {
                return self::awardDefault();
            }
        }

        return $currentItem;
    }

    public static function awardDefault()
    {
        $prize = self::award('defaults');
        if (empty($prize))
            $prize = self::award('fails');

        return $prize;
    }

    public static function hasChance($prize)
    {
        if ($prize->default || empty($prize->type))
            return true;

        $today = date('Y-m-d', TIMESTAMP);
        $cacheName = 'rates_' . $today;

        $cache = self::getShared('cache');
        $rates = $cache->get($cacheName);
        if (empty($rates)) {
            $rate = PrizeRate::findFirstByDateStr($today);
            $rates = $rate ? $rate->getData() : [];
            $cache->save($cacheName, $rates, 86400 * 2);
        }

        if (empty($rates))
            return false;

        $prizeRate = (int) array_element($rates, 'prizeId_' . $prize->id, 0);
        if ($prizeRate <= 0)
            return false;

        $hour = intval(date('G', TIMESTAMP));
        if (self::randFloat() > $hour / 13)
            return false;

        $rateName = 'lucky_total_' . $today . '_' . $prize->id;
        if ($cache->get($rateName) >= $prizeRate)
            return false;

        return $cache->increment($rateName) <= $prizeRate;
    }

    public static function fetchAll($name)
    {
        self::hasCache($name) || self::initCache();
        return self::fetchCache($name);
    }

    public static function exchange($luckyLog, $data = null)
    {
        if ($luckyLog->prizeType == 1) {
            if ($luckyLog->exchanged)
                throw new \Exception('您已兑奖，请耐心等待奖品寄送');
            elseif (empty($data))
                return true;


            $data = (array) $data;
            $luckyLog->assign([
                'address' => array_element($data, 'address'),
                'idcard' => array_element($data, 'idcard'),
                'exchanged' => TIMESTAMP,
            ]);
            if ($luckyLog->save())
                return true;
            else
                throw new \Exception('兑奖出错，请稍后重试');
        } elseif ($luckyLog->prizeType == 2) {
            try {
                $redpacketLog = Redpacket::exchange($luckyLog);
                $luckyLog->exchanged = TIMESTAMP;
                $luckyLog->save();

                return $redpacketLog;
            } catch (\Exception $e) {
                $luckyLog->cancelled = 1;
                $luckyLog->save();

                return false;
            }
        } elseif ($luckyLog->prizeType == 3) {
            $luckyLog->exchanged = TIMESTAMP;
            $luckyLog->save();

            $card = VirtualCard::fetchOne($luckyLog->id);
            System::runTask('sms/send', ['virtualCardId' => $card->id]);
            return $card;
        }

        return false;
    }

    private static function saveCache($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $n => $v) {
                self::saveCache($n, $v);
            }
        } else {
            $cache = self::getShared('cache');
            $cache->save('prize_' . $name, $value, 86400 * 60);
        }
    }

    private static function fetchCache($name)
    {
        $cache = self::getShared('cache');
        return $cache->get('prize_' . $name);
    }

    private static function hasCache($name)
    {
        $cache = self::getShared('cache');
        return $cache->exists('prize_' . $name);
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

    private static function randFloat($min = 0, $max = 1, $mul = 999999)
    {
        if ($min > $max)
            return 0;

        return mt_rand($min * $mul, $max * $mul) / $mul;
    }
}