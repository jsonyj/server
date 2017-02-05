<?php

class ClassModel extends AppModel {

    function getClassList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT tb_class.*, tb_school.title AS school_title
            FROM tb_class
            LEFT JOIN tb_school ON tb_school.id = tb_class.school_id
            WHERE tb_class.deleted = 0";
        
        $this->where($sql, 'tb_class.title', 'class_title', 'lk');
        $this->where($sql, 'tb_school.title', 'school_title', 'lk');
        
        if ($all) {
            $this->order($sql, 'order.class');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.class');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getClassOptionList($schoolId) {
        $this->escape($schoolId);
        $sql = "
            SELECT id AS value, title AS name FROM tb_class
            WHERE deleted = 0 AND status = 1 AND school_id = '{$schoolId}'";

        $this->order($sql, 'order.default');
        $rs = $this->getAll($sql);

        $classList = array();
        foreach($rs as $v) {
            $classList[$v['value']] = $v;
        }

        return $classList;
    }

    function getClass($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_class
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveClass($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入名称'),
            ),
            'start' => array(
                array('isNotNull', '请输入开学日期'),
                array('isDate', '请输入正确的开学日期'),
            ),
            'school_id' => array(
                array('isNotNull', '请选择学校'),
            )
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function saveClass($form) {
        $table = 'tb_class';
        $data = array(
            'title' => $form['title'],
            'start' => $form['start'],
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
    
    function deleteClass($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_class', $data, $where);
    }

    function getClassMutilOptionList($schoolId) {
        $this->escape($schoolId);

        $schoolIdSql = '';
        if(is_array($schoolId)) {
            $schoolIdSql = " AND tb_class.school_id IN (" . implode(',', $schoolId) . ")";
        }

        $sql = "
            SELECT tb_class.id AS value, tb_class.title AS name, tb_school.title AS school_title
            FROM tb_class
            LEFT JOIN tb_school ON tb_school.id = tb_class.school_id
            WHERE tb_class.deleted = 0 AND tb_class.status = 1 {$schoolIdSql}
            ORDER BY tb_class.created DESC";

        $rs = $this->getAll($sql);

        $classList = array();
        foreach($rs as $v) {
            $classList[$v['value']] = $v;
        }

        return $classList;
    }
}

?>
