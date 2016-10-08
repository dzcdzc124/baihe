<?php

namespace App\Modules\Site\Controllers;

use App\Helpers\Imei as ImeiHelper;
use App\Models\Order;


class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->saveDistrict();

        $this->view->setVars([
            'isWeiXin' => $this->userAgent->isWeixin(),
            'isMobile' => $this->userAgent->isMobile()
        ]);

        die(var_dump(substr('order', strrpos('order', '\\') + 1)));

    private function saveDistrict()
    {
        $district = $this->dispatcher->getParam('district');
        if ($district) {
            $cacheName = 'district_' . $district;
            if ( ! $this->cache->exists($cacheName)) {
                $dm = District::findFirstById($district);
                if (empty($dm)) {
                    $district = null;
                } else {
                    $this->cache->save($cacheName, $dm->name, 86400 * 30);
                }
            }

            $this->cookies->set('current-district', $district, TIMESTAMP + 3600);
        }
    }
}