<?php

/**
 * @desc 家长相关功能页面
 * @author
 */

class ParentController extends AppController {
    
    var $studentModel = null;
    var $studentDetectionModel = null;
    var $articleModel = null;
    var $reportWeekModel = null;
    var $reportMonthModel = null;
    var $chartModel = null;
    var $fileModel = null;
    var $weichatPushMessageModel = null;
    var $parentModel = null;
    var $schoolParentModel = null;
    var $relationModel = null;
    var $smsModel = null;
    var $weichatBindModel = null;
    
    function ParentController() {
        $this->AppController();
        $this->studentModel = $this->getModel('Student');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
        $this->articleModel = $this->getModel('Article');
        $this->reportWeekModel = $this->getModel("ReportWeek");
        $this->reportMonthModel = $this->getModel("ReportMonth");
        $this->chartModel = $this->getModel("Chart");
        $this->fileModel = $this->getModel("File");
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
        $this->parentModel = $this->getModel('Parent');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->relationModel = $this->getModel('Relation');
        $this->smsModel = $this->getModel('Sms');
        $this->weichatBindModel = $this->getModel('WeichatBind');
    }

    /**
     * @desc 首页
     * @author
     */
    function indexAction() {
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $student_list = $this->studentModel->getStudentListByParentPhone($user['phone']);
        $this->view->assign('student_list', $student_list);
        $studentId = $this->get('studentId') ? $this->get('studentId') : $student_list[0]['id'];
        
        $id = $this->get('id');
        if($studentId){
            $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);
            $studentDetection = $this->studentDetectionModel->getDetection($student['id']);
            $student['studentDetection'] = $studentDetection;
            $studentDream = $this->parentModel->getStudentDreamList($student['dream_id']);
            $student['dream_id'] = $studentDream['name'];
            $studentHobbyList = $this->parentModel->getStudentHobbyList($student['hobby']);
            $hobby = "";
            foreach($studentHobbyList as $key => $val){
                if($key < 7){
                    $hobby .= $val['title'] . ',';
                }
            }
            $hobby = substr($hobby,0,strlen($hobby)-1);
            $student['hobby'] = $hobby;
            
        }
        
        
        $this->view->assign('student', $student);
        $this->view->layout();
    }
    /**
     * @desc 编辑资料
     * @author
     */
    function settingAction(){
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $studentId = $this->get('studentId');
        $id = $this->get('id');
        $form = $this->post('form');


        $form['studentId'] = $studentId;
        
        $studentHobbyList = $this->parentModel->getStudentHobbyList();
        $this->view->assign('studentHobbyList', $studentHobbyList);
        $studentDreamList = $this->parentModel->getStudentDreamList();
        $this->view->assign('studentDreamList', $studentDreamList);
        
        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);
        if($student){
            if($this->isComplete()){
                $data = array(
                    'birthday' => $form['birthday'] ? $form['birthday'] : $student['birthday'], 
                    'nickname' => $form['nickname'] ? $form['nickname'] : $student['nickname'],
                    'gender' => $form['gender'] ? $form['gender'] : $student['gender'],
                    'dream_id' => $form['dream_id'] ? $form['dream_id'] : $student['dream_id'],
                    'hobby' => $form['hobby'] ? implode(',',$form['hobby']) : $student['hobby'],
                    'id' => $student['id'],
                );
                $this->parentModel->updataStudentSetting($data);
                $this->redirect("?c=parent&a=index&studentId={$studentId}");
                
                $studentDream = $this->parentModel->getStudentDreamList($form['dream_id']);
                $form['dream_id'] = $studentDream['name'];
                //$this->view->assign('form', $form);
            }else{
                $form = $this->studentModel->getStudentByParentId($user['id'], $studentId);
                $studentDream = $this->parentModel->getStudentDreamList($form['dream_id']);
                $form['hobby'] = explode(',', $form['hobby']);
                $form['hobby_name'] = "";
                foreach($form['hobby'] as $val){
                    foreach($studentHobbyList as $vals){
                        if($val == $vals['id']){
                            $form['hobby_name'] .= $vals['title'] . ",";
                        }
                    }
                }
                $form['hobby_name'] = substr($form['hobby_name'],0,strlen($form['hobby_name'])-1);
                $form['dream_id'] = $studentDream['name'];
                
                $this->view->assign('form', $form);
            }
            
        }else{
            $studentDream = $this->parentModel->getStudentDreamList($form['dream_id']);
            $form['hobby'] = explode(',', $student['hobby']);
            $form['hobby_name'] = "";
            foreach($form['hobby'] as $val){
                foreach($studentHobbyList as $vals){
                    if($val == $vals['id']){
                        $form['hobby_name'] .= $vals['title'] . ",";
                    }
                }
            }
            $form['hobby_name'] = substr($form['hobby_name'],0,strlen($form['hobby_name'])-1);
            $form['dream_id'] = $studentDream['name'];
            $this->view->assign('form', $form);
        }
        
        $this->view->assign('student', $student);
        $this->view->layout();  
    }
    /**
     * @desc 宝贝梦想
     * @author
     */
    function dreamAction(){
        $user = $this->getSession(SESSION_USER);
        $id = $this->get('id');
        $studentId = $this->get('studentId');
        
        $studentDreamList = $this->parentModel->getStudentDreamList();
        
        $this->view->assign('studentDreamList', $studentDreamList);
        $this->view->layout();  
    }
    
    function ajaxStudentSettingAction(){
        $user = $this->getSession(SESSION_USER);
        $dream_id = $this->post('dream_id');
        
        $studentId = $this->get('studentId');
        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);
        if($student){
            $data = array(
                'birthday' => $student['birthday'], 
                'nickname' => $student['nickname'],
                'dream_id' => $dream_id ? $dream_id : $student['dream_id'],
                'hobby' => $student['hobby'],
                'id' => $student['id'],
            );
            if($this->parentModel->updataStudentSetting($data)){
                
                $studentDream = $this->parentModel->getStudentDreamList($dream_id);
                $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '修改成功', // code 不为 0 时的错误信息
                    'dream_name' => $studentDream['name'],
                    'dream_url' => APP_RESOURCE_URL.$studentDream['img_url'],
                );
            }else{
                $return = array(
                    'code' => 1, //  0 - 成功
                    'message' => '保存失败。', // code 不为 0 时的错误信息
                );
            }
            
            
        }else{
            $return = array(
                'code' => 1, //  0 - 成功
                'message' => '学生id错误。', // code 不为 0 时的错误信息
            );
        }
        echo json_encode($return);
        exit(); 
    }

    /**
     * @desc 报告页面
     * @author
     */
    function reportAction() {
        $wxUser = $this->getAccessWxUser();
        
        $user = $this->getSession(SESSION_USER);
        $user_type = $this->getSession(SESSION_ROLE);
        if((!$user) || ($user_type != ACT_PARENT_ROLE)) {
            //未登陆或者登录的不是家长时，自动登录家长角色
            if ($bind_parent = $this->weichatBindModel->getWeichatBindParent($wxUser['id'])) {
                if ($parent = $this->parentModel->getParentByPhone($bind_parent['phone'])) {
                    $this->setSession(SESSION_USER, $parent);
                    $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
                    $user = $parent;
                }
                else {
                    $this->redirect('?c=bind&a=index');
                    exit();
                }
            }
            else {
                $this->redirect('?c=bind&a=index');
                exit();
            }
        } 
        
        $student_list = $this->studentModel->getStudentListByParentPhone($user['phone']);
        
        $this->view->assign('student_list', $student_list);
        $studentId = $this->get('studentId') ? $this->get('studentId') : $student_list[0]['id'];
        
        $id = $this->get('id');
        $away_id = $this->get('away_id');
        $date = $this->get('day') ? $this->get('day') : date('Y-m-d', time());
        $type = $this->get('type');
         
        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);
        if($id){
            $detectionIdNone = $this->studentDetectionModel->getDetection($student['id'], $id);
            $date = $detectionIdNone['created'];
        }
        if($away_id){
           $studentAway = $this->studentModel->getStudentAway($away_id);
           $date = $studentAway['created'];
        }
        $student['type'] = $type;
        $student['away_id'] = $away_id;
        $student['dete_id'] = $id;
        
        $studentDreamList = $this->parentModel->getStudentDreamList();
        $this->view->assign('studentDreamList', $studentDreamList);
        
        switch ($type){
            case REPORT_TYPE_DAY:
                if($student) {
                    $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id'], $id);
                    
                    $detectionList= $this->studentDetectionModel->getDetectionNoStatusList($studentId, $date, $id);
                    foreach($detectionList as $key=>$detection){
                            
                        $studentDetectionImg = $this->fileModel->getFile($detection['file_img_id']);
                        $detectionStatus = $this->studentDetectionModel->getDetectionStatus($studentId, $detection['created']);
                        
                        $detectionList[$key]['studentDetectionImg'] = $studentDetectionImg;
                        $detectionList[$key]['detectionStatus'] = $detectionStatus;
                    }
                    
                    $date = date('Y-m-d', strtotime($date));
                    $student['detectionList'] = $detectionList;
                    $student['day'] = $date;
                    $student['detectionStatus'] = $detectionStatus;
                    $grade = $this->calculateGrade($student['class_start']);
                    $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_DAY);
                    $student['articleList'] =  $articleList;
                }
                break;

            case REPORT_TYPE_MONTH:
                if($student) {

                    $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id'], $id);

                    $student['studentDetectionImg'] = $this->studentModel->getStudentLatestDetectionImg($student['id']);

                    $start = date('Y-m-01', strtotime($date));
                    $end = date('Y-m-d', strtotime("$start +1 month -1 day"));
                    $report = $this->reportMonthModel->getReportMonth($student['id'], $start, $end);
                    if(!$report){
                        $report = $this->reportMonthModel->getReportMonthXin($student['id']);
                    }
                    $student['report'] = $report;
                    $detectionList = $this->studentDetectionModel->getDetectionList($student['id'], $report['start'], $report['end']); 
                    
                    $student['day'] = date("Y-m", strtotime($report['start']));
                    $student['detectionList'] =  $detectionList;

                    $grade = $this->calculateGrade($student['class_start']);

                    $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_MONTH);
                    $student['articleList'] =  $articleList;
                }
                break;
                
            case REPORT_TYPE_SHUTTLE:
                    $awayNotice = array();
                    $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id'], $id);
                    $rs = $this->studentModel->getStudentAway($away_id, $student['id'], $date);
// new
                    $reImg = $this->fileModel->getFile($rs['file_img_id']);
                    $rsSub_img = $this->fileModel->getFile($rs['file_sub_img_id']);
// org
                    $awayNotice['time'] = $rs['created'] ? date('Y-m-d H:i',strtotime($rs['created'])) : '';
                    $awayNotice['img'] = $rs['img'];
                    $awayNotice['reImg'] = $reImg['file_path']; // new
                    $awayNotice['sub_img'] = $rs['sub_img'];
                    $awayNotice['reSub_img'] = $rsSub_img['file_path'];  // new
                    
                    $student_s = $this->studentModel->getStudent($rs['student_id']);
                    $awayNotice['student'] = $student_s['name'];
                    
                    $parent = $this->schoolParentModel->getParentRel($rs['student_id'], $rs['parent_id']);
                    $awayNotice['parent'] = $parent['relation_title'];
                    
                    $grade = $this->calculateGrade($student_s['start']);
                    
                    $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_DAY);
                    $student['articleList'] =  $articleList;
                    $student['awayNotice'] =  $awayNotice;
                    $student['day'] = $date;
                    $this->log(print_r($awayNotice,true),'test');
                break;
                
            default:
                break;
        }
        $studentDream = $this->parentModel->getStudentDreamList($student['dream_id']);
        $student['dream_name'] = $studentDream['name'];
        $student['dream_url'] = $studentDream['img_url'];
  
        $this->view->assign('student', $student);
        
        $this->view->layout();
    }

    function countReadingNumAjaxAction(){
        $articleId = $this->post('articleId'); 
        $this->articleModel->updateReadingNum($articleId);
        echo json_encode(array('code' => 0,'message' => '统计成功'));
        exit();  
    }



    function videoIndexAction(){
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $student_list = $this->studentModel->getStudentListByParentPhone($user['phone']);
        $studentId = $this->get('studentId') ? $this->get('studentId') : $student_list[0]['id'];
        $this->view->assign('studentId', $studentId);
        $this->view->layout();
    }

    function videoDetailsAction(){
        $user = $this->getSession(SESSION_USER);
        $channelId = $this->get('channelId');
        $studentId = $this->get('studentId');
        $os = $this->isMobile();
        $this->view->assign('os',$os);
        $opendId = $this->parentModel->getOpenIdByParentPhone($user['phone']);
        $this->view->assign('channelId', $channelId); 
        $this->view->assign('opendId',$opendId['openid']);
        $this->view->assign('studentId', $studentId);
        $this->view->layout();
    }
}
?>
