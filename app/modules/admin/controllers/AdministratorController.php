<?php

namespace App\Modules\Admin\Controllers;

use App\Lib\Paginator;
use App\Models\Administrator;
use App\Modules\Admin\Forms\Administrator as AdministratorForm;


class AdministratorController extends ControllerBase
{
    protected $access = 'sa';

    public function indexAction()
    {
        $query = Administrator::query();
        $query->order('username ASC');

        $currentPage = $this->request->getQuery('page', 'int');
        $currentPage = max($currentPage, 1);
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

    public function createAction()
    {
        $this->dispatcher->forward([
            'controller' => 'administrator',
            'action' => 'update',
        ]);
    }

    public function updateAction()
    {
        $id = $this->request->getQuery('id', 'int');
        $model = $id ? Administrator::findFirstById($id) : null;
        if (empty($model))
            $model = new Administrator;

        $form = new AdministratorForm($model);

        if ($this->request->isPost()) {
            $form->bind($_POST, $model);

            try {
                if ($form->isValid()) {
                    $password = $form->getValue('newPassword');
                    if ($password)
                        $model->setPassword($password);

                    $model->save();
                    $this->serveJson('数据已保存', 0);
                } else {
                    $this->serveJson($form->implodeMessages('<br>'));
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
