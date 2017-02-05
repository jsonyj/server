<?php

class ActivityController extends AppController {

	var $activityModel = null;
    var $ossClient = null;
    var $bucket = null;

	function ActivityController() {
        $this->AppController();
        $this->activityModel = $this->getModel('Activity');
        $this->ossClient = $this->getOssClient();
        $this->bucket = $this->code('oss.default.bucket');
    }
	function indexAction() {
		$activityList = $this->activityModel->getActivityList();
		$this->view->assign("activityList",$activityList);
        $this->view->layout();
    }


    function inputAction(){
        $id = $this->get('id');
        $form = $this->post('form');
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->activityModel->validSaveActivity($form, $errors)) {
                if ($id = $this->activityModel->saveActivity($form)) {
                    $this->redirect('?c=activity&a=input&id='.$id, true, '保存成功');
                    exit;
                }
                $this->redirect('?c=activity&a=index', true, '保存失败');
                exit;
            }else {
                $this->view->assign('form', $form);
                $this->view->assign('errors', $errors);
            }
        }else{
            $activity_info = $this->activityModel->getActivityById($id);
            $this->view->assign('form', $activity_info);
        }
        $this->view->layout();


    }

    function deleteAction() {
        $id = $this->get('id');
        $this->activityModel->deleteActivity($id);
        $this->redirect("?c=activity&a=index", true, '删除成功');
    }

    function giftIndexAction(){
		$gift_list = $this->activityModel->getGiftList();
		$this->view->assign('gift_list',$gift_list);
		$this->view->layout();
    }

    function giftInputAction() {
        $id = $this->get('id');
        $form = $this->post('form');
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->activityModel->validGift($form, $errors)) {
                if ($id = $this->activityModel->saveGift($form)) {
                    $this->redirect('?c=activity&a=giftInput&id='.$id, true, '保存成功');
                    exit;
                }
                $this->redirect('?c=activity&a=giftIndex', true, '保存失败');
                exit;
            }else {
                $this->view->assign('form', $form);
                $this->view->assign('errors', $errors);
            }
        }else{
            $gift_info = $this->activityModel->getGiftById($id);
            $this->view->assign('form', $gift_info);
        }
        $this->view->layout();

    }


    function imgListOneAction() {
        $id = $this->get('id');
        $form = $this->post('form');
        $img_one = $this->activityModel->getGiftById($id);
        $this->log(print_r($img_one,true),'log3');
        $this->view->assign('form', $img_one);
        $this->view->layout();
    }

    function uploadAction() {
        $id = $this->get('id');
        $uploadConfig = $this->code('upload.gift');
        $configKey = 'gift[file]';
        $postKey = 'gift';
        $uploadConfig[$configKey]['dir'] = "{$id}";
        $fileNameArray = explode('.',$_FILES['gift']['name']['file']);
        $object = $postKey . '/' . $uploadConfig[$configKey]['dir'] . '/' . md5($fileNameArray['0'] . time()) . '.' . $fileNameArray['1'];
        
        $uploadFile = $this->ossClient->uploadFile($this->bucket, $object, $_FILES['gift']['tmp_name']['file']);
        if (!empty($uploadFile)) {
                $file = array(
                    'gift_img' => $object, //保存的路径   new
                );
                $this->activityModel->saveGiftImg($file, $id);

        }else {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-type: application/json');
            print json_encode(array('error' => implode(' ', $errors)));
        }
    }


    function deleteGiftAction() {
        $id = $this->get('id');
        if ($this->activityModel->deleteGift($id)) {
            $this->redirect('?c=activity&a=giftIndex', true, '删除成功');
             exit;
        }
        $this->redirect('?c=activity&a=giftIndex', true, '删除失败');
        exit;
    }

    function deleteGiftImgAction() {
        $id = $this->get('id');
        $dream = $this->activityModel->getActivityById($id);
        $file_path = APP_RESOURCE_ROOT . $dream['img_url'];
        @unlink($file_path);
        $this->dreamModel->deleteGiftImg($id);
    }



}
?>