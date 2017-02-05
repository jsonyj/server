
<?php

class ClassController extends AppController {

    var $schoolModel = null;
    var $classModel = null;

    function ClassController() {
        $this->AppController();
        $this->schoolModel = $this->getModel('School');
        $this->classModel = $this->getModel('Class');
    }

    function indexAction() {
        $user = $this->getSession(SESSION_USER);

        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $classList = $this->classModel->getClassList($user['school_id'], $sh);
        $this->view->assign('paging', $this->classModel->paging);
        $this->view->assign('classList', $classList);
        $this->view->layout();
    }

    function inputAction() {

        $user = $this->getSession(SESSION_USER);
        $page = $this->get('page');
        $id = $this->get('id');
        $form = $this->post('form');

        $form['school_id'] = $user['school_id'];
        
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->classModel->validSaveClass($form, $errors)) {
                if ($rs = $this->classModel->saveClass($form)) {
                    $this->redirect("?c=class&a=index&sh[page]=".$page, true, '保存成功');
                    exit;
                }
                
                $this->redirect("?c=class&a=input&pc=class&pa=index&id={$id}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            $form = $this->classModel->getClass($user['school_id'], $id);
            if($id && empty($form)) {
                 $this->redirect("?c=class&a=index", true, $this->lang('ID不存在'));
                 exit();
            }
            $this->view->assign('form', $form);
        }

        $this->view->layout();
    }

    function deleteAction() {
        $user = $this->getSession(SESSION_USER);
        $id = $this->get('id');
        $this->classModel->deleteClass($user['school_id'], $id);
        $this->redirect("?c=class&a=index", true, '删除成功');
    }

    function getClassOptionAction() {
        $user = $this->getSession(SESSION_USER);
        $classOptionList = $this->classModel->getClassOptionList($user['school_id']);
        print json_encode(array(
            'code' => 0,
            'optionList' => $classOptionList,
        ));
    }
}

?>
