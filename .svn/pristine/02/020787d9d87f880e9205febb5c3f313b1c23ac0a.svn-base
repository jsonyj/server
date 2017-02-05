<?php

class SchoolModel extends AppModel {

    function getSchoolList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_school
            WHERE deleted = 0";
        
        $this->where($sql, 'title', 'title', 'lk');
        $this->where($sql, 'address', 'address', 'lk');
        $this->where($sql, 'phone', 'phone', 'lk');
        
        if ($all) {
            $this->order($sql, 'order.default');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.default');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getSchoolOptionList() {
        $this->setSearch($sh);
        $sql = "
            SELECT id AS value, title AS name FROM tb_school
            WHERE deleted = 0 AND status = 1";

        $this->order($sql, 'order.default');
        $rs = $this->getAll($sql);

        $schoolList = array();
        foreach($rs as $v) {
            $schoolList[$v['value']] = $v;
        }

        return $schoolList;
    }

    function getSchool($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_school
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveSchool($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入名称'),
            ),
            'phone' => array(
                array('isNotNull', '请输入电话'),
            ),
            'address' => array(
                array('isNotNull', '请输入地址'),
            )
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function saveSchool($form) {
        $table = 'tb_school';
        $data = array(
            'title' => $form['title'],
            'phone' => $form['phone'],
            'address' => $form['address'],
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
    
    function deleteSchool($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_school', $data, $where);
    }

}

?>
