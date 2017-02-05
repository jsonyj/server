<?php

class IndexController extends AppController {

    var $adminModel = null;

    function IndexController() {
        $this->AppController();
        $this->adminModel = $this->getModel('Admin');
    }

    function indexAction() {
        $this->view->layout();
    }

    function loginAction() {
        $errors = array();
        $login = $this->post('login');
        
        if ($this->isComplete()) {
            if ($this->adminModel->login($login, $errors)) {
                $this->redirect('?c=index');
            } else {
                $this->view->assign('errors', $errors);
                $this->view->assign('login', $login);
                $this->view->layoutLogin('login');
            }
        } else {
            $this->view->layoutLogin();
        }
    }

    function logoutAction() {
        $this->unsetSession(SESSION_USER);
        $this->unsetSession(SESSION_ROLE);
        $this->redirect('?c=index&a=login');
    }

    function changeAction() {
        $user = $this->getSession(SESSION_USER);
        $userId = $user['id'];
        
        $form = $this->post('form');
        
        if ($this->isComplete()) {
            
            if ($this->adminModel->validAdminChange($form, $errors)) {
                if ($this->adminModel->updateAdminPwd($userId, $form['password'])) {
                    $this->redirect("?c=index&a=change&id={$userId}", true, '保存成功');
                    exit;
                }
                
                $this->redirect("?c=index&a=change&id={$userId}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->unsetValue($form, 'password');
                $this->view->assign('form', $form);
            }
        }
        else {
            $this->unsetValue($user, 'password');
            $this->view->assign('user', $user);
        }
        
        $this->view->layout();
    }
}

?>
