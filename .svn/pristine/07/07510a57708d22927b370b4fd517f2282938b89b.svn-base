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
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $classList = $this->classModel->getClassList($sh);
        $this->view->assign('paging', $this->classModel->paging);
        $this->view->assign('classList', $classList);
        $this->view->layout();
    }

    function inputAction() {

        $id = $this->get('id');
        $form = $this->post('form');
        
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->classModel->validSaveClass($form, $errors)) {
                if ($rs = $this->classModel->saveClass($form)) {
                    $this->redirect("?c=class&a=input&pc=class&pa=index&id={$rs}", true, '保存成功');
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
            $form = $this->classModel->getClass($id);
            if($id && empty($form)) {
                 $this->redirect("?c=class&a=index", true, $this->lang('ID不存在'));
                 exit();
            }
            $this->view->assign('form', $form);
        }

        $schoolList = $this->schoolModel->getSchoolOptionList();
        $this->view->assign('schoolList', $schoolList);

        $this->view->layout();
    }

    function deleteAction() {
        $id = $this->get('id');
        $this->classModel->deleteClass($id);
        $this->redirect("?c=class&a=index", true, '删除成功');
    }

    function getClassOptionAction() {
        $id = $this->get('id');
        $classOptionList = $this->classModel->getClassOptionList($id);
        print json_encode(array(
            'code' => 0,
            'optionList' => $classOptionList,
        ));
    }

    function getClassMutilOptionAction() {
        $schoolId = $this->get('schoolId');
        $classOptionList = $this->classModel->getClassMutilOptionList($schoolId);
        print json_encode(array(
            'code' => 0,
            'optionList' => $classOptionList,
        ));
    }
}

?>
