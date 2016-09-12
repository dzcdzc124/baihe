<?php

namespace App\Modules\Admin\Controllers\Prize;

use App\Lib\Paginator;
use App\Helpers\Prize as PrizeHelper;
use App\Helpers\System as SystemHelper;
use App\Models\Redpacket;
use App\Modules\Admin\Forms\Redpacket as RedpacketForm;


class RedpacketController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $totals = $this->request->getPost('totals');
            $weights = $this->request->getPost('weights');

            if (is_array($totals)) {
                Redpacket::updateData($totals, $weights);
            }

            SystemHelper::clearCache('redpacket_');
            $this->serveJson('设置已更新', 0);
        }

        $query = Redpacket::query();
        $query->order('sort DESC');

        $redpacketList = $query->execute();

        $this->view->setVars([
            'redpacketList' => $redpacketList,
        ]);
    }

    public function createAction()
    {
        $this->dispatcher->forward([
            'controller' => 'redpacket',
            'action' => 'update',
        ]);
    }

    public function updateAction()
    {
        $id = (int) $this->request->getQuery('id');
        $model = Redpacket::findFirstById($id);
        if (empty($model))
            $model = new Redpacket;

        $form = new RedpacketForm($model);
        $form->bind($_POST, $model);

        if ($this->request->isPost()) {
            try {
                if ($form->isValid()) {
                    if ($model->save()) {
                        SystemHelper::clearCache('redpacket_');
                        $this->serveJson('红包设置已保存', 0);
                    } else {
                        $this->serveJson('红包设置无法保存');
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