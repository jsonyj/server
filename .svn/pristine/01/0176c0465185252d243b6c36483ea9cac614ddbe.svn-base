<?php

class ParentModel extends AppModel {

    function getParentList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT tb_parent.*, tb_school.title AS school_title, tb_class.title AS class_title
            FROM tb_parent
            LEFT JOIN tb_student_parent ON tb_student_parent.parent_id = tb_parent.id
            LEFT JOIN tb_student ON tb_student.id = tb_student_parent.student_id
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_parent.deleted = 0 ";
        
        $this->where($sql, 'tb_parent.name', 'parent_name', 'lk');
        $this->where($sql, 'tb_student.name', 'student_name', 'lk');
        $this->where($sql, 'tb_parent.phone', 'phone', 'lk');

        if ($all) {
            $this->order($sql, 'order.parent');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.parent');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getParent($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_parent
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveParent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'name' => array(
                array('isNotNull', '请输入姓名'),
            ),
            'phone' => array(
                array('isNotNull', '请输入手机号码'),
                array('isPhoneNo', '该手机号码格式不正确'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function saveParent($form) {
        $table = 'tb_parent';
        $data = array(
            'name' => $form['name'],
            'phone' => $form['phone'],
            'type' => $form['type'] ? $form['type'] : 0,
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
    
    function deleteParent($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_parent', $data, $where);
    }

    function deleteStudentParentByParentId($parentId) {
        $this->escape($parentId);
        $data = array('deleted' => 1);
        $where = "parent_id = '{$parentId}'";
        return $this->Update('tb_student_parent', $data, $where);
    }

    function validSaveStudentParent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'parent_id' => array(
                array('isNotNull', '请选择学生家长'),
            ),
            'school_id' => array(
                array('isNotNull', '请选择学生学校'),
            ),
            'class_id' => array(
                array('isNotNull', '请选择学生班级'),
            ),
            'student_id' => array(
                array('isNotNull', '请选择学生姓名'),
            ),
            'relation_id' => array(
                array('isNotNull', '请选择家长称谓'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function getStudentParent($parentId, $studentId) {
        $this->escape($parentId);
        $this->escape($studentId);

        $sql = "SELECT * FROM tb_student_parent WHERE deleted = 0 AND parent_id = '{$parentId}' AND student_id = '{$studentId}'";
        return $this->getOne($sql);
    }

    function saveStudentParent($form) {
        $table = 'tb_student_parent';
        $data = array(
            'parent_id' => $form['parent_id'],
            'student_id' => $form['student_id'],
            'relation_id' => $form['relation_id'],
            'school_id' => $form['school_id'],
            'class_id' => $form['class_id'],
        );
        
        switch($form['relation_id']) {
            case PARENT_TYPE_FATHER:
                $data['relation_title'] = '爸爸';
                break;
            case PARENT_TYPE_MOTHER:
                $data['relation_title'] = '妈妈';
                break;
            case PARENT_TYPE_GRANDPA:
                $data['relation_title'] = '爷爷';
                break;
            case PARENT_TYPE_GRANDMA:
                $data['relation_title'] = '奶奶';
                break;
            case PARENT_TYPE_GRANDFATHER:
                $data['relation_title'] = '外公';
                break;
            case PARENT_TYPE_GRANDMOTHER:
                $data['relation_title'] = '外婆';
                break;
            default:
                break;
        }

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

    function getParentStudentList($parentId) {
        $this->escape($parentId);
        $this->escape($studentId);

        $sql = "SELECT
                  tb_student_parent.*, tb_student.name AS student_name, tb_student.gender AS student_gender, tb_student.birthday AS student_birthday,
                  tb_school.title AS school_title, tb_class.title AS class_title
                FROM tb_student_parent
                LEFT JOIN tb_student ON tb_student.id = tb_student_parent.student_id
                LEFT JOIN tb_school ON tb_school.id = tb_student_parent.school_id
                LEFT JOIN tb_class ON tb_class.id = tb_student_parent.class_id
                WHERE tb_student_parent.deleted = 0 AND tb_student_parent.parent_id = '{$parentId}'";

        return $this->getAll($sql);
    }

    function getStudentParentById($id) {
        $this->escape($id);

        $sql = "SELECT * FROM tb_student_parent WHERE deleted = 0 AND id = '{$id}'";
        return $this->getOne($sql);
    }

    function getStudentParentByStudentId($id) {
        $this->escape($id);

        $sql = "SELECT tb_student_parent.*, tb_parent.name AS parent_name, tb_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_parent ON tb_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$id}'";
        return $this->getOne($sql);
    }

    function deleteStudentParent($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_student_parent', $data, $where);
    }

    function getParentListByStudentId($studentId) {
        $this->escape($studentId);
        $sql = "
            SELECT tb_parent.*, tb_school.title AS school_title, tb_class.title AS class_title, tb_student_parent.relation_id
            FROM tb_parent
            LEFT JOIN tb_student_parent ON tb_student_parent.parent_id = tb_parent.id AND tb_student_parent.student_id = '{$studentId}'
            LEFT JOIN tb_student ON tb_student.id = tb_student_parent.student_id
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_parent.deleted = 0 AND tb_student_parent.deleted = 0 ORDER BY tb_parent.created DESC ";
        
        $rs = $this->getAll($sql);
        return $rs;
    }
    
    function deleteParentByPhone($phone) {
        $this->escape($phone);
        $data = array('deleted' => 1);
        $where = "phone = '{$phone}'";
        return $this->Update('tb_parent', $data, $where);
    }

}

?>
