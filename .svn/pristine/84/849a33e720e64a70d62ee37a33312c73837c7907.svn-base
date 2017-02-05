<?php

class AdminController extends AppController {

    var $AdminModel = null;

    function AdminController() {
        $this->AppController();
        $this->AdminModel = $this->getModel('Admin');
    }

    function indexAction() {
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $adminList = $this->AdminModel->adminList($sh);
        $this->view->assign('paging', $this->AdminModel->paging);
        $this->view->assign('adminList', $adminList);
        $this->view->layout();
    }
    
    function inputAction() {
        $id = $this->get('id');
        $admin = $this->post('admin');
        
        if ($this->isComplete()) {
            $admin['id'] = $id;
            if ($this->AdminModel->validAdmin($admin, $errors)) {
                if ($rs = $this->AdminModel->saveAdmin($admin)) {
                    $this->redirect("?c=admin&a=input&id={$rs}", true, '系统账号保存成功。');
                    exit;
                }
                
                $this->redirect("?c=admin&a=input&id={$id}", true, '系统账号保存失败。');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('admin', $admin);
            }
        }
        else {
            $admin = $this->AdminModel->admin($id);
            if($id && empty($admin)) {
                 $this->redirect("?c=admin&a=index", true, $this->lang('系统账号ID不存在。'));
            }
            $this->unsetValue($admin, 'password');
            $this->view->assign('admin', $admin);
        }
        
        $this->view->layout();
    }
    
    function deleteAction() {
        $id = $this->get('id');
        $this->AdminModel->deleteAdmin($id);
        $result = array('status' => 1, 'msg' => $this->lang('delete_admin_success'));
        echo json_encode($result);
        exit;
    }
    
    function changeAction() {
        $admin_info = $this->getSession(SESSION_USER);
        $admin_id = $admin_info['id'];
        
        $admin = $this->post('admin');
        
        if ($this->isComplete()) {
            
            if ($this->AdminModel->validAdminChange($admin, $errors)) {
                if ($this->AdminModel->updateAdminPwd($admin_id, $admin['password'])) {
                    $this->redirect("?c=admin&a=change&id={$admin_id}", true, '保存成功。');
                    exit;
                }
                
                $this->redirect("?c=admin&a=change&id={$admin_id}", true, '保存失败。');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->unsetValue($admin_info, 'password');
                $this->view->assign('admin', $admin_info);
            }
        }
        else {
            $this->unsetValue($admin_info, 'password');
            $this->view->assign('admin', $admin_info);
        }
        
        $this->view->layout();
    }

    function testAction() {
        $fileModel = $this->getModel('File');

        $iphone_thumb = $this->code('iphone_thumb');

        $file_list = $fileModel->getFileList();

        foreach($file_list as $file) {
            switch($file['sub_type']) {
                case 1:
                    $fileModel->resizeIPhoneThumb(APP_RESOURCE_ROOT . $file['file_path'], $iphone_thumb['product']['list_image']['width'], $iphone_thumb['product']['list_image']['height']);
                    break;

                case 2:
                    $fileModel->resizeIPhoneThumb(APP_RESOURCE_ROOT . $file['file_path'], $iphone_thumb['product']['detail_image']['width'], $iphone_thumb['product']['detail_image']['height']);
                    break;

                case 3:
                    $fileModel->resizeIPhoneThumb(APP_RESOURCE_ROOT . $file['file_path'], $iphone_thumb['promotion']['promoted_image']['width'], $iphone_thumb['promotion']['promoted_image']['height']);
                    break;

                case 4:
                    $fileModel->resizeIPhoneThumb(APP_RESOURCE_ROOT . $file['file_path'], $iphone_thumb['promotion']['list_image']['width'], $iphone_thumb['promotion']['list_image']['height']);
                    break;

                case 5:
                    $fileModel->resizeIPhoneThumb(APP_RESOURCE_ROOT . $file['file_path'], $iphone_thumb['promotion']['top_image']['width'], $iphone_thumb['promotion']['top_image']['height']);
                    break;
            }
        }
    }
}

?>