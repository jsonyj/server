<?php

class SchoolController extends AppController {

    var $schoolModel = null;
    var $schoolAdminModel = null;
    var $studentDetectionModel = null;

    function SchoolController() {
        $this->AppController();
        $this->schoolModel = $this->getModel('School');
        $this->schoolAdminModel = $this->getModel('SchoolAdmin');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
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

                    $form = array(
                        'school_id' => $rs,
                        'name' => '超级管理员',
                        'login' => $form['admin_login'],
                        'password' => $form['admin_password'],
                        'status' => $form['status'],
                    );

                    $schoolAdmin = $this->schoolAdminModel->getAdmin($id);
                    if($schoolAdmin) {
                        $form['id'] = $schoolAdmin['id'];
                    }

                    $this->schoolAdminModel->saveAdmin($form);

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

            $schoolAdmin = $this->schoolAdminModel->getAdmin($id);
            $form['admin_login'] = $schoolAdmin['login'];

            $this->view->assign('form', $form);
        }

        $this->view->layout();
    }

    function deleteAction() {
        $id = $this->get('id');
        $this->schoolModel->deleteSchool($id);
        $this->redirect("?c=school&a=index", true, '删除成功');
    }

    function dataStatisticsAction(){
        $sh = $this->get('sh');
        if($sh){
            $dataStatisticsList = $this->schoolModel->getDataStatistics($sh);
            $this->view->assign('dataStatisticsList',$dataStatisticsList);
        }
        $this->view->layout();
    }
}

?>
