<?php

class SchoolAdminModel extends AppModel {

    function getAdmin($schoolId) {
        $this->escape($schoolId);
        $sql = "
            SELECT * FROM tb_school_admin
            WHERE school_id = '{$schoolId}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }

    function saveAdmin($form) {
        $table = 'tb_school_admin';
        $data = array(
            'school_id' => $form['school_id'],
            'login' => trim($form['login']),
            'name' => trim($form['name']),
            'status' => $form['status'] ? true : false,
        );
        
        if (strlen(trim($form['password'])) > 0) {
            $data['password'] = md5(trim($form['password']));
        }

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $where = "id = '{$whereId}'";
            $this->Update($table, $data, $where);
            return $form['id'];
        } else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
}

?>
