<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Forms\Setting as SettingForm;


class SettingController extends ControllerBase
{
    protected $access = 'sa';

    public function indexAction()
    {
        $category = $this->request->getQuery('category', 'trim');
        if ( ! in_array($category, ['base']))
            $category = 'base';

        $obj = new \stdClass;

        $form = new SettingForm();
        $form->bind($_POST, $obj);

        if ($this->request->isPost()) {
            try {
                if ($form->isValid()) {
                    $fields = isset($_POST['fields']) ? (array) $_POST['fields'] : [];
                    $this->setting['fields'] = $fields;

                    $requiredFields = isset($_POST['requiredFields']) ? (array) $_POST['requiredFields'] : [];
                    $this->setting['requiredFields'] = $requiredFields;

                    foreach ($obj as $key => $value) {
                        $this->setting[$key] = $value;
                    }

                    $this->setting->reload();

                    $this->serveJson('设置已保存', 0);
                } else {
                    $this->serveJson($form->implodeMessages('<br>'));
                }
            } catch (\Exception $e) {
                $this->serveJson($e->getMessage());
            }
        }

        $this->view->setVars([
            'category' => $category,
            'form' => $form,
        ]);
    }
}