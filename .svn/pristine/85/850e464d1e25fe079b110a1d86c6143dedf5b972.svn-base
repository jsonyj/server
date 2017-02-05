<?php


class UserController extends AppController {

    var $parentModel = null;
    var $studentModel = null;
    var $studentDetectionModel = null;
    var $fileModel = null;

    function UserController() {
        $this->AppController();
        $this->parentModel = $this->getModel('Parent');
        $this->studentModel = $this->getModel('Student');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
        $this->fileModel = $this->getModel('File');
    }

    function indexAction() {
        $wxUser = $this->getAccessWxUser();

        $parent = $this->parentModel->getParentByWeichatId($wxUser['id']);
        $parent['studentList'] = $this->studentModel->getStudentListByParentPhone($parent['phone']);

        foreach ( $parent['studentList'] as $key => $value ) {
            $detection = $this->studentDetectionModel->getDetection($value['id']);
            $parent['studentList'][$key]['studentDetection'] = $detection;
//             $parent['studentList'][$key]['studentDetectionImg'] = $this->studentModel->getStudentLatestDetectionImg($value['id']);
            $parent['studentList'][$key]['studentDetectionImg'] = $this->fileModel->getFile($detection['file_img_id']);
        }

        $this->view->assign('parent', $parent);

        $this->view->layout();
    }

    function groupListAction() {
        $this->view->layout();
    }
}
?>
