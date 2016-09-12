<?php

namespace App\Modules\Api\Controllers;

use Phalcon\Mvc\Dispatcher;

use App\Lib\Mvc\Controller;
use App\Helpers\Wechat as WechatHelper;
use App\Models\District;
use App\Models\Imei;


class ControllerBase extends Controller
{
    protected $loginRequired = true;

    protected $openId;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (parent::beforeExecuteRoute($dispatcher)) {
            if ($this->loginRequired)
                $this->openId = WechatHelper::loginRequired();

            // $this->openId = 'oVFyOjpHl_b0sud_MVDhUc7f_NEY';
            return true;
        }

        return false;
    }

    protected function saveImei($imei, $create = true)
    {
        if ($create) {
            $model = Imei::findFirstByImei($imei);
            if (empty($model)) {
                $cookieDistrict = $this->cookies->get('current-district');
                $district = $cookieDistrict ? $cookieDistrict->getValue() : null;

                $model = new Imei;
                $model->assign([
                    'imei' => $imei,
                    'openId' => $this->openId,
                    'district' => $district,
                    'districtName' => $this->getDistrictName($district),
                ]);

                $model->save();
            }
        }

        $cryptData = $this->crypt->encrypt($imei);
        $this->cookies->set('current-imei', $cryptData, TIMESTAMP + 86400);
        return true;
    }

    protected function getImei()
    {
        $cookieData = $this->cookies->get('current-imei');
        $cryptData = $cookieData ? $cookieData->getValue() : null;
        $imei = $cryptData ? trim($this->crypt->decrypt($cryptData)) : null;

        $model = $imei ? Imei::findFirstByImei($imei) : null;
        return $model;
    }

    protected function getDistrictName($districtId)
    {
        if (empty($districtId))
            return null;

        $cacheName = 'district_' . $districtId;
        if ( ! $this->cache->exists($cacheName)) {
            $district = District::findFirstById($districtId);
            if ($district) {
                $this->cache->save($cacheName, $district->name, 86400 * 30);
                return $district->name;
            } else {
                return null;
            }
        } else {
            return $this->cache->get($cacheName);
        }
    }
}