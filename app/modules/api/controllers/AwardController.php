<?php

namespace App\Modules\Api\Controllers;

use App\Helpers\Prize as PrizeHelper;
use App\Models\LuckyLog;
use App\Models\AwardLog;


class AwardController extends ControllerBase
{
    public function indexAction()
    {
        $imei = $this->getImei();
        if (empty($imei) || empty($imei->completed))
            $this->serveJson('请先扫描手机IMEI码');

        $awarded = LuckyLog::count([
            'conditions' => 'imei = :imei: AND cancelled = :cancelled:',
            'bind' => [
                'imei' => $imei->imei,
                'cancelled' => 0,
            ]
        ]);
        if ($awarded > 0)
            $this->serveJson('该IMEI码已经参与过抽奖');

        $isLucky = LuckyLog::count([
            'conditions' => 'openId = :openId: AND prizeType = :prizeType:',
            'bind' => [
                'openId' => $this->openId,
                'prizeType' => 1,
            ],
        ]);

        if ($isLucky)
            $prize = PrizeHelper::awardDefault();
        else
            $prize = PrizeHelper::award();

        $n = 0;
        $res = null;
        while ($n < 5) {
            $awardLog = new AwardLog;
            $awardLog->assign([
                'openId' => $this->openId,
                'prizeId' => $prize->id,
            ]);
            @$awardLog->save();

            if ($prize->type > 0) {
                $luckyLog = new LuckyLog;
                $luckyLog->assign([
                    'openId' => $this->openId,
                    'imei' => $imei->imei,
                    'prizeId' => $prize->id,
                    'prizeType' => $prize->type,
                    'name' => $imei->name,
                    'mobile' => $imei->mobile,
                ]);

                try {
                    $luckyLog->save();
                    if ($res = PrizeHelper::exchange($luckyLog))
                        break;
                    else
                        $prize = PrizeHelper::awardDefault();
                } catch (\Exception $e) {
                    $prize = PrizeHelper::awardDefault();
                }
            } else {
                break;
            }

            $n += 1;
        }

        $data = [
            'prizeId' => (int) $prize->id,
            'prizeType' => (int) $prize->type,
        ];

        if ($prize->type == 2 && $res) {
            $data['redpacket'] = [
                'amount' => (int) $res->amount,
            ];
        } elseif ($prize->type == 3 && $res) {
            $data['gift'] = [
                'data1' => $res->data1,
                'data2' => $res->data2,
                'data3' => $res->data3,
                'data4' => $res->data4,
                'data5' => $res->data5,
            ];
        }

        $this->serveJson('OK', 0, $data);
    }
}