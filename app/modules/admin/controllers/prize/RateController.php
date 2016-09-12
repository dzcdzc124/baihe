<?php

namespace App\Modules\Admin\Controllers\Prize;

use App\Lib\Paginator;
use App\Helpers\Prize as PrizeHelper;
use App\Helpers\System as SystemHelper;
use App\Models\PrizeRate;


class RateController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $data = (array) $this->request->getPost('data');
            try {
                PrizeRate::saveAll($data);
                SystemHelper::clearCache('rates_');
            } catch (\Exception $e) {
                $this->serveJson($e->getMessage());
            }

            $this->serveJson('设置已保存', 0);
        }

        $query = PrizeRate::query();
        $query->order('dateStr DESC');

        $currentPage = $this->request->getQuery('page', 'int');
        $paginator = new Paginator([
            'query' => $query,
            'limit' => 20,
            'page' => $currentPage,
        ]);

        $page = $paginator->getPaginate();

        $prizeList = PrizeHelper::fetchAll('lucky');
        array_walk($prizeList, function ($item, $key) use (&$prizeList) {
            if ($item->default)
                unset($prizeList[$key]);
        });

        $this->view->setVars([
            'prizeList' => $prizeList,
            'page' => $page,
        ]);
    }

    public function deleteAction()
    {
        $dateStr = $this->request->get('date', 'trim');
        if ( ! preg_match('/^[\\d]{4}\-[\\d]{2}\-[\\d]{2}$/', $dateStr))
            $this->serveJson('日期格式不正确');

        $model = PrizeRate::findFirstByDateStr($dateStr);
        if (empty($model))
            $this->serveJson('找不到该记录', 0);

        if ($model->delete()) {
            SystemHelper::clearCache('rates_');
            $this->serveJson('记录已删除', 0);
        } else {
            $this->serveJson('无法完成操作，请重试');
        }
    }
}