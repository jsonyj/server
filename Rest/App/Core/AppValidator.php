<?php

class AppValidator extends BraveValidator {
    
    function isAdminExists($field, $vars) {
        $id = (int)$vars['id'];
        $email = $this->data[$field];
        $this->escape($id);
        
        $sql = "
            SELECT * FROM tb_admin 
            WHERE email = '{$email}' AND id <> '{$id}' AND deleted = 0
        ";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isAgentPhoneValid($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "
            SELECT * FROM tb_agents 
            WHERE phone = '{$phone}' AND deleted = 0 AND activated = 1
        ";
        
        $rs = $this->getOne($sql);
        return $rs? true: false;
    }
    
    function isMemberExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "
            SELECT * FROM tb_members 
            WHERE phone = '{$phone}' AND deleted = 0 AND activated = 1
        ";
        
        $rs = $this->getOne($sql);
        return $rs? true: false;
    }
    
    function isMemberNotExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "
            SELECT * FROM tb_members 
            WHERE phone = '{$phone}' AND deleted = 0 AND activated = 1
        ";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isAgentNotExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "
            SELECT * FROM tb_agents 
            WHERE phone = '{$phone}' AND deleted = 0 AND activated = 1
        ";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isTypeExists($field, $vars) {
        $type = $this->data[$field];
        $this->escape($type);
        
        if(($type == MEMBER_REGIST_CAPTCHA_TYPE) || ($type == AGENT_REGIST_CAPTCHA_TYPE) || ($type == MEMBER_FORGETPASSWORD_CAPTCHA_TYPE) || ($type == AGENT_LOGIN_CAPTCHA_TYPE)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function isAgentCaptchaExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "SELECT * FROM tb_user_captcha 
            WHERE phone = '{$phone}' AND deleted = 0 AND user_type = ".TD_USER_TYPE_AGENT;
        
        $rs = $this->getOne($sql);
        return $rs ? false: true;
    }
    
    function isMemberCaptchaExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "SELECT * FROM tb_user_captcha 
            WHERE phone = '{$phone}' AND deleted = 0 AND user_type = ".TD_USER_TYPE_MEMBER;
        
        $rs = $this->getOne($sql);
        return $rs ? false: true;
    }

    function isPhotoExisted($field, $vars) {
        $id = $this->data[$field];

        $photoModel = $this->getModel('Photo');

        return $photoModel->getPhotoListById($id) ? false: true;
    }
    
    /**
     * @desc 检查姓名是否重复
     * @author ly
     * @param $field
     * @param $vars
     * @return boolean
     */
    function isClassStudentNotExisted($field, $vars){
        $studentName = $vars['name'];
        $classId = $this->data['class_id'];
        $id = $this->data['id'];
    
        $this->escape($studentName);
        $this->escape($classId);
        $this->escape($id);
    
        $sql = "SELECT count(id) AS count FROM tb_student WHERE deleted = 0 AND name = '{$studentName}' AND class_id = '{$classId}' AND id <> '{$id}'";
        $rs = $this->getOne($sql);
    
        return $rs['count'] >= 1 ? false: true;
    }
    
    
    /**
     * @desc 验证学生家长关系是否存在
     * @author ly
     * @param $field
     * @param $vars
     * @return number
     */
    function isNotRelationExist($field, $vars){
        
        $sql = "SELECT COUNT(tb_student_parent.id) as count FROM tb_student_parent
                WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$vars['student_id']}'
                AND (tb_student_parent.parent_id = '{$vars['parent_id']}' OR tb_student_parent.relation_id = '{$vars['relation_id']}')
                ";
        
        $rs = $this->getOne($sql);
        
        return $rs['count'] >= 1 ? false: true;
    }
    
    /**
     * @desc 验证学校是否存在
     * @author ly
     * @param $field
     * @param $vars
     * @return boolean
     */
    function isNotSchoolExist($field, $vars){
        $id = $this->data[$field];
        
        $sql = "SELECT COUNT(id) AS count FROM tb_school WHERE deleted = 0 AND id = '{$id}'";
        
        $rs = $this->getOne($sql);
        
        return $rs['count'] >= 1 ? true:false;
    }
    
    /**
     * @desc 验证学校班级是否存在
     * @author ly
     * @param $field
     * @param $vars
     * @return boolean
     */
    function isNotSchoolClassExist($field, $vars){
        $id = $this->data[$field];
        $school_id = $vars;
        
        $sql = "SELECT COUNT(id) AS count FROM tb_class WHERE deleted = 0 AND id = '{$id}'
            AND school_id = '{$school_id}'
        ";
        
        $rs = $this->getOne($sql);
        
        return $rs['count'] >= 1 ? true:false;
    }
    
    /**
     * @desc 验证家长是否存在
     * @author ly
     * @param $field
     * @param $vars
     * @return boolean
     */
    function isNotParent($field, $vars){
        $phone = $this->data[$field];
        
        $sql = "SELECT COUNT(tb_school_parent.id) AS count FROM tb_school_parent 
                left join tb_student_parent on tb_student_parent.parent_id = tb_school_parent.id
                WHERE tb_school_parent.deleted = 0 AND tb_school_parent.phone = '{$phone}'";
        
        $rs = $this->getOne($sql);
        
        return $rs['count'] >= 1 ? true:false;
    }
    
    /**
     * @desc 验证家长是否存在
     * @author wei
     * @param $field
     * @param $vars
     * @return boolean
     */
    function isParent($field, $vars){
        $phone = $this->data[$field];
        
        $sql = "SELECT COUNT(tb_school_parent.id) AS count FROM tb_school_parent 
                left join tb_student_parent on tb_student_parent.parent_id = tb_school_parent.id
                WHERE tb_school_parent.deleted = 0 AND tb_school_parent.phone = '{$phone}'";
        
        $rs = $this->getOne($sql);
        
        return $rs['count'] >= 1 ? false:true;
    }
    /**
     * @desc 验证检测数据ID
     * @author ly
     * @param $field
     * @param $vars
     * @return boolean
     */
    function isNotDetection($field, $vars){
        $detection_id = $this->data[$field];
        
        $sql = "SELECT COUNT(id) AS count FROM tb_student_detection 
                WHERE deleted = 0 AND id = '{$detection_id}'";
        
        $rs = $this->getOne($sql);
        
        return $rs['count'] >= 1 ? true:false;
    }
    

}

?>