<?php


class ClaimController extends AppController {

    var $claimModel = null;
    var $studentDetectionModel = null;
    var $studentModel = null;
    var $fileModel = null;
    var $classModel = null;
    var $weichatPushMessageModel = null;

    function ClaimController() {
        $this->AppController();
        $this->claimModel = $this->getModel('Claim');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
        $this->studentModel = $this->getModel('Student');
        $this->fileModel = $this->getModel('File');
        $this->classModel = $this->getModel('Class');
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
    }

    /**
     * @desc 领取页
     * @author ly
     */
    function indexAction() {
        $studentId = $this->get('id');
        $day = $this->get('day');

        $student = $this->studentModel->getStudent($studentId);
        $this->view->assign("student", $student);
        
        $detectionClaimList = $this->claimModel->getDetectionClaimList($student['school_id'], $day);
        
        $this->view->assign("detectionClaimList", $detectionClaimList);
        
        $this->view->assign('day', $day);
        $this->view->layout();
    }
    
    /**
     * @desc 老师领取页
     * @author ly
     */
    function teacherDetectionAction() {
        $user = $this->getSession(SESSION_USER);

        $detectionClaimSchoolList = $this->claimModel->getDetectionClaimSchoolList($user['school_id']);
        $this->view->assign("detectionClaimSchoolList", $detectionClaimSchoolList);
        
        $classList = $this->classModel->getClassSelectList($user['school_id']);
        $this->view->assign('classList', $classList);
        $this->view->layout();
    }
    
    /**
     * @desc 根据学校和班级获取所有学生成下拉框html
     * @author ly
     */
    function selectStudentAction() {
        $user = $this->getSession(SESSION_USER);
        $class_id = $this->get('class_id');

        $students = $this->studentModel->getStudents($user['school_id'], $class_id);
    
        $html = '';
        foreach($students as $student) {
            $html .= "<option value=\"{$student['value']}\">{$student['name']}</option>";
        }
    
        echo $html;
        exit();
    }
    
    /**
     * @desc 退回保存数据
     * @author ly
     */
    function claimReturnAjaxAction(){
        $form = $this->post('form');
        $user = $this->getSession(SESSION_USER);
        $userRole = $this->getSession(SESSION_ROLE);
        
        $form['day'] = date('Y-m-d', strtotime($form['day']));
        $form['op_uid'] = $user['id'];
        $form['op_utype'] = $userRole;
        
        $student = $this->studentModel->getStudent($form['student_id']);
        $form['school_id'] = $student['school_id'];
        
        if($userRole == ACT_PARENT_ROLE){
            if($rs = $this->claimModel->saveClaim($form)){
                $this->studentDetectionModel->updateStudentDetectionStatus($form['detection_id']);
                
                if($form['first'] == 1){
                    $this->studentDetectionModel->updateDetectionFirst($form['detection_id'], 0);
                    if($studentDetectionFirst = $this->studentDetectionModel->getStudentDetectionAsc($form['student_id'],$form['day'])){
                        $this->studentDetectionModel->updateDetectionFirst($studentDetectionFirst['id'], 1);
                    }
                }
                
                if ($form['lastest'] == 1){
                    $this->studentDetectionModel->updateDetectionLastest($form['detection_id'], 0);
                    if($studentDetectionLastest = $this->studentDetectionModel->getStudentDetectionDesc($form['student_id'],$form['day'])){
                        $this->studentDetectionModel->updateDetectionLastest($studentDetectionLastest['id'], 1);
                    }
                }
                
                echo json_encode(array('code' => 0,'data' => $rs));
                exit;
            }else{
                echo json_encode(array('code' => 1,'errors' => '保存失败。'));
                exit;
            }
        }else{
            echo json_encode(array('code' => 1,'errors' => '保存失败。必须是家长才能更换照片'));
            exit;
        }
    }
    
    /**
     * @desc 领取保存信息
     * @author ly
     */
    function claimReceiveAjaxAction(){
        $form = $this->post('form');
        $user = $this->getSession(SESSION_USER);
        $userRole = $this->getSession(SESSION_ROLE);
        
        if($userRole == ACT_PARENT_ROLE || $userRole == ACT_SCHOOL_TEACHER){
            $claim = $this->claimModel->getClaim($form['id']);
            $data = array(
                'detection_id' => $claim['detection_id'],
                'terminal_img_id' => $claim['terminal_img_id'],
                'school_id' => $claim['school_id'],
                'student_id' => $form['student_id'],
                'type' => DETECTION_STATUS_NORMAL,
                'op_uid' => $user['id'],
                'op_utype' => $userRole,
            );
        
            if($rs = $this->claimModel->saveClaim($data)){
                $this->claimModel->updateDetectionClaimStatus($form['id']);
                $this->studentDetectionModel->updateStudentDetectionStudentIdAndStatus($claim['detection_id'], $form['student_id']);
                
                $checked = $this->studentDetectionModel->getDetectionById($claim['detection_id']);
                
                if($userRole == ACT_SCHOOL_TEACHER){
                    $form['day'] = date('Y-m-d', strtotime($checked['created']));
                }
                
                if(!$first = $this->studentDetectionModel->getStudentDetectionByFirst($form['student_id'], $form['day'])){
                    $this->studentDetectionModel->updateDetectionFirst($claim['detection_id'], 1);
                }elseif ($first['created'] > $checked['created']){
                    $this->studentDetectionModel->updateDetectionFirst($claim['detection_id'], 1);
                    $this->studentDetectionModel->updateDetectionFirst($first['id'], 0);
                }elseif($first['created'] <= $checked['created']) {
                    $this->studentDetectionModel->updateDetectionFirst($claim['detection_id'], 0);
                }
                
                if(!$lastest = $this->studentDetectionModel->getStudentDetectionByLastest($form['student_id'], $form['day'])){
                    $this->studentDetectionModel->updateDetectionLastest($claim['detection_id'], 1);
                }elseif ($lastest['created'] < $checked['created']){
                    $this->studentDetectionModel->updateDetectionLastest($claim['detection_id'], 1);
                    $this->studentDetectionModel->updateDetectionLastest($lastest['id'], 0);
                }elseif ($lastest['created'] >= $checked['created']){
                    $this->studentDetectionModel->updateDetectionLastest($claim['detection_id'], 0);
                }
                
                $studentDetectionId =  $this->studentModel->getStudentDetectionId($form['student_id'], $claim['detection_id']);
                $studentSchool = $this->studentModel->getStudentSchool($form['student_id']);
                if($studentDetectionId){
                    //保存推送信息
                    $parentList = $this->studentModel->getStudentParentList($form['student_id']);
                    $student_info = $this->studentModel->getStudent($form['student_id']);
                    foreach($parentList as $parent) {
                        if($parent['openid']) {
                            $wei_form = array(
                                'open_id' => $parent['openid'],
                                'day_report_id' => $studentDetectionId['id'],
                                'detection' => $studentDetectionId,
                                'school_title' => $studentSchool['school_title'],
                                'studentId' => $form['student_id'],
                                'student' => $student_info,
                                'send_time' => NOW,
                            );

                            $wei_form['message'] = $this->weichatPushMessageModel->genDayReportMessage($wei_form);
                            $this->weichatPushMessageModel->saveMessage($wei_form);
                        }
                        
                    }
                    
                }
                
                
                $this->fileModel->updateFileByUsageId($claim['file_img_id'], $form['student_id']);
                
                echo json_encode(array('code' => 0,'data' => $rs));
                exit;
            }else{
                echo json_encode(array('code' => 1,'errors' => '保存失败。'));
                exit;
            }
        }else{
            echo json_encode(array('code' => 1,'errors' => '保存失败。必须是家长才能更换照片'));
            exit;
        }
    }

}
?>
