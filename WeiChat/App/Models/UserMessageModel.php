<?php

/**
 * @desc 用户消息类
 * @author wzl
 */

class UserMessageModel extends BaseModel {

    function getMessageList($user_id, $user_role) {
        
        $this->escape($user_id);
        //根据家长id找学生
        if ($user_role == '21') {
            $sql = "
                SELECT student_id FROM tb_student_parent 
                WHERE parent_id = '{$user_id}' AND deleted = 0
            ";
            $all_parent_student = $this->getAll($sql);
            unset($sql);
        }
        //查找消息
        $sql = "SELECT * FROM tb_user_message ";
        switch ($user_role) {
            case '21'://家长可以查看发给自己和宝贝的消息
                $sql .= "WHERE ((tb_user_message.t_uid = '{$user_id}' AND tb_user_message.t_utype = '{$user_role}') ";
                foreach ($all_parent_student as $k => $v) {
                    $sql .= "OR (tb_user_message.t_uid = {$v['student_id']} AND tb_user_message.t_utype = '40')) ";
                }
                break;
            case '30':
                $sql .= "WHERE (tb_user_message.t_uid = '{$user_id}' AND tb_user_message.t_utype = '{$user_role}') ";
                break;
            default:
                $sql .= " WHERE 1 = 0 ";
                break;
        }
        $sql .= " AND tb_user_message.deleted = 0 ORDER BY created DESC";
        
        return $this->getAll($sql);
    }
    
    /**
    *@author: 查询父亲id
    *@desc: wei
    **/
    function getParentId($phone) {
        $this->escape($phone);

        $sql = "
            SELECT * FROM tb_school_parent WHERE phone = '{$phone}' AND deleted = 0
            ";

        $rs = $this->getOne($sql);
        return $rs;
    }
    
    /**
    *@author 根据家长parentId查询通知
    *@desc wei
    **/
    function getUserSystemNoticeList($parentId){
        $this->escape($parentId);
        
        $sql = "SELECT * FROM tb_system_notice WHERE tb_system_notice.receive_uid = '{$parentId}' AND tb_system_notice.deleted = 0  ORDER BY tb_system_notice.created DESC";
        
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
    *@author 根据id查询通知内容
    *@desc wei
    **/
    function getUserClassNotice($classNotice_id){
        $this->escape($classNotice_id); 
        
        $sql = " SELECT * FROM tb_class_notice WHERE id='{$classNotice_id}' AND deleted = 0";
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    /**
    *@author 根据id查询通知内容
    *@desc wei
    **/
    function getUserStudentPraise($studentId, $dateTime){
        $this->escape($studentId); 
        
        $sql = " SELECT * FROM tb_student_praise WHERE student_ids  like  '%{$studentId}%' AND  DATE_FORMAT(created,'%Y-%m') = '{$dateTime}' AND deleted = 0";
        
        $rs = $this->getAll($sql);
        return $rs;
    }
    
    /**
    *@author 根据id查询通知图片
    *@desc wei
    **/
    function getUserNoticeImg($pic_id){
        $this->escape($pic_id);
        
        $sql = " SELECT * FROM tb_student_pic WHERE id= '{$pic_id}' AND deleted = 0";
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    /**
     * @desc 查询回复的上一条
     * @author wei
     */
    function getIsParentClassNoticeReply($classNotice_id, $reply_uid, $student_id){
        $this->escape($classNotice_id); 
        $this->escape($reply_uid); 
        $this->escape($student_id); 
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE class_notice_id = '{$classNotice_id}' AND  reply_uid = '{$reply_uid}' AND student_id  = '{$student_id}'  AND deleted = 0 ORDER BY created DESC";
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    function getStudentParentName($parent_id){
        $this->escape($parent_id); 
        
        $sql = "SELECT tb_student_parent.*, tb_school_parent.name as parent_name  FROM tb_student_parent LEFT JOIN tb_school_parent ON tb_student_parent.parent_id = tb_school_parent.id AND  tb_school_parent.deleted = 0 WHERE tb_student_parent.parent_id = {$parent_id} AND tb_student_parent.deleted = 0";
       
        $rs = $this->getOne($sql);
        return $rs; 
    }
    /**
     * @desc 查询回复消息
     * @author wei
     */ 
    function getClassNoticeReplyList($class_notice_id, $branch_id){
        $this->escape($class_notice_id); 
        $this->escape($branch_id); 
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE class_notice_id = '{$class_notice_id}' AND branch_id = '{$branch_id}' AND deleted = 0 order by created asc";
        
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
     * @desc 查询回复第一条
     * @author wei
     */ 
    function getClassNoticeReplyParentList($class_Notice_id, $reply_uid, $student_id){
        $this->escape($class_Notice_id);
        $this->escape($student_id);
        $this->escape($reply_uid);
        
        $sql = "SELECT * FROM tb_class_notice_reply WHERE tb_class_notice_reply.class_notice_id = '{$class_Notice_id}' AND reply_uid='{$reply_uid}' AND tb_class_notice_reply.deleted = 0 AND parent_id = 0 AND student_id = '{$student_id}' order by created asc";
        
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
    *@author wei
    *@desc 消息置为已读
    **/
    function updateIsRead($id) {
        $this->escape($id);
        $table = 'tb_system_notice';
        $data = array(
            'is_read' => '1'
        );
        $whereSql = "id = '{$id}'";
        $this->Update($table, $data, $whereSql);
    }

    function updateIsReadByCNId($class_notice_id,$user_id){
        $this->escape($class_notice_id);
        $this->escape($user_id);
        $table = 'tb_system_notice';
        $data = array(
            'is_read' => '1'
        );
        $whereSql = "class_notice_id = '{$class_notice_id}'
                    AND receive_uid = '{$user_id}'";
        $this->Update($table, $data, $whereSql);
    }
    /**
    *@author wei
    *@desc 消息提示
    **/
    function getUserMessageTishi($id,$role){
        $this->escape($id);
        $this->escape($role);
        
        $sql = "SELECT * FROM tb_system_notice WHERE  receive_uid = '{$id}' AND receive_utype='{$role}' AND is_read = 0 AND deleted = 0 ORDER BY created ASC";
        
        $rs = $this->getAll($sql);
        return $rs; 
    }
}


?>