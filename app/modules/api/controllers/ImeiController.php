<?php

namespace App\Modules\Api\Controllers;

use App\Helpers\Imei as ImeiHelper;
use App\Models\Imei;
use App\Models\ImeiAttempt;
use App\Models\LuckyLog;
use App\Models\RedpacketLog;
use App\Models\VirtualCard;


class ImeiController extends ControllerBase
{
    public function checkAction()
    {
        $attemptTotal = $this->logAttempt(true);
        if ($attemptTotal >= 10)
            $this->serveJson('IMEI码错误次数过多，请稍后再试！');

        $imei = $this->request->get('imei');
        try {
            $data = ImeiHelper::query($imei);
        } catch (\Exception $e) {
            $this->serveJson($e->getMessage());
        }

        if (empty($data)) {
            $attempt = new ImeiAttempt;
            $attempt->assign([
                'openId' => $this->openId,
                'imei' => $imei,
            ]);
            @$attempt->save();
            $this->logAttempt();

            $this->serveJson('你扫描的是无效IMEI码<br>双IMEI手机请扫描另一个IMEI码');
        }

        $machines = trim($this->setting['machines']);
        $machines = $machines ? preg_split('/[\\s,]+/', $machines) : null;
        if ($machines) {
            $machine = $data['machine'];
            if ( ! in_array($machine, $machines))
                $this->serveJson('该手机不在活动范围内');
        }

        $model = Imei::findFirstByImei($imei);
        if ($model) {
            if ($model->openId != $this->openId)
                $this->serveJson('该IMEI码已经参与过活动');

            $luckyLog = LuckyLog::findFirst([
                'conditions' => 'imei = :imei: AND cancelled = :cancelled:',
                'bind' => [
                    'imei' => $imei,
                    'cancelled' => 0,
                ]
            ]);

            $data = [
                'lucky' => $luckyLog ? [
                    'prizeId' => (int) $luckyLog->prizeId,
                    'prizeType' => (int) $luckyLog->prizeType,
                ] : null,
            ];

            if ($luckyLog) {
                if ($luckyLog->prizeType == 2) {
                    $redpacketLog = RedpacketLog::findFirstByLuckyLogId($luckyLog->id);

                    if ($redpacketLog) {
                        $data['lucky']['redpacket'] = [
                            'amount' => (int) $redpacketLog->amount,
                        ];
                    }
                } elseif ($luckyLog->prizeType == 3) {
                    $card = VirtualCard::findFirstByLuckyLogId($luckyLog->id);

                    if ($card) {
                        $data['lucky']['gift'] = [
                            'data1' => $card->data1,
                            'data2' => $card->data2,
                            'data3' => $card->data3,
                            'data4' => $card->data4,
                            'data5' => $card->data5,
                        ];
                    }
                }
            }

            $this->saveImei($imei, false);
            $this->serveJson('OK', 0, $luckyLog ? $data : null);
        } else {
            $imeiTotal = Imei::count([
                'conditions' => 'openId = :openId:',
                'bind' => [
                    'openId' => $this->openId,
                ],
            ]);

            if ($imeiTotal >= 3)
                $this->serveJson('同一微信号最多只能使用3个IMEI码参与活动');

            if ($this->saveImei($imei)) {
                $this->serveJson('OK', 0);
            } else {
                $this->serveJson('出错啦！请重新扫描IMEI码');
            }
        }
    }

    public function infoAction()
    {
        $model = $this->getImei();
        if (empty($model))
            $this->serveJson('请先扫描IMEI码', -9);
        elseif ($model->completed)
            $this->serveJosn('你已提交过资料');

        $name = $this->request->get('name', 'trim');
        if (empty($name))
            $this->serveJson('请填写你的姓名');

        $mobile = $this->request->get('mobile', 'trim');
        if ( ! preg_match('/^1(3|4|5|7|8)[\\d]{9}$/', $mobile))
            $this->serveJson('手机号码格式有误');

        $model->assign([
            'name' => $name,
            'mobile' => $mobile,
            'completed' => 1,
        ]);

        if ($model->save())
            $this->serveJson('OK', 0);
        else
            $this->serveJson('无法完成操作，请重试');
    }

    private function logAttempt($query = false)
    {
        $cacheName = 'imei-attempt-' . $this->openId;
        if ($this->cache->exists($cacheName))
            $currentVal = intval($this->cache->get($cacheName));
        else
            $currentVal = 0;

        if ( ! $query) {
            $currentVal += 1;
            $this->cache->save($cacheName, $currentVal, 3600 * 12);
        }

        return $currentVal;
    }
}