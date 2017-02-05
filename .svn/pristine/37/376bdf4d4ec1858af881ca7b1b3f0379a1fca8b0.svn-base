<?php

class BranchController extends AppController {

    var $versionModel = null;

    function BranchController() {
        $this->AppController();
        $this->versionModel = $this->getModel('Version');
    }
    // 群列表
    function indexAction(){
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $branchList = $this->versionModel->getBranch($sh);
        $this->view->assign('paging', $this->versionModel->paging);
        $this->view->assign('branchList', $branchList);
        $this->view->layout();
    }
    // 增加&修改群
    function inputAction(){
    	$id = $this->get('id');
        $request = $this->post('form');
        if ($this->isComplete()) {
            
            $form = array(
                "id" => $request['id'],
                'branch_name' => $request['branch_name'],
	            'branch_no' => $request['branch_no'],
	            'branch_des' => $request['branch_des'] ,
                );
            if ($id > 0) {
                $form['id'] = $id;
                $this->view->assign('form', $form);
                if($this->versionModel -> saveBranch($form)){
                    $this->redirect("?c=branch&a=index", true, '上传成功');
                    exit;
                }
            }else{
                if($this->versionModel -> saveBranch($form)){
                    $this->redirect("?c=branch&a=index", true, '上传成功');
                    exit;
                }
            }
        }else{
            $form = $this->versionModel -> getBranch3($id);
            $this->view->assign('form', $form);
        }
        $this->view->layout();
    }
    // 删除群
    function deleteAction(){
        $id = $this->get('id');
        $this->versionModel->deteleBranch($id);
        $this->redirect("?c=branch&a=index", true, '删除成功');
    }
    /**结束分割**/
}