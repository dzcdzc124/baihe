<?php

namespace App\Modules\Admin\Controllers\Prize;

use App\Helpers\System as SystemHelper;
use App\Models\Prize;
use App\Modules\Admin\Forms\Prize as PrizeForm;


class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $totals = $this->request->getPost('totals');
            $weights = $this->request->getPost('weights');

            if (is_array($totals)) {
                Prize::updateData($totals, $weights);
            }

            SystemHelper::clearCache('prize_');
            $this->serveJson('设置已更新', 0);
        }

        $prizeList = Prize::find([
            'order' => 'sort DESC',
        ]);

        $this->view->setVars([
            'prizeList' => $prizeList,
        ]);
    }

    public function createAction()
    {
        $this->dispatcher->forward([
            'controller' => 'index',
            'action' => 'update',
        ]);
    }

    public function updateAction()
    {
        $id = (int) $this->request->getQuery('id');
        $model = Prize::findFirstById($id);
        if (empty($model))
            $model = new Prize;

        $form = new PrizeForm($model);
        $form->bind($_POST, $model);

        if ($this->request->isPost()) {
            try {
                if ($form->isValid()) {
                    if ($model->save()) {
                        SystemHelper::clearCache('prize_');
                        $this->serveJson('奖品已保存', 0);
                    } else {
                        $this->serveJson('奖品信息无法保存');
                    }
                } else {
                    $this->serveJson($form->implodeMessages());
                }
            } catch (\Exception $e) {
                $this->serveJson($e->getMessage());
            }
        }

        $this->view->setVars([
            'model' => $model,
            'form' => $form,
        ]);
    }
}