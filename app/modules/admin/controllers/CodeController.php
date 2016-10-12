<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\System as SystemHelper;
use App\Models\Question;
use App\Modules\Admin\Forms\Question as QuestionForm;


class QuestionController extends ControllerBase
{
    public function indexAction()
    {
        $query = Imei::query();

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
        if ($this->request->isPost()) {
            $questions = $this->request->getPost('questions');
            $sorts = $this->request->getPost('sorts');
            $reverses = $this->request->getPost('reverses');

            if (is_array($questions)) {
                Question::updateData($questions, $sorts, $reverses);
            }

            //SystemHelper::clearCache('Question_');
            $this->serveJson('设置已更新', 0);
        }

        $questionList = Question::find([
            'order' => 'sort',
        ]);

        $this->view->setVars([
            'questionList' => $questionList,
        ]);
    }

    public function createAction()
    {
        $this->dispatcher->forward([
            'action' => 'update',
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