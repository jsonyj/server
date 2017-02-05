<?php

class StaffSignModel extends AppModel {

    function getStaffList() {
        $sql = "
            SELECT tb_staff.*, tb_sign_type.in_time as in_time, tb_sign_type.out_time as out_time  FROM tb_staff LEFT JOIN  tb_sign_type ON tb_staff.sign_type_id = tb_sign_type.id AND tb_sign_type.deleted = 0
            WHERE tb_staff.deleted = 0 AND tb_staff.status = 1 AND tb_staff.type <>
        " . ACT_SCHOOL_HEADMASTER;
        return $this->getAll($sql);
    }

    
    /**
     * @desc  生产每天的签到签退数据
     * @author   wei
     */
    function saveStaffSignDate($data){
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
            'out_time' => $data['out_time'],
            'out_img' => $data['out_img'],
        );
        
        $record['created'] = NOW;
        return $this->Insert($table, $record);
        
    }
    /**
     * @desc  判断当天是否生成数据
     * @author   wei
     */
    function getIsStaffSignDate($staff_id ,$sign_date){
        $this->escape($staff_id);
        $this->escape($sign_date);
        $sql = "SELECT * FROM  tb_staff_signdate  WHERE staff_id = '{$staff_id}' AND sign_timestamp = '{$sign_date}' AND deleted = 0 ";
        
        return $this->getOne($sql);
        
    }
    
    /**
     * @desc  查询职工当天刷卡的最后一条记录
     * @author   wei
     */
    function getStaffSignrecordOut($staff_id ,$sign_date){
        $this->escape($staff_id);
        $this->escape($sign_date);
        
        $sql = "SELECT * FROM  tb_staff_signrecord  WHERE staff_id = '{$staff_id}' AND sign_timestamp = '{$sign_date}' AND deleted = 0 AND first != 1  ORDER BY created DESC ";
        
        return $this->getOne($sql); 
    }
    
    /**
     * @desc  查询职工当天是否有记录
     * @author   wei
     */
    function getStaffSignRecord($staff_id ,$sign_date){
        $this->escape($staff_id);
        $this->escape($sign_date);
        
        $sql = "SELECT * FROM  tb_staff_signrecord  WHERE staff_id = '{$staff_id}' AND sign_timestamp = '{$sign_date}' AND deleted = 0";
        
        $rs = $this->getOne($sql); 
        return $rs ? true : false;
    }
    
    /**
     * @desc  修改职工签退日期并且判断状态
     * @author   wei
     */
    function updateStaffSignDate($data) {
        $this->escape($data);
        $id = $data['staffSignDate_id'];
        $staff_id = $data['staff_id'];
        $sign_timestamp = $data['sign_timestamp'];
        $from = array(
            'sign_status' =>$data['sign_status'],
            'out_time' => $data['out_time'],
            'out_img' => $data['out_img'],
        );
        $where = "id = '{$id}' AND staff_id = '{$staff_id}' AND sign_timestamp = '{$sign_timestamp}' ";
        return $this->Update('tb_staff_signdate', $from, $where);
    }
    /**
     * @desc  修改职工最后一条签退记录
     * @author   wei
     */
    function updateStaffSignrecord($id) {
        $this->escape($id);
        $data = array(
            'lastest' => APP_UNIFIED_TRUE,
        );
        $where = "id = '{$id}' ";
        return $this->Update('tb_staff_signrecord', $data, $where);
    }
    
    /**
     * @desc 获取某学校下面需要更新的职工
     * @author wei
     */
    function getSchoolStaffSign($school_id, $school_key) {
        $this->escape($school_id);
        $this->escape($school_key);
        
        $sql = "
            SELECT * 
            FROM tb_staff
            WHERE school_id = '{$school_id}'
                AND qrcode_school_key <> '{$school_key}'
                AND deleted = 0
        ";
        
        return $this->getAll($sql);
    }
    
    /**
     * @desc 更新职工二维码
     * @author wei
     */
    function saveStaffSignQrcode($id, $qrcode) {
        $this->escape($id);
        
        $data = array(
            'qrcode_url' => $qrcode['qrcode_url'],
            'qrcode_school_key' => $qrcode['qrcode_school_key'],
        );
        $where = "id = '{$id}'";
        
        return $this->Update('tb_staff', $data, $where);
    }
}

?>
