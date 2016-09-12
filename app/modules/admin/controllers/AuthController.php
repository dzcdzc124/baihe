<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Administrator;
use App\Modules\Admin\Forms\Login as LoginForm;
use App\Modules\Admin\Forms\Register as RegisterForm;


class AuthController extends ControllerBase
{
    public function registerAction()
    {
        $count = Administrator::count();
        $form = $model = null;

        if ($count < 1) {
            $model = new Administrator;
            $form = new RegisterForm($model);
            $form->bind($_POST, $model);

            if ($this->request->isPost()) {
                try {
                    if ($form->isValid()) {
                        $username = $form->getValue('username');
                        $admin = Administrator::findFirstByUsername($username);
                        if ( ! empty($admin)) {
                            throw new \Exception("该用户名已存在", 1);
                        }

                        $model->activated = 1;
                        $model->save();

                        $this->serveJson('创建用户成功', 0);
                    } else {
                        $this->serveJson($form->implodeMessages());
                    }
                } catch (\Exception $e) {
                    $this->serveJson($e->getMessage());
                }
            }
        }

        $this->view->setVars([
            'count' => $count,
            'form' => $form,
            'model' => $model,
        ]);
    }

    public function loginAction()
    {
        $credentials = new \stdClass;
        $form = new LoginForm($credentials);
        $form->bind($_POST, $credentials);

        if ($this->request->isPost()) {
            try {
                if ($form->isValid()) {
                    $this->auth->check($credentials);

                    $this->serveJson('登录成功，正在跳转...', 0);
                } else {
                    $this->serveJson($form->implodeMessages());
                }
            } catch (\Exception $e) {
                $this->serveJson($e->getMessage());
            }
        }

        $this->view->setVars([
            'form' => $form,
        ]);
    }

    public function logoutAction()
    {
        $this->auth->logout();
        $this->response->redirect('/admin/');
    }

    public function changePasswordAction()
    {
        if ($this->request->isPost()) {
            $this->serveJson('该功能尚未开通');
        }
    }
}