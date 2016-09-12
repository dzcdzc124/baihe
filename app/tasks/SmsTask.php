<?php

namespace App\Tasks;

use App\Helpers\Sms as SmsHelper;
use App\Models\LuckyLog;
use App\Models\VirtualCard;


class SmsTask extends TaskBase
{
    public function sendAction($params = [])
    {
        $this->initParams($params);
        $virtualCardId = $this->fetchParam('virtualCardId');

        if (empty($virtualCardId))
            return false;

        $smsTmpl = $this->setting->get('smsTmpl');
        if (empty($smsTmpl))
            return false;

        $virtualCard = VirtualCard::findFirstById($virtualCardId);
        if (empty($virtualCard) || empty($virtualCard->luckyLogId))
            return false;

        $luckyLog = LuckyLog::findFirstById($virtualCard->luckyLogId);
        if (empty($luckyLog) || $luckyLog->prizeType != 3)
            return false;

        $data = [
            'url' => '',
            'data1' => $virtualCard->data1,
            'data2' => $virtualCard->data2,
            'data3' => $virtualCard->data3,
            'data4' => $virtualCard->data4,
            'data5' => $virtualCard->data5,
        ];

        $games = (array) $this->config->games;
        $url = array_element($games, $virtualCard->data1, '');
        $data['url'] = $url;

        SmsHelper::sendTemplate($luckyLog->mobile, $smsTmpl, $data);
    }
}