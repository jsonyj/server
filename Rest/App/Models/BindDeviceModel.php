<?php
/**
 * @description 绑定客户端相关Model
 * @author   wei
 */
class BindDeviceModel extends AppModel {

    /**
     * @desc 验证Rfid数据
     * @author wei
     */
    function validgetBindParentRfid($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'parent_id' => array(
                array('isNotNull', '请输入家长ID。'),
            ),
            'student_id' => array(
                array('isNotNull', '请输入学生ID。'),
            ),
            'rfid' => array(
                array('isNotNull', '请输入IC卡号。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    /**
     * @desc  判断角色绑定ic卡信息
     * @author   wei
     */
    function validSchoolRole($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'id' => array(
                array('isNotNull', '请输入角色ID。'),
            ),
            'rfid' => array(
                array('isNotNull', '请输入绑定IC卡号。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    /**
     * @desc  修改Rfid
     * @author   wei
     */
     function setBindParentRfid($form) {
        $data = array(
            'rfid' => $form['rfid']
        );
        $where = "deleted = 0 AND parent_id = '" . $form["parent_id"] . "' AND student_id = '" . $form["student_id"] . "'";
        return $this->Update('tb_student_parent', $data, $where);
    }
    
    /**
     * @desc  修改职工Rfid
     * @author   wei
     */
    function sverScholRole($form){
        $table = "tb_staff";
        $data = array(
            'rfid' => $form['rfid']
        );
        $where = "deleted = 0 AND id = '" . $form["id"] . "' ";
        return $this->Update($table, $data, $where);
        
    }
    
    function getScholRole($staff_id){
        $this->escape($staff_id);
        
        $sql = "SELECT * FROM tb_staff  WHERE id = '{$staff_id}' AND deleted = 0  AND status = 1 ";
        
        return $this->getOne($sql) ? true : false;
        
    }

}

?>
