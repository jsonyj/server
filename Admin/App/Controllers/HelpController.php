<?php

class HelpController extends AppController {

    var $helpModel = null;
    var $ossClient = null;
    var $bucket = null;

    function HelpController() {
        $this->AppController();
        $this->helpModel = $this->getModel('Help');
        $this->ossClient = $this->getOssClient();
        $this->bucket = $this->code('oss.default.bucket');
    }

    function indexAction() {
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);

        $help_list = $this->helpModel->getHelpList($sh);
        $this->view->assign('paging', $this->helpModel->paging);
        $this->view->assign('help_list', $help_list);
        $this->view->layout();
    }

    function inputAction() {

         $id = $this->get('id');
         $form = $this->post('form');

         if ($this->isComplete()) {
             $form['id'] = $id;
             if ($this->helpModel->validHelp($form, $errors)) {
                 $helpWeight = $this->helpModel->getHelpByWeight($form['weight']);
                 if($helpWeight){
                     $maxWeight = $this->helpModel->getHelpMaxWeight();
                     pr($maxWeight);
                     $maxWeight = $maxWeight + 1;
                     $this->helpModel->updateHelpWeight($helpWeight['id'], $maxWeight);
                 }
                 
                 if ($rs = $this->helpModel->saveHelp($form)) {
                     $this->redirect("?c=help&a=input&pc=help&pa=index&id={$rs}", true, '保存成功');
                     exit;
                 }

                 $this->redirect("?c=help&a=input&pc=help&pa=index&id={$id}", true, '保存失败');
                 exit;
             }
             else {
                 $this->view->assign('errors', $errors);
                 $this->view->assign('form', $form);
             }
         } else {
             $form = $this->helpModel->getHelpById($id);
             if($id && empty($form)) {
                  $this->redirect("?c=help&a=index", true, $this->lang('ID不存在'));
                  exit();
             }
             $this->view->assign('form', $form);
         }

         $this->view->layout();
     }

    function deletedAction() {
        $id = $this->get('id');
        if ($this->helpModel->deleteHelp($id)) {
            $this->redirect('?c=help&a=index', true, '删除成功');
        }
        $this->redirect('?c=help&a=index', true, '删除失败');
    }
    
    /**
     * @desc 上传图片
     * @author ly
     */
    function uploadAction() {
        $id = $this->get('id');
        $uploadConfig = $this->code('upload.help');
        $configKey = 'help[file]';
        $postKey = 'help';
        $uploadConfig[$configKey]['dir'] = "{$id}";
        // new 
        $fileNameArray = explode('.',$_FILES['help']['name']['file']);
        $object = $postKey . '/' . $uploadConfig[$configKey]['dir'] . '/' . md5($fileNameArray['0'] . time()) . '.' . $fileNameArray['1'];
        $uploadFile = $this->ossClient->uploadFile($this->bucket, $object, $_FILES['help']['tmp_name']['file']);
        // org
        // $upload = $this->load(EXTEND, 'BraveUpload');
        // $upload->upload($uploadConfig);
        // $uploadFile = $this->post($postKey);
        if (!empty($uploadFile)) {
            // if ($this->helpModel->validFileUpload($uploadFile, $errors)) {   不验证
                $iconImg = $object; //保存的路径 new
                // $iconImg = $uploadConfig[$configKey]['uri'] . '/' .$uploadFile['file_info']['save']; //保存的路径 org
                $this->helpModel->saveHelpIconImg($iconImg, $id);
            // } else {
            //     header('HTTP/1.1 500 Internal Server Error');
            //     header('Content-type: application/json');
            //     print json_encode(array('error' => implode(' ', $errors)));
            // }
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-type: application/json');
            print json_encode(array('error' => implode(' ', $errors)));
        }
    }
    
    /**
     * @desc 根据id获取图片
     * @author ly
     */
    function getHelpImgAction() {
        $id = $this->get('id');
        $file = $this->helpModel->getHelpById($id);
        if(!empty($file['icon_img'])) {
            $files[] = array('id' => $file['id'], 'name' => $file['name'], 'size' => $file['dream_size'], 'url' => APP_RESOURCE_URL . $file['icon_img']);
            print json_encode($files);
            exit();
        }
        exit();
    }
    
    /**
     * @desc 删除图片
     * @author ly
     */
    function deleteHelpImgAction() {
        $id = $this->get('id');
        $help = $this->helpModel->getHelpById($id);
        $file_path = APP_RESOURCE_ROOT . $help['icon_img'];
        @unlink($file_path);
        $this->helpModel->deleteHelpImg($id);
    }

}

?>
