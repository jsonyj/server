<?php

class SchoolController extends AppController {

    var $schoolModel = null;
    var $staffModel = null;
    var $schoolParentModel = null;
    var $weichatPushMessageModel = null;
    
    function SchoolController() {
        $this->AppController();
        $this->schoolModel = $this->getModel('School');
        $this->staffModel = $this->getModel('Staff');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->studentModel = $this->getModel('Student');
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
    }


    /**
     * @desc 学校学生统计首页
     * @desc 园长角色
     * @author ly
     */
    function schoolStatisticAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);

        $sh = $this->get('sh');
        if (!$date = $sh['date']) {
            $date = date('Y-m-d');
            $sh['date'] = $date;
        }
        $this->view->assign('sh', $sh);

        if($role == ACT_SCHOOL_HEADMASTER){
            $dayArriveSchoolNum = $this->schoolModel->getDayArriveSchoolStatistic($user['school_id'], $date);
            $this->view->assign('dayArriveSchoolNum', $dayArriveSchoolNum);

            $dayLeaveSchoolNum = $this->schoolModel->getDayLeaveSchoolStatistic($user['school_id'], $date);
            $this->view->assign('dayLeaveSchoolNum', $dayLeaveSchoolNum);

            $this->view->assign('date', $date);
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }

        $this->view->assign('loginUser', $user);
        $this->view->layout();
    }

    /**
     * @desc 当日签到表统计
     * @author ly
     */
    function schoolDaySignStatisticAction(){
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $sh = $this->get('sh');
        if (!$date = $sh['date']) {
            $date = date('Y-m-d');
            $sh['date'] = $date;
        }
        $this->view->assign('sh', $sh);
        
        if($role == ACT_SCHOOL_HEADMASTER){
            $daySignStatisticList = $this->schoolModel->getDaySignStatisticList($user['school_id'], strtotime($date));
            $this->view->assign('daySignStatisticList', $daySignStatisticList);
            $this->view->assign('date', $date);
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }
        $this->view->layout();
    }

    /**
     * @desc 本月签到表统计
     * @author ly
     */
    function schoolMonthSignStatisticAction(){
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $sh = $this->get('sh');
        if (!$date = $sh['date']) {
            $date = date('Y-m');
            $sh['date'] = $date;
        }
        $this->view->assign('sh', $sh);
        
        if($role == ACT_SCHOOL_HEADMASTER){
            $staffList = $this->schoolModel->getStaffList($user['school_id']);
            
            $monthSignStatisticList = array();
            $monthSignStatisticList = $staffList;
            foreach ($staffList as $key=>$value) {
                $monthSignList = $this->schoolModel->getMonthSignStatisticList($user['school_id'],$value['id'], $date);
                
                foreach ($monthSignList as $v) {
                    if(in_array($v['sign_status'], array(SIGN_STATUS_LATE_UNOUT,SIGN_STATUS_LATE_OUT,SIGN_STATUS_LATE_EARLY))){
                        $monthSignStatisticList[$key]['monthLateNum'] = $v['num'];
                    }
                    
                    if(in_array($v['sign_status'], array(SIGN_STATUS_IN_EARLY,SIGN_STATUS_LATE_EARLY))){
                        $monthSignStatisticList[$key]['monthEarlyNum'] = $v['num'];
                    }
                    
                    if(in_array($v['sign_status'], array(SIGN_STATUS_UNIN_UNOUT))){
                        $monthSignStatisticList[$key]['monthUninNum'] = $v['num'];
                    }
                    
                    if(in_array($v['sign_status'], array(SIGN_STATUS_IN_UNOUT,SIGN_STATUS_LATE_UNOUT))){
                        if($date == date('Y-m')){
                            $monthSignStatisticList[$key]['monthUnoutNum'] = $v['num'] - 1;
                        }else{
                            $monthSignStatisticList[$key]['monthUnoutNum'] = $v['num'];
                        }
                    }
                }
            }
            $this->view->assign('monthSignStatisticList', $monthSignStatisticList);
            $this->view->assign('date', $date);
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }
        $this->view->layout();
    }

    /**
     * @desc 班级学生到校统计详情
     * @author ly
     */
    function classArriveStatisticAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);

        if (!$date = $this->get('date')) {
            $date = date('Y-m-d');
        }

        if($role == ACT_SCHOOL_HEADMASTER || $role == ACT_SCHOOL_SUPPORTER || $role ==ACT_SCHOOL_DOCTOR){
            $classArriveSchoolNumList = $this->schoolModel->getClassArriveSchoolNumList($user['school_id'], $date);

            foreach ($classArriveSchoolNumList as $key => $value) {
                $classArriveStudentList = $this->schoolModel->getClassArriveStudentList(array('school_id' => $user['school_id'], 'class_id' => $value['class_id']), $date);
                $studentsMum = $this->schoolModel->getStudentsNum($value['class_id']);
                $classArriveSchoolNumList[$key]['mum'] = $studentsMum['0']['mum'];
                $classArriveSchoolNumList[$key]['classArriveStudentList'] = $classArriveStudentList;
            }

            $this->view->assign('classArriveSchoolNumList', $classArriveSchoolNumList);
            $this->view->assign('date', $date);

        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }

        $this->view->layout();
    }

    /**
     * @desc 班级学生离校统计详情
     * @author ly
     */
    function classLeaveStatisticAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);

        if (!$date = $this->get('date')) {
            $date = date('Y-m-d');
        }

        if($role == ACT_SCHOOL_HEADMASTER || $role == ACT_SCHOOL_SUPPORTER){
            $classLeaveSchoolNumList = $this->schoolModel->getClassLeaveSchoolNumList($user['school_id'], $date);

            foreach ($classLeaveSchoolNumList as $key => $value) {
                $classLeaveStudentList = $this->schoolModel->getClassLeaveStudentList(array('school_id' => $user['school_id'], 'class_id' => $value['class_id']), $date);
                $classLeaveSchoolNumList[$key]['classLeaveStudentList'] = $classLeaveStudentList;
            }

            $this->view->assign('classLeaveSchoolNumList', $classLeaveSchoolNumList);

            $this->view->assign('date', $date);
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }

        $this->view->layout();
    }
    
    /**
     * @desc 某个班级学生到、离校统计首页
     * @desc 老师角色
     * @author ly
     */
    function classStatisticAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $sh = $this->get('sh');
        if (!$date = $sh['date']) {
            $date = date('Y-m-d');
            $sh['date'] = $date;
        }
        $this->view->assign('sh', $sh);
        
        $sh['school_id'] = $user['school_id'];
        $sh['phone'] = $user['phone'];
        $sh['role'] = $role;
        
        if($role == ACT_SCHOOL_TEACHER){
            //到校统计
            $classArriveSchoolNumList = $this->schoolModel->getTeacherClassArriveSchoolList($sh, $date);

            foreach ($classArriveSchoolNumList as $key => $value) {
                $classArriveStudentList = $this->schoolModel->getClassArriveStudentList(array('school_id' => $user['school_id'], 'class_id' => $value['class_id']), $date);
                $studentsMum = $this->schoolModel->getStudentsNum($value['class_id']);
                $classArriveSchoolNumList[$key]['mum'] = $studentsMum['0']['mum'];
                $classArriveSchoolNumList[$key]['classArriveStudentList'] = $classArriveStudentList;
            }

            $this->view->assign('classArriveSchoolNumList', $classArriveSchoolNumList);
            
            //离校统计
            $classLeaveSchoolNumList = $this->schoolModel->getTeacherClassLeaveSchoolList($sh, $date);
            
            foreach ($classLeaveSchoolNumList as $key => $value) {
                $classLeaveStudentList = $this->schoolModel->getClassLeaveStudentList(array('school_id' => $user['school_id'], 'class_id' => $value['class_id']), $date);
                $classLeaveSchoolNumList[$key]['classLeaveStudentList'] = $classLeaveStudentList;
            }
            
            $this->view->assign('classLeaveSchoolNumList', $classLeaveSchoolNumList);
            $this->view->assign('date', $date);
        
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }
        
        $this->view->assign('loginUser', $user);
        $this->view->layout();
    }
    
    /**
     * @desc 学校体温偏高统计首页
     * @desc 医生角色
     * @author ly
     */
    function schoolTemperatureStatisticAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $sh = $this->get('sh');
        if (!$date = $sh['date']) {
            $date = date('Y-m-d');
            $sh['date'] = $date;
        }
        $this->view->assign('sh', $sh);
        
        if($role == ACT_SCHOOL_DOCTOR || $role == ACT_SCHOOL_HEADMASTER){
            $dayArriveSchoolNum = $this->schoolModel->getDayArriveSchoolStatistic($user['school_id'], $date);
            $this->view->assign('dayArriveSchoolNum', $dayArriveSchoolNum);

            $dayLeaveSchoolNum = $this->schoolModel->getDayLeaveSchoolStatistic($user['school_id'], $date);
            $this->view->assign('dayLeaveSchoolNum', $dayLeaveSchoolNum);
            
            $schoolTemperatureStatisticList = $this->schoolModel->getSchoolTemperatureStatisticList($user['school_id'], $date);
            $this->view->assign('schoolTemperatureStatisticList', $schoolTemperatureStatisticList);
            $this->view->assign('date', $date);        
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }
        
        $this->view->assign('loginUser', $user);
        $this->view->layout();
    }
    
    /**
     * @desc 学生到离校统计首页
     * @desc 勤务角色
     * @author ly
     */
    function studentStatisticAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $sh = $this->get('sh');
        if (!$date = $sh['date']) {
            $date = date('Y-m-d');
            $sh['date'] = $date;
        }
        $this->view->assign('sh', $sh);
        
        if($role == ACT_SCHOOL_SUPPORTER){
            $dayArriveSchoolNum = $this->schoolModel->getDayArriveSchoolStatistic($user['school_id'], $date);
            $this->view->assign('dayArriveSchoolNum', $dayArriveSchoolNum);
            
            $dayLeaveSchoolNum = $this->schoolModel->getDayLeaveSchoolStatistic($user['school_id'], $date);
            $this->view->assign('dayLeaveSchoolNum', $dayLeaveSchoolNum);
            
            $this->view->assign('date', $date);
        }else{
            $this->redirect('?c=index&a=bind');
            exit;
        }
        
        $this->view->assign('loginUser', $user);
        $this->view->layout();
    }
    
    /**
     * @desc 园长发送通知
     * @author wei
     */
    function deliverNoticeAction(){
        $this->validAccessLoginUser('?c=index&a=bind');
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $signPackage = $this->getWeichatSignPackage();
        $this->view->assign('signPackage', $signPackage);
        
        $statistic = $this->staffModel->getStaff($user['id']);

        $schoolStudentList = $this->staffModel->getSchoolStudentList($statistic['school_id']);

        /*园长班级通知*/
        $schoolClassTitleList = $this->staffModel->getSchoolClassTitle($statistic['school_id']);

        $this->view->assign('schoolClassTitleList', $schoolClassTitleList);
        $this->view->layout(); 
    }
    
    function ajaxDeliverNoticeCommentAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        $statistic = $this->staffModel->getStaff($user['id']);
        // $schoolStudentList = $this->staffModel->getSchoolStudentList($statistic['school_id']);
        $form = $this->post('form');
        $class_ids = $form['class_id'];
        if($this->isComplete()){
            if ($this->staffModel->validSaveStatisticNotice($form, $errors)) {
                for($i=0; $i < count($class_ids) ; $i++) { 
                    $schoolStudentList = $this->staffModel->getSchoolClassStudentList($class_ids[$i]);
                    $data = array(
                        'school_id' => $user['school_id'],
                        'class_id' => $class_ids[$i],
                        'staff_id' => $user['id'],
                        'type_c' => $form['comment_type'],
                        'notice_type' => STATISTIC_NOTICE_TYPE,
                        'student_ids' => '',
                        'content' => $form['content'],
                        'voice_id' => $form['voice_id'] ? $form['voice_id'] : '', 
                        'can_reply' => APP_UNIFIED_FALSE,
                    );
                    if($i == 0){   
                        $class_notice_id = $this->staffModel->saveClassNotice($data);
                    }
                    
                    foreach($schoolStudentList as $schoolStudentListVal){
                        $parentList = $this->schoolParentModel->getSchoolParentListByStudentId($schoolStudentListVal['id']);
                        $studentName = $this->studentModel->getStudent($schoolStudentListVal['id']);
                        
                        foreach($parentList as  $val){
                            //生成系统通知

                            $system_notice = array(
                                'class_notice_id' => $class_notice_id,
                                'receive_uid' => $val['parent_id'],
                                'receive_utype' => ACT_PARENT_ROLE,
                                'send_uid' => $user['id'],
                                'send_utype' => ACT_SCHOOL_HEADMASTER,
                                'send_user_avatar' => './system/99.png',
                                'send_user_name' => $user['name'],
                                'is_read' => APP_UNIFIED_FALSE,
                                'is_url' => APP_UNIFIED_TRUE,
                                'url' => '?c=public&a=noticeDetailed&class_notice_id='.$class_notice_id,
                                'content' => '<span class="al-tongzhi-color">园长通知：</span>'.$statistic['name'].'给你发送了<span class="tongzhi-color">'. $studentName['name'] .'的通知消息</span>，请注意查看。',
                            );

                            $this->staffModel->saveSystemNotice($system_notice);

                            /*保存微信推送消息*/
                            // if($form['flag']){
                                $parentInfor = $this->staffModel->getParentInfor($val['parent_id']);
                                $studentSchool = $this->studentModel->getStudentSchool($schoolStudentListVal['id']);
                                if($parentInfor['openid']){
                                    $wei_form = array(
                                        'open_id' => $parentInfor['openid'],
                                        'student_id' => $schoolStudentListVal['id'],
                                        'school_title' => $studentSchool['school_title'],
                                        'student_name' => $studentName['name'],
                                        'staff_name' => $statistic['name'],
                                        'send_time' => NOW,
                                    );
                                    $wei_form['message'] = $this->weichatPushMessageModel->genImportantNotice($wei_form);
                                    $this->weichatPushMessageModel->saveMessage($wei_form);
                                }
                            // }

                        }

                        
                    }
                }
                $return = array(
                    'code' => 0,
                    "message" => '发送成功。',
                );
                
            }else{
                $return = array(
                    'code' => 1,
                    "message" => implode(';', $errors),
                );
            }
        }else{
           $return = array(
                'code' => 1,
                "message" => '上传数据错误。',
            ); 
        }
        
        
        print json_encode($return);
        exit();
    }

    /*园长历史通知消息*/
    function schoolMessageHistoryAction(){
        // 获取学校id
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        $statistic = $this->staffModel->getStaff($user['id']);
        // 园长历史消息列表
        $schoolMessageHisList = $this->staffModel->getSchoolMessageHistory($statistic['school_id']);
        for($i = 0; $i < count($schoolMessageHisList); $i++){
            $schoolSystemNotice = $this->staffModel->getSchoolSendPersonNum($schoolMessageHisList[$i]['staff_id'],$schoolMessageHisList[$i]['id']);
            $kindergartenLeaderName = $this->staffModel->getKindergartenLeaderById($schoolMessageHisList[$i]['staff_id']);
            $schoolMessageHisList[$i]['send_num'] = $schoolSystemNotice[0];
            $schoolMessageHisList[$i]['is_read'] = $schoolSystemNotice[1];
            $schoolMessageHisList[$i]['name'] = $kindergartenLeaderName[0]['name'];
        }
        $this->view->assign('schoolMessageHisList',$schoolMessageHisList);
        $this->view->layout();
    }

    /*老师列表*/
    function schoolStaffAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        $statistic = $this->staffModel->getStaff($user['id']);
        $schoolTeacherList = $this->staffModel->getSchoolTeacherList($statistic['school_id']);
        $this->view->assign('schoolTeacherList',$schoolTeacherList);
        $this->view->layout();
    }
    /*老师历史通知*/
    function staffMessageHistoryAction(){
        $staff_id = $this->get('staff_id');
        $teacherNoticeList = $this->staffModel->getTeacherNoticeList($staff_id);        
        $this->view->assign('user', $user);
        $this->view->assign('teacherNoticeList',$teacherNoticeList);
        $this->view->layout();
    }
    /*视频播放*/
    function videoMonitoringAction(){
        $this->view->layout();
    }

    function playVideoAction(){
        $this->view->layout();
    }


    /*直播*/
    function liveVideoAction(){
        $this->view->layout();
    }

    function playLiveVideoAction(){
        $this->view->layout();
    }




    /*视频*/
    function schoolVideoSettingAction(){
        $this->view->layout();
    }


    function schoolVideoDetailsAction(){
        
        $channelId = $this->get('channelId');
        $this->view->assign('channelId', $channelId); 
        $this->view->layout();
    }


}
?>
