<?php

/**
 * @desc 职工model
 * @author
 */
class StaffModel extends AppModel {

    /**
     * @desc 验证职工信息
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validStaff($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'name' => array(
                array('isNotNull', '请输入姓名'),
            ),
            'phone' => array(
                array('isNotNull', '请输入电话'),
                array('isPhoneNo', '电话格式不正确，请重新输入', $form['phone']),
                array('isSchoolStaffNotExisted', '职工电话已存在，请核对后重新输入', $form),
            ),
            'type' => array(
                array('isNotNull', '请选择职工类型'),
            ),e
        );
        // if($form['type'] != ACT_SCHOOL_HEADMASTER){
        //     $config['sign_type_id'] = array(
        //         array('isNotNull', '请选择签到类型'),
        //     );
        // }

        if($form['type'] == ACT_SCHOOL_TEACHER){
            $config['class_id'] = array(
                array('isNotNull', '请选择班级'),
            );
        }

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    /**
     * @desc 查询当前学校多条职工信息
     * @author ly
     * @param $school_id
     * @param $sh
     * @param string $all
     * @return array
     */
    function getStaffList($school_id, $sh, $all = false){
        $this->escape($school_id);
        $this->setSearch($sh);

        $sql = "SELECT tb_staff.*,tb_class.title AS class_title,tb_sign_type.title,tb_sign_type.in_time,tb_sign_type.out_time FROM tb_staff
                LEFT JOIN tb_sign_type ON tb_sign_type.id = tb_staff.sign_type_id
                LEFT JOIN tb_staff_class ON tb_staff_class.staff_id = tb_staff.id
                LEFT JOIN tb_class ON tb_class.id = tb_staff_class.class_id
                WHERE tb_staff.deleted = 0 AND tb_staff.school_id = '{$school_id}'";

        $this->where($sql, "tb_staff.name", "name", "lk");
        $this->where($sql, "tb_staff.phone", "phone", "=");
        $this->where($sql, "tb_staff.type", "type", "=");

        if($all){
            $this->order($sql, "order.default");
            $rs = $this->getAll($sql);
        }else{
            $this->order($sql, "order.default");
            $this->paging("paging.default");
            $sqlCount = "SELECT COUNT(temp.id) FROM ({$sql}) AS temp";
            $rs = $this->paginate($sql, $sqlCount);
        }

        return $rs;
    }

    /**
     * @desc 根据职工id查询单条职工信息
     * @author ly
     * @param $staff_id
     * @return array
     */
    function getStaff($staff_id){
        $this->escape($staff_id);

        $sql = "SELECT tb_staff.*, tb_staff_class.class_id AS class_id, tb_staff_class.id AS staff_class_id FROM tb_staff
                LEFT JOIN tb_staff_class ON tb_staff_class.staff_id = tb_staff.id
                WHERE tb_staff.deleted = 0 AND tb_staff.id = '{$staff_id}'";

        return $this->getOne($sql);
    }

    /**
     * @desc 获取所有签到类型
     * @author ly
     * @return array
     */
    function getSignTypeList(){
        $sid = $_SESSION[SESSION_USER]["school_id"];
        $sql = "
            SELECT * FROM tb_sign_type
            WHERE school_id = '{$sid}'
            AND deleted = 0
            ";

        return $this->getAll($sql);

    }

    /**
     * @desc 保存、编辑职工信息
     * @author ly
     * @param $form
     * @return array
     */
    function saveStaff($form) {
        $table = "tb_staff";
        $data = array(
            'school_id' => $form['school_id'],
            'sign_type_id' => $form['sign_type_id'],
            'name' => $form['name'],
            'phone' => $form['phone'],
            'type' => $form['type'],
            'status' => $form['status'] ? true : false,
        );
        if($form['id'] > 0){
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }else{
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }

    /**
     * @desc 保存、编辑职工关联信息
     * @author ly
     * @param $form
     * @return array
     */
    function saveStaffClass($form) {
        $table = "tb_staff_class";

        $data = array(
            'school_id' => $form['school_id'],
            'class_id' => $form['class_id'],
            'staff_id' => $form['staff_id'],
        );

        if($form['id'] > 0){
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }else{
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }

    /**
     * @desc 删除
     * @author ly
     * @param $id
     * @return array
     */
    function deleteStaff($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_staff', $data, $where);
    }

    /**
     * @desc 删除职工关联信息
     * @author ly
     * @param $id
     * @return array
     */
    function deleteStaffClass($id) {
        $this->escape($id);

        $sql = "DELETE FROM tb_staff_class WHERE id = '{$id}'";

        return $this->getOne($sql);
    }

    function getStaffOptionList($school_id){
        $this->escape($school_id);

        $sql = "SELECT tb_staff.id AS value,tb_staff.name AS name FROM tb_staff
                WHERE tb_staff.deleted = 0 AND tb_staff.school_id = '{$school_id}'";


        $this->order($sql, "order.default");
        $rs = $this->getAll($sql);  
        $staffOptionList = array();
        foreach ($rs as $v) {
            $staffOptionList[$v['value']] = $v;
        } 
        return $staffOptionList;
        
    }
}

?>
