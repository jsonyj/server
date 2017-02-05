<?php

class SchoolRoleModel extends AppModel {

    function getSchool($id) {
        $this->escape($id);
        $sql = "SELECT * FROM tb_school 
                WHERE id = '{$id}' AND deleted = 0 AND status = 1
            ";
        return $this->getOne($sql);
    }
    
    function getSchoolStaff($id){
        $this->escape($id);
        
        $sql = "SELECT tb_staff.*, tb_sign_type.in_time as in_time, tb_sign_type.out_time as out_time  FROM  tb_staff LEFT JOIN tb_sign_type ON tb_staff.sign_type_id = tb_sign_type.id AND tb_sign_type.deleted = 0   WHERE tb_staff.id = '{$id}' AND tb_staff.deleted = 0  AND tb_staff.status = 1 ";
        
        return $this->getOne($sql);
        
    }
    
    function validSchoolRoleSign($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'id' => array(
                array('isNotNull', '请输入职工ID。'),
            ),
        );
        
        if($form['create_time']){
            $config = array(
                'create_time' => array( 
                    array('isDateTimeTwo', '上传时间格式不正确。'),
                ),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    /**
     * @desc  签到签退
     * @author   wei
     */
    function saveSchoolStaffSign($data){
        $table = 'tb_staff_signdate';
        
        $record = array(
            'staff_id' => $data['staff_id'],
            'sign_timestamp' => $data['sign_timestamp'],
            'sign_date' => $data['sign_date'],
            'set_intime' => $data['set_intime'],
            'set_outtime' => $data['set_outtime'],
            'sign_status' => $data['sign_status'],
            'in_time' => $data['in_time'],
            'in_img' => $data['in_img'],
            'out_time' => $data['out_time'] ? $data['out_time'] : '00:00:00',
            'out_img' => $data['out_img'] ? $data['out_img'] : '',
        );
        
        if ($data['id'] > 0) {
            $file_id = $data['id'];
            $this->escape($file_id);
            
            $where = "id = '{$file_id}'";
            $this->Update($table, $record, $where);
            return $file_id;
        }
        else {
            $record['created'] = $data['create_time'] ? date("Y-m-d H:i:s", strtotime($data['create_time'])) : NOW;
            return $this->Insert($table, $record);
        }
        
    }
    
    /**
     * @desc  记录每一次刷卡
     * @author   wei
     */
    function saveStaffSignrecord($data){
        $table = 'tb_staff_signrecord';
        
        $record = array(
            'staff_id' => $data['staff_id'],
            'sign_timestamp' => $data['sign_timestamp'],
            'sign_date' => $data['sign_date'],
            'first' => $data['first'],
            'lastest' => $data['lastest'],
            'img' => $data['img'],
        );
        
        $record['created'] = NOW;
        return $this->Insert($table, $record);
    }
    
    function getIsSchooltStaffSignDate($staff_id ,$sign_date){
        $this->escape($staff_id);
        $this->escape($sign_date);
        
        $sql = "SELECT * FROM  tb_staff_signdate  WHERE staff_id = '{$staff_id}' AND sign_timestamp = '{$sign_date}' AND deleted = 0 ";
        
        return $this->getOne($sql);
        
    }
    
    function getSchoolSignrecord($staff_id ,$sign_date, $first){
        $this->escape($staff_id);
        $this->escape($sign_date);
        $this->escape($first);
        
        $sql = "SELECT * FROM  tb_staff_signrecord  WHERE staff_id = '{$staff_id}' AND sign_timestamp = '{$sign_date}' AND deleted = 0  ";
        if($first){
            $sql .= " AND first = '{$first}' ";
            
        }
        return $this->getOne($sql);
        
    }
    
    function validGetSchooID($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'school_id' => array(
                array('isNotNull', '请输入学校ID'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }    
    /**
     * @desc  查询学职工信息
     * @author   wei
     */
    function getSchoolStaffList($school_id, $timestamp){
        $this->escape($school_id);
        $this->escape($timestamp);
        
        if($timestamp) {
            $timestampSql = " AND  tb_staff.updated >= '{$timestamp}' ";
        }
        
        $sql = "SELECT * FROM tb_staff 
                WHERE tb_staff.school_id  = '{$school_id}' {$timestampSql} AND tb_staff.deleted = 0 AND tb_staff.status = 1 ORDER BY tb_staff.updated DESC
        ";
        
        $rs = $this->getAll($sql);
        return $rs; 
    }
    
    function getSchoolClass($school_id, $staff_id){
        $this->escape($school_id);
        $this->escape($staff_id);
        $sql = "SELECT tb_class.* FROM tb_staff_class 
        LEFT JOIN tb_class ON tb_staff_class.class_id = tb_class.id AND tb_class.deleted = 0
        WHERE tb_staff_class.staff_id ={$staff_id} AND tb_staff_class.school_id ={$school_id} AND tb_staff_class.deleted = 0 ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据电话获取家长信息
     * @author ly
     * @param $phone
     * @return NULL|unknown
     */
    function getSchoolParent($school_id, $phone){
        $this->escape($phone);
        $this->escape($school_id);
        
        $sql = "
            SELECT tb_school_parent.* FROM tb_school_parent
            WHERE tb_school_parent.deleted = 0 AND tb_school_parent.phone = '{$phone}'
            AND school_id = '{$school_id}'";
        
        return $this->getOne($sql);
    }
    
}

?>
