<?php

class SchoolParentModel extends AppModel {

    function getParentList($sh, $all = false) {
        $this->setSearch($sh);

        $studentNameSql = '';
        if($studentName = $sh['student_name']) {
            $this->escape($studentName);
            $studentNameSql = " AND tb_school_parent.id IN (
                SELECT parent_id FROM tb_student_parent WHERE student_id IN (
                    SELECT id FROM tb_student WHERE name LIKE '%{$studentName}%'
                )
            ) ";
        }

        $sql = "
            SELECT tb_school_parent.*, tb_school.title AS school_title
            FROM tb_school_parent
            LEFT JOIN tb_school ON tb_school_parent.school_id = tb_school.id
            WHERE tb_school_parent.deleted = 0 {$studentNameSql}";

        $countSql = "
            SELECT COUNT(tb_school_parent.id)
            FROM tb_school_parent
            WHERE tb_school_parent.deleted = 0 {$studentNameSql}";

        $this->where($sql, 'tb_school_parent.name', 'parent_name', 'lk');
        $this->where($sql, 'tb_school_parent.phone', 'phone', 'lk');

        if ($all) {
            $this->order($sql, 'order.parent');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.parent');
            $rs = $this->paginate($sql, $countSql);
        }
        
        return $rs;
    }

    function getParent($id) {
        $this->escape($id);
        $sql = "
            SELECT tb_school_parent.*, tb_school.title AS school_title
            FROM tb_school_parent
            LEFT JOIN tb_school ON tb_school.id = tb_school_parent.school_id
            WHERE tb_school_parent.id = '{$id}' AND tb_school_parent.deleted = 0
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
                array('isMobile', '该手机号码格式不正确'),
                array('isSchoolParentNotExisted', '该手机号码家长已存在'),
            ),
        );
        
        if(!$form['id']) {
            $config['school_id'] = array(
                array('isNotNull', '请选择所属学校'),
            );
        }

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function saveParent($form) {
        $table = 'tb_school_parent';
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
            $data['school_id'] = $form['school_id'];
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    function deleteParent($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_school_parent', $data, $where);
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
            'class_id' => array(
                array('isNotNull', '请选择学生班级'),
            ),
            'student_id' => array(
                array('isNotNull', '请选择学生姓名'),
            ),
            'relation_id' => array(
                array('isNotNull', '请选择家长称谓'),
            ),
            'school_id' => array(
                array('isNotNull', '请选择所属学校'),
            ),
        );
        
        if($form['relation_id'] == PARENT_TYPE_OTHER){
            $config = array(
                'relation_title' => array(
                    array('isNotNull', '请输入家长称谓'),
                )
            );
            
        }
        
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
            case PARENT_TYPE_OTHER:
                $data['relation_title'] = $form['relation_title'];
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

    function getStudentParentByStudentId($studentId, $relationId) {
        $this->escape($studentId);
        $this->escape($relationId);

        $sql = "SELECT tb_student_parent.*, tb_school_parent.name AS parent_name, tb_school_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}' AND tb_student_parent.relation_id = '{$relationId}'";
        
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
        $this->escape($schoolId);
        $sql = "
            SELECT tb_school_parent.*, tb_school.title AS school_title, tb_class.title AS class_title, tb_student_parent.relation_title
            FROM tb_school_parent
            LEFT JOIN tb_student_parent ON tb_student_parent.parent_id = tb_school_parent.id AND tb_student_parent.student_id = '{$studentId}'
            LEFT JOIN tb_student ON tb_student.id = tb_student_parent.student_id
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_school_parent.deleted = 0 AND tb_student_parent.deleted = 0 ORDER BY tb_school_parent.created DESC ";

        $rs = $this->getAll($sql);
        return $rs;
    }
    
    /**
     * @desc 获取某学校下面需要更新的家长
     * @author lxs
     */
    function getSchoolUpdateParents($school_id, $school_key) {
        $this->escape($school_id);
        $this->escape($school_key);
        
        $sql = "
            SELECT * 
            FROM tb_student_parent
            WHERE school_id = '{$school_id}'
                AND qrcode_school_key <> '{$school_key}'
                AND deleted = 0
        ";
        
        return $this->getAll($sql);
    }
    
    /**
     * @desc 更新家长二维码
     * @author lxs
     */
    function saveParentQrcode($rel_id, $qrcode) {
        $this->escape($rel_id);
        
        $data = array(
            'qrcode_url' => $qrcode['qrcode_url'],
            'qrcode_school_key' => $qrcode['qrcode_school_key'],
        );
        $where = "id = '{$rel_id}'";
        
        return $this->Update('tb_student_parent', $data, $where);
    }

}

?>
