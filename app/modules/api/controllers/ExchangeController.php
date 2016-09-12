<?php

namespace App\Modules\Api\Controllers;

use App\Models\LuckyLog;
use App\Helpers\Prize as PrizeHelper;
use App\Helpers\System as SystemHelper;


class ExchangeController extends ControllerBase
{
    public function indexAction()
    {
        $imei = $this->getImei();
        if (empty($imei) || empty($imei->completed))
            $this->serveJson('请先扫描手机IMEI码');

        $luckyLog = LuckyLog::findFirst([
            'conditions' => 'imei = :imei: AND cancelled = :cancelled:',
            'bind' => [
                'imei' => $imei->imei,
                'cancelled' => 0,
            ]
        ]);
        if (empty($luckyLog))
            $this->serveJson('很遗憾，你没有中奖');
        elseif ($luckyLog->exchanged)
            $this->serveJson('您已兑奖，请耐心等待奖品寄送');
        elseif ($luckyLog->prizeType != 1)
            $this->serveJson('非实物奖品无需兑奖');

        $address = $this->request->get('address', 'trim');
        if (empty($address))
            $this->serveJson('请输入你的收件地址');

        $idcard = $this->request->get('idcard', 'trim');
        if ( ! SystemHelper::idcardCheck($idcard))
            $this->serveJson('身份证号码不正确');

        try {
            PrizeHelper::exchange($luckyLog, [
                'address' => $address,
                'idcard' => $idcard,
            ]);
        } catch (\Exception $e) {
            $this->serveJson($e->getMessage());
        }

        $this->serveJson('兑奖成功', 0);
    }
}