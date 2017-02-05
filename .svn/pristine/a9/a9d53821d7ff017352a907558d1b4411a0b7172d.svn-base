<?php

class DeviceController extends AppController {

    var $deviceModel = null;
    var $schoolModel = null;

    function DeviceController() {
        $this->AppController();
        $this->deviceModel = $this->getModel('Device');
        $this->schoolModel = $this->getModel('School');
    }

    function indexAction() {
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $deviceList = $this->deviceModel->getDeviceList($sh);
        $this->view->assign('paging', $this->deviceModel->paging);
        $this->view->assign('deviceList', $deviceList);
        $this->view->layout();
    }

    function inputAction() {

        $id = $this->get('id');
        $form = $this->post('form');
        
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->deviceModel->validSaveDevice($form, $errors)) {
                if ($rs = $this->deviceModel->saveDevice($form)) {
                    $this->redirect("?c=device&a=input&pc=device&pa=index&id={$rs}", true, '保存成功');
                    exit;
                }
                
                $this->redirect("?c=device&a=input&pc=device&pa=index&id={$id}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            $form = $this->deviceModel->getDevice($id);
            if($id && empty($form)) {
                 $this->redirect("?c=school&a=index", true, $this->lang('ID不存在'));
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
        $this->deviceModel->deleteDevice($id);
        $this->redirect("?c=device&a=index", true, '删除成功');
    }
}

?>
