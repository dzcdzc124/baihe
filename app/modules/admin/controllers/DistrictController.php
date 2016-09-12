<?php

namespace App\Modules\Admin\Controllers;

use App\Lib\Paginator;
use App\Helpers\System as SystemHelper;
use App\Models\District;


class DistrictController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $data = (array) $this->request->getPost('data');
            try {
                District::saveAll($data);
                SystemHelper::clearCache('district_');
            } catch (\Exception $e) {
                $this->serveJson($e->getMessage());
            }

            $this->serveJson('设置已保存', 0);
        }

        $query = District::query();
        $query->order('name ASC');

        $currentPage = $this->request->getQuery('page', 'int');
        $paginator = new Paginator([
            'query' => $query,
            'limit' => 20,
            'page' => $currentPage,
        ]);

        $page = $paginator->getPaginate();

        $this->view->setVars([
            'page' => $page,
        ]);
    }

    public function deleteAction()
    {
        $id = $this->request->get('id', 'trim');

        $model = District::findFirstById($id);
        if (empty($model))
            $this->serveJson('找不到该区域', 0);

        if ($model->delete()) {
            SystemHelper::clearCache('district_');
            $this->serveJson('区域已删除', 0);
        } else {
            $this->serveJson('无法完成操作，请重试');
        }
    }
}