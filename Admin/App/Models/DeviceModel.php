<?php

class DeviceModel extends AppModel {

    function getDeviceList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT tb_device.*, tb_school.title AS school_title
            FROM tb_device
            LEFT JOIN tb_school ON tb_device.school_id = tb_school.id
            WHERE tb_device.deleted = 0";
        
        $this->where($sql, 'tb_school.title', 'school_title', 'lk');
        $this->where($sql, 'tb_device.no', 'no', 'lk');
        
        if ($all) {
            $this->order($sql, 'order.device');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.device');
            $this->order($sql, 'order.device');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getDeviceOptionList() {
        $this->setSearch($sh);
        $sql = "
            SELECT id AS value, title AS name FROM tb_device
            WHERE deleted = 0 AND status = 1";

        $this->order($sql, 'order.default');
        $rs = $this->getAll($sql);

        $deviceList = array();
        foreach($rs as $v) {
            $deviceList[$v['value']] = $v;
        }

        return $deviceList;
    }

    function getDevice($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_device
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveDevice($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'no' => array(
                array('isNotNull', '请输入编号'),
                array('isDeviceNoExisted', '相同编号设备已经存在'),
            ),
            'school_id' => array(
                array('isNotNull', '请选择学校'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function saveDevice($form) {
        $table = 'tb_device';
        $data = array(
            'no' => $form['no'],
            'school_id' => $form['school_id'],
            'status' => $form['status'] ? true : false,
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
    
    function deleteDevice($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_device', $data, $where);
    }

}

?>
