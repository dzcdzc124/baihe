<?php

namespace App\Modules\Admin\Controllers;

use App\Lib\Paginator;
use App\Models\Code;


class CodeController extends ControllerBase
{   
    protected $createNum = 10;

    public function indexAction()
    {
        if ($this->request->isPost()) {
            $givens = $this->request->getPost('givens');

            if (is_array($givens)) {
                Code::updateData($givens);
            }

            $this->serveJson('设置已更新', 0);
        }

        $query = Code::query()->order("created desc");

        $currentPage = $this->request->getQuery('page', 'int');
        $paginator = new Paginator([
            'query' => $query,
            'limit' => 20,
            'page' => $currentPage,
        ]);

        $page = $paginator->getPaginate();

        $this->view->setVars([
            'page' => $page,
            'createNum' => $this->createNum
        ]);
       
    }

    public function createAction()
    {   
        for( $i = 0; $i < $this->createNum; $i++ ){
            $code = new Code;
            $code->code = Code::createCode();
            $code->module = 'pdq';
            $code->created = TIMESTAMP;
            $code->save();
        }

        $this->dispatcher->forward([
            'action' => 'index'
        ]);
    }

    public function updateAction()
    {
        $id = (int) $this->request->getQuery('id');
        $model = Question::findFirstById($id);
        if (empty($model))
            $model = new Question;

        $form = new QuestionForm($model);
        $form->bind($_POST, $model);

        if ($this->request->isPost()) {
            try {
                if ($form->isValid()) {
                    if ($model->save()) {
                        //SystemHelper::clearCache('question_');
                        $this->serveJson('问题已保存', 0);
                    } else {
                        $this->serveJson('问题无法保存');
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