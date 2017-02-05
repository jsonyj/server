<?php

class StaffModel extends AppModel {
    
    /**
     * @desc 根据电话号码获取职工信息
     * @author lxs
     */
    function getStaffByPhone($phone, $type = null) {
        $this->escape($phone);

        $sql = "
            SELECT * FROM tb_staff WHERE phone = '{$phone}' AND status = 1 AND deleted = 0
        ";
        
        if ($type) {
            $this->escape($type);
            $sql .= " AND type = '{$type}'";
        }

        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据ID获取职工信息
     * @author lxs
     */
    function getStaff($id, $type = null) {
        $this->escape($id);

        $sql = "
            SELECT tb_staff.*,  tb_staff_class.class_id as class_id
            FROM tb_staff LEFT JOIN tb_staff_class ON tb_staff.id = tb_staff_class.staff_id AND tb_staff_class.deleted = 0
            WHERE tb_staff.id = '{$id}' AND tb_staff.status = 1 AND tb_staff.deleted = 0
        ";
        
        if ($type) {
            $this->escape($type);
            $sql .= " AND type = '{$type}'";
        }

        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据classID获取学生信息
     * @author wei
     */
     
    function  getTeacherStudentList($class_id){
        $this->escape($class_id);
        
        $sql = "SELECT * FROM tb_student WHERE class_id = '{$class_id}'  AND status = 1 AND deleted = 0 ";
        
        return $this->getAll($sql);
    }
    
    /**
     * @desc 新建系统通知数据
     * @author wei
     */
    function saveSystemNotice($form) {
        $table = 'tb_system_notice';
        $data = array(
            'class_notice_id' => $form['class_notice_id'],
            'receive_uid' => $form['receive_uid'],
            'receive_utype' => $form['receive_utype'],
            'send_uid' => $form['send_uid'],
            'send_utype' => $form['send_utype'],
            'send_user_avatar' => $form['send_user_avatar'],
            'send_user_name' => $form['send_user_name'],
            'is_read' => $form['is_read'],
            'is_url' => $form['is_url'],
            'url' => $form['url'],
            'content' => $form['content'],
            // 'img_notice' => $form['img_notice'],
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    
    function validSaveClassNotice($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'student_id' => array(
                array('isNotNull', '请选择通知学生。'),
            ),
        );
        
        if(ctype_space($form['content']) && $form['comment_type'] == 1){
            $config = array(
                'content' => array(
                    array('isNotNull', '发送消息不能为空。'),
                ),
            );
            $errors['content'] = "发送消息不能为空。";
            return false;
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function validSaveStatisticNotice($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        if(ctype_space($form['content']) && $form['comment_type'] == 1){
            $config = array(
                'content' => array(
                    array('isNotNull', '发送消息不能为空。'),
                ),
            );
            $errors['content'] = "发送消息不能为空。";
            return false;
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
     /**
     * @desc 新建班级通知数据
     * @author wei
     */
    function saveClassNotice($form) {
        $table = 'tb_class_notice';
        $data = array(
            'school_id' => $form['school_id'],
            'class_id' => $form['class_id'],
            'staff_id' => $form['staff_id'],
            'type_c' => $form['type_c'],
            'notice_type' => $form['notice_type'],
            'student_ids' => $form['student_ids'],
            'voice_id' => $form['voice_id'],
            'content' => $form['content'],
            'can_reply' => $form['can_reply'],
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
     /**
     * @desc 班级通知回复
     * @author wei
     */
    function saveClassNoticeReplyNotice($form) {
        $table = 'tb_class_notice_reply';
        $data = array(
            'class_notice_id' => $form['class_notice_id'],
            'parent_id' => $form['parent_id'],
            'branch_id' => $form["branch_id"],
            'reply_uid' => $form['reply_uid'],
            'reply_utype' => $form['reply_utype'],
            'reply_user_avatar' => $form['reply_user_avatar'],
            'reply_user_name' => $form['reply_user_name'],
            'comment_type' => $form['comment_type'],
            'content' => $form['content'],
            'voice_id' => $form['voice_id'],
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    function validStudentPic($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'student_id' => array(
                array('isNotNull', '学生ID错误。'),
            ),
            'img_ids' => array(
                array('isNotNull', '上传照片id错误。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
     /**
     * @desc 学生照片
     * @author wei
     */
    function saveStudentPic($form) {
        $table = 'tb_student_pic';
        $data = array(
            'student_id' => $form['student_id'],
            'staff_id' => $form['staff_id'],
            'type' => $form['type'],
            'pic_ids' => $form['pic_ids'],
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    function validSaveClassNoticeReply($form, &$errors){
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'class_notice_id' => array(
                array('isNotNull', '通知id不能为空。'),
            ),
            'reply_uid' => array(
                array('isNotNull', '回复者ID不能为空。'),
            ),
        );
        
        if(ctype_space($form['content']) && $form['comment_type'] == 1){
            $errors['content'] = "发送消息不能为空。";
            return false;
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
        
    }
    
    /**
     * @desc 回复
     * @author wei
     */
    function saveClassNoticeReply($form) {
        $table = 'tb_class_notice_reply';
        $data = array(
            'class_notice_id' => $form['class_notice_id'],
            'student_id' => $form['student_id'],
            'parent_id' => $form['parent_id'],
            'branch_id' => $form["branch_id"],
            'reply_uid' => $form['reply_uid'],
            'reply_utype' => $form['reply_utype'],
            'reply_user_avatar' => $form['reply_user_avatar'],
            'reply_user_name' => $form['reply_user_name'],
            'comment_type' => $form['comment_type'],
            'content' => $form['content'],
            'voice_id' => $form['voice_id'],
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    /**
    *@author 根据id查询通知内容
    *@desc wei
    **/
     function getUserSystemNoticeList($receive_uid){
        $this->escape($receive_uid); 
        $sql = "SELECT * FROM tb_system_notice WHERE tb_system_notice.receive_uid = '{$receive_uid}' AND tb_system_notice.deleted = 0 AND tb_system_notice.receive_utype = 32 order by created  desc";
        return $this->getAll($sql);
    }
    
    /**
    *@author 根据id查询家长通知
    *@desc wei
    **/
     function getUserSystemNotice($id){
        $this->escape($id);
        
        $sql = "SELECT * FROM tb_system_notice WHERE tb_system_notice.id = '{$id}' AND tb_system_notice.deleted = 0  ";
        
        return $this->getOne($sql);
    }
    
    /**
    *@author 根据班级通知id查询回复
    *@desc wei
    **/
    function getClassNoticeReplyList($class_Notice_id, $branch_id){
        $this->escape($class_Notice_id);   
        $this->escape($branch_id);   
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE tb_class_notice_reply.class_notice_id = '{$class_Notice_id}' AND tb_class_notice_reply.deleted = 0 AND branch_id = '{$branch_id}'  order by created asc";
        
        $rs = $this->getAll($sql);
        $voiceModel = $this->getModel('Voice');
        foreach($rs as $key => $val){
            if($val['comment_type'] == 2){
                $voice = $voiceModel->getVoice($val['voice_id']);
                $rs[$key]['voice'] = $voice;
            }
            
        }
        return $rs;
    }
    
    function getClassNoticeReplyParentList($class_Notice_id){
        $this->escape($class_Notice_id);   
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE tb_class_notice_reply.class_notice_id = '{$class_Notice_id}' AND tb_class_notice_reply.deleted = 0 AND parent_id = 0 order by created asc";
        
        $rs = $this->getAll($sql);
        $voiceModel = $this->getModel('Voice');
        foreach($rs as $key => $val){
            if($val['comment_type'] == 2){
                $voice = $voiceModel->getVoice($val['voice_id']);
                $rs[$key]['voice'] = $voice;
            }
            
        }
        return $rs;
        
    }
    
    /**
     * @desc 老师查询回复的上一条
     * @author wei
     */
    function getIsParentClassNoticeReply($classNotice_id, $reply_uid, $student_id){
        $this->escape($classNotice_id); 
        $this->escape($reply_uid); 
        $this->escape($student_id); 
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE class_notice_id = '{$classNotice_id}' AND  reply_uid = '{$reply_uid}' AND student_id = {$student_id}  AND deleted = 0 ORDER BY created DESC";
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    function getBranchClassNoticeReply($classNotice_id, $reply_uid, $student_id){
        $this->escape($classNotice_id); 
        $this->escape($reply_uid); 
        $this->escape($student_id); 
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE class_notice_id = '{$classNotice_id}' AND  reply_uid = '{$reply_uid}'  AND deleted = 0 AND parent_id = 0 AND student_id = {$student_id} ";
        
        $rs = $this->getOne($sql);
        return $rs;
        
    }
    
    function validSaveStudentPraise($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'student_id' => array(
                array('isNotNull', '请选择通知学生。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    /**
     * @desc 老师发送小红花
     * @author wei
     */ 
    function  saveStudentPraise($form){
        $table = 'tb_student_praise';
        $data = array(
            'student_ids' => $form['student_ids'],
            'staff_id' => $form['staff_id'],
            'content' => $form['content'],
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
        
    }
    
    /**
     * @desc 获取当前学校学生的所以ID
     * @author wei
     */ 
    function getSchoolStudentList($school_id){
        $this->escape($school_id); 
        
        $sql = "SELECT * FROM tb_student WHERE school_id = '{$school_id}' AND deleted = 0 ORDER BY created DESC";
        
        $rs = $this->getAll($sql);
        return $rs;
    }

    /*所以当前班级的所有学生ID*/
    function getSchoolClassStudentList($class_id){
        $this->escape($class_id);
        $sql = "SELECT * FROM tb_student WHERE class_id = '{$class_id}' AND deleted = 0 ORDER BY created DESC";
        $rs = $this->getAll($sql);
        return $rs;
    }

    /*获取当前学校所有的班级*/
    function getSchoolClassTitle($school_id){
        $this->escape($school_id);
        $sql = "SELECT * FROM tb_class WHERE school_id = '{$school_id}' AND deleted = 0";
        $rs = $this->getAll($sql);
        return $rs;
    }

    /*获取当前学校的所有老师*/
    function getSchoolTeacherList($school_id){
        $this->escape($school_id);
        $sql = "SELECT * FROM tb_staff_class AS tb_sc LEFT JOIN tb_staff AS tb_s ON tb_sc.staff_id = tb_s.id LEFT JOIN tb_class AS tb_c 
                ON tb_sc.class_id = tb_c.id WHERE tb_sc.school_id = '{$school_id}' AND tb_s.type = 32";
        $rs = $this->getAll($sql);
        return $rs;
    }
    /*从数据获取园长发送的所有历史消息*/
    function getSchoolMessageHistory($school_id){
        $this->escape($school_id);
        $sql = "SELECT * FROM tb_class_notice WHERE school_id = '{$school_id}' AND notice_type = 3 AND created > DATE_SUB( CURDATE( ) , INTERVAL 3 MONTH ) AND deleted = 0
        ORDER BY created DESC";
        $rs = $this->getAll($sql);
        return $rs;
    }


    /*从数据库获取园长发送对应消息的人数和阅读数量*/
    function getSchoolSendPersonNum($send_uid,$class_notice_id){
        $sql = "SELECT * FROM tb_system_notice WHERE send_uid = '{$send_uid}' AND send_utype = 31 AND deleted = 0 AND class_notice_id = '{$class_notice_id}' ORDER BY created DESC";
        $is_aread_sql = "SELECT * FROM tb_system_notice WHERE send_uid = '{$send_uid}' AND send_utype = 31 AND deleted = 0 AND class_notice_id = '{$class_notice_id}' 
        AND is_read = 1 ORDER BY created DESC";
        $rs = array();
        array_push($rs, count($this->getAll($sql)));
        array_push($rs, count($this->getAll($is_aread_sql)));   
        return $rs;
    }

    function getKindergartenLeaderById($send_uid){
        $this->escape($send_uid);
        $sql = "SELECT name FROM tb_staff WHERE id = '{$send_uid}' AND deleted = 0";
        $rs = $this->getAll($sql);
        return $rs;
    }
    


    /*获取老师页所发送的全部消息*/
    function getTeacherNoticeList($send_uid){
        $this->escape($send_uid);
        $noticeSql = "SELECT * FROM tb_class_notice WHERE staff_id = '{$send_uid}' AND deleted = 0  AND created > DATE_SUB( CURDATE( ) , INTERVAL 3 MONTH ) ORDER BY created DESC";
        $noticeRs = $this->getAll($noticeSql);

        /*发送人数*/
        $rs = array();
        foreach ($noticeRs as $key => $value) {
            $sendNumSql = "SELECT * FROM tb_system_notice AS tb_s LEFT JOIN tb_class_notice AS tb_c ON tb_s.class_notice_id = tb_c.id
                            WHERE  tb_s.class_notice_id = '{$value['id']}' AND tb_c.staff_id = '{$send_uid}' AND tb_s.send_uid = '{$send_uid}' AND tb_c.deleted = 0 AND tb_s.deleted = 0";
            $sendSystemNotcie = $this->getAll($sendNumSql);

            /*已阅读的人数统计*/
            $isReadSql = "SELECT * FROM tb_system_notice AS tb_s LEFT JOIN tb_class_notice AS tb_c ON tb_s.class_notice_id = tb_c.id
                            WHERE tb_s.class_notice_id = '{$value['id']}' AND tb_c.staff_id = '{$send_uid}' AND tb_s.send_uid = '$send_uid' AND tb_c.deleted = 0 AND tb_s.deleted = 0 AND tb_s.is_read = 1";
            $isReadNum = count($this->getAll($isReadSql));

            $isNewReplaySql = "SELECT * FROM tb_system_notice WHERE class_notice_id ='{$value['id']}' AND send_utype =21 AND is_read =0";
            $isNewReplayNum = count($this->getAll($isNewReplaySql));
            /*新回复消息*/
            $isReplaySql = "SELECT * FROM tb_system_notice WHERE class_notice_id ='{$value['id']}' AND send_utype =21";
            $isReplayNum = count($this->getAll($isReplaySql));

            $value['sendNum'] = count($sendSystemNotcie);
            $value['isReadNum'] = $isReadNum;
            $value['send_user_avatar'] = "./system/99.png";
            $flag = -1;

            $isReplaySystemSql = "SELECT * FROM tb_system_notice WHERE class_notice_id = '{$value['id']}' AND 
                 send_utype = 21 AND receive_uid = '{$send_uid}' ORDER BY created DESC";
            $replaySystem = $this->getOne($isReplaySystemSql);

            if($isReplayNum){                
                $value['url'] = $replaySystem['url']; 
                $value['is_read'] = $replaySystem['is_read'];
            }
            if($isNewReplayNum){
                $value['created'] = $replaySystem['created'];
                $value['url'] = $replaySystem['url']; 
                $value['is_read'] = $replaySystem['is_read'];
                $value['replayTips'] = "有宝宝家长新回复";      
                $flag = 1;
            }
            /*消息置顶*/
            if($flag == 1){
                array_unshift($rs, $value);
            }else{
                array_push($rs, $value);     
            }
        }
        return $rs;
    }

    function getParentInfor($parent_id){
        $this->escape($parent_id);
        $sql = "SELECT * FROM tb_school_parent AS tb_sp
                LEFT JOIN tb_parent ON tb_sp.phone = tb_parent.phone
                LEFT JOIN tb_weichat ON tb_parent.weichat_id = tb_weichat.id
                WHERE tb_parent.deleted =0 AND tb_sp.id = {$parent_id}";
        $rs = $this->getOne($sql);
        return $rs;
    }
    
}

?>
