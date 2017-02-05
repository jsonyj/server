<?php

class VersionController extends AppController {

    var $versionModel = null;
    var $ossClient = null;
    var $bucket = null;

    function VersionController() {
        $this->AppController();
        $this->versionModel = $this->getModel('Version');
        $this->ossClient = $this->getOssClient();
        $this->bucket = $this->code('oss.default.bucket');
    }
//     
    function indexAction(){
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $versionList = $this->versionModel->getVersionList($sh);
        $this->view->assign('paging', $this->versionModel->paging);
        $this->view->assign('versionList', $versionList);
        $this->view->layout();
        
    }
// 新增上传，编辑上传
    function inputAction(){
        $id = $this->get('id');
        $request = $this->post('form');
        $branchList = $this->versionModel->getBranch2($sh);
        $this->view->assign('branchList', $branchList);
        if ($this->isComplete()) {
            if($request['branch_id']>0){
                $object_dir = 'version/branch/';
            }else{
                $object_dir = 'version/main/';
            }
            $fileName = md5_file($_FILES['file']['tmp_name']);
            $fileNameArray = explode('.',$_FILES['file']['name']);
            if(isset($fileNameArray['2'])){
                $object_fileName = $fileName . '.' . $fileNameArray['1'] . '.' . $fileNameArray['2'];
            }else{
                $object_fileName = $fileName . '.' . $fileNameArray['1'];
            }
            $object = $object_dir . $object_fileName;
            $filePath = $_FILES['file']['tmp_name'];
            $this->ossClient->uploadFile($this->bucket, $object, $filePath);
            
            $form = array(
                "id" => $request['id'],
                "branch_id" => $request['branch_id'],
                "model_no" => $request['model_no'],
                "version_des" => $request['version_des'],
                "device_type" => $request['device_type'],
                );
            if($form['branch_id'] > 0){
                $branch_url = $object;
                $form['version_url'] = $branch_url;
                $form['branch_version'] = $request['version_no'];
            }
            else{
                $main_url = $object;
                $form['version_url'] = $main_url;
                $form['main_version'] = $request['version_no'];
            }
            if ($id > 0) {
                $form['id'] = $id;
                $this->view->assign('form', $form);
                if($this->versionModel -> insertVerion($form)){
                    $this->redirect("?c=version&a=index", true, '上传成功');
                    exit;
                }
            }else{
                if($this->versionModel -> insertVerion($form)){
                    $this->redirect("?c=version&a=index", true, '上传成功');
                    exit;
                }
            }
        }
        else
        {

            $form = $this->versionModel -> getVersion($id);
            $this->view->assign('form', $form);
        }
        $this->view->layout();
    }
// 删除
    function deleteAction(){
        $id = $this->get('id');
        $this->versionModel->deteleVersion($id);
        $this->redirect("?c=version&a=index", true, '删除成功');
    }


//***结束分割线***//
}