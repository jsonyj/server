<?php

class SchoolController extends AppController {

    var $schoolModel = null;

    function SchoolController() {
        $this->AppController();
        $this->schoolModel = $this->getModel('School');
    }

    function indexAction() {
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $schoolList = $this->schoolModel->getSchoolList($sh);
        $this->view->assign('paging', $this->schoolModel->paging);
        $this->view->assign('schoolList', $schoolList);
        $this->view->layout();
    }

    function inputAction() {

        $id = $this->get('id');
        $form = $this->post('form');
        
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->schoolModel->validSaveSchool($form, $errors)) {
                if ($rs = $this->schoolModel->saveSchool($form)) {
                    $this->redirect("?c=school&a=input&pc=school&pa=index&id={$rs}", true, '保存成功');
                    exit;
                }
                
                $this->redirect("?c=school&a=input&pc=school&pa=index&id={$id}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            $form = $this->schoolModel->getSchool($id);
            if($id && empty($form)) {
                 $this->redirect("?c=school&a=index", true, $this->lang('ID不存在'));
                 exit();
            }
            $this->view->assign('form', $form);
        }

        $this->view->layout();
    }

    function deleteAction() {
        $id = $this->get('id');
        $this->schoolModel->deleteSchool($id);
        $this->redirect("?c=school&a=index", true, '删除成功');
    }
}

?>
