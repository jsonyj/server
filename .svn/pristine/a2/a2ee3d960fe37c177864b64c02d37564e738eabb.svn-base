<?php

/**
 * @desc 帮助相关页面
 * @author
 */

class ChristmasController extends AppController {

    var $studentModel = null;
    var $christmasModel = null;


    function ChristmasController(){
    	$this->AppController();
    	$this->studentModel = $this->getModel('Student');
    	$this->christmasModel = $this->getModel('Christmas');
    }
    function indexAction(){
    	$user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $student_list = $this->studentModel->getStudentListByParentPhone($user['phone']);
        // $this->log(print_r($student_list,true),'log10');
        $studentId = $this->get('studentId') ? $this->get('studentId') : $student_list[0]['id'];
        $studentInfor = $this->studentModel->getStudent($studentId);
        $gettingGiftNum = $this->christmasModel->getGettingGiftNum($studentId);
        $getGivingNum = $this->christmasModel->getGivingNum($studentId);
        $this->view->assign('getGivingNum',$getGivingNum);
        $this->view->assign('studentInfor',$studentInfor);
        $this->view->assign('gettingGiftNum',$gettingGiftNum);
        $this->view->layout();
    }


    /*收到礼物*/

    function gettingGiftAction(){
        $studentId = $this->get('studentId');
        $classId = $this->get('classId');
        $gettingGiftNum = $this->christmasModel->getGettingGiftNum($studentId);
        $gettingGiftList = $this->christmasModel->getGettingGift($studentId);
        $this->view->assign('gettingGiftList',$gettingGiftList);
        $this->view->assign('studentId',$studentId);
        $this->view->assign('classId',$classId);
        $getGivingNum = $this->christmasModel->getGivingNum($studentId);
        $this->view->assign('getGivingNum',$getGivingNum);
        $this->view->assign('gettingGiftNum',$gettingGiftNum);
        $this->view->layout();
    }

    /*送礼物*/
    function givingGiftAction(){
        /*礼物列表*/
        $type = $this->get('type');
        $studentId = $this->get('studentId');
        $classId = $this->get('classId');
        $returnStudentId = $this->get('returnStudentId');
        $giftList = $this->christmasModel->getGift();
        $this->view->assign('giftList',$giftList);
        /*学生选择*/
        if($returnStudentId){
            $returnStudentList = $this->studentModel->getReturnStudentId($returnStudentId);
            $this->view->assign('returnStudentList',$returnStudentList);
        }else{
            $classmatesList = $this->studentModel->getClassmatesList($classId,$studentId);
            $this->view->assign('classmatesList',$classmatesList);
        }
        $this->view->assign('type',$type);
        $this->view->assign('studentId',$studentId);
        $this->view->assign('classId',$classId);
        $this->view->layout();
    }


    function ajaxGivingGiftAction(){
        $form = $this->post('form');
        $classmatesInfo= $form['classmates_info'];
        $giftsInfo = $form['gifts_info'];
        $studentId = $form['student_id'];
        $studentInfor = $this->studentModel->getStudent($studentId);
        /*赠送的人数*/
        $dataGivingNum = array(
                'activity_id' => '',
                'giving_studentId' => $studentId,
                'classmatesNum' => count($classmatesInfo)*count($giftsInfo),
            );
        $this->christmasModel->saveGivingNum($dataGivingNum);

        foreach ($classmatesInfo as $classmate_info) {
            $classmatesInfoArr = explode(',',$classmate_info); 
            /*获取的礼物数量*/
            $dataGettingNum = array(
                    'activity_id' => '',
                    'getting_studentId' => $classmatesInfoArr[0],
                    'giftsNum' => count($giftsInfo),
                );
            $this->christmasModel->saveGettingNum($dataGettingNum);

            foreach ($giftsInfo as $gift_info) {
                $giftsInfoArr = explode(',', $gift_info);
                $data = array(
                        'getting_studentId' => $classmatesInfoArr[0],
                        'getting_studentName' => $classmatesInfoArr[1],
                        'giving_studentId' => $studentId,
                        'giving_studentName' => $studentInfor['name'],
                        'giving_studentImg' => $studentInfor['org_img_url'],
                        'gift_id' => $giftsInfoArr[0],
                        'gift_name' => $giftsInfoArr[1],
                        'gift_img' => $giftsInfoArr[2],
                        'activity_id' => '', 
                        'activity_name' => '',
                    );
                $this->christmasModel->saveGivingGift($data);
            }
            
        }
        $return = array(
                'code' => 0, //  0 - 成功
                'message' => '赠送成功。', // code 不为 0 时的错误信息
            );
        echo json_encode($return);
        exit();
    }


    function givingSuccessAction(){
        $classId = $this->get('classId');
        $studentId = $this->get('studentId');
        $classId = $this->get('classId');
        $this->view->assign('classId',$classId);
        $this->view->assign('studentId',$studentId);
        $getGivingNum = $this->christmasModel->getGivingNum($studentId);
        $this->view->assign('getGivingNum',$getGivingNum);
        $this->view->layout();
    }
}

?>