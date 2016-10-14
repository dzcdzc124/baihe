<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\System as SystemHelper;
use App\Models\Product;


class ProductController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $questions = $this->request->getPost('names');
            $sorts = $this->request->getPost('details');
            $reverses = $this->request->getPost('total_fees');

            if (is_array($questions)) {
                Product::updateData($questions, $sorts, $reverses);
            }

            //SystemHelper::clearCache('Question_');
            $this->serveJson('设置已更新', 0);
        }

        $productList = Product::find([
            'order' => 'id',
        ]);

        $this->view->setVars([
            'productList' => $productList,
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