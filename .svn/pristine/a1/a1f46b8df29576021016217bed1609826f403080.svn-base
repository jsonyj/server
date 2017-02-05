<?php
class SchoolParentModel extends AppModel {

    function validBind($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '请输入手机号码'),
                array('isMobile', '请输入正确的手机号码'),
            ),
            'captcha' => array(
                array('isNotNull', '请输入验证码'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function getParentByWeichatId($weichatId) {
        $this->escape($weichatId);
        $sql = "SELECT tb_school_parent.*
                FROM tb_school_parent
                  WHERE tb_school_parent.deleted = 0 AND tb_school_parent.status = 1 AND tb_school_parent.weichat_id = '{$weichatId}'
                ";

        return $this->getOne($sql);
    }

    function updateParentByWeichatId($parentId, $weichatId) {
        $table = 'tb_school_parent';
        $data = array(
            'weichat_id' => $weichatId,
        );

        $this->escape($parentId);

        $where = "id = '{$parentId}'";
        $this->Update($table, $data, $where);
        return $parentId;
    }

    function getParentByPhone($phone) {
        $this->escape($phone);
        $sql = "SELECT tb_school_parent.*
                FROM tb_school_parent
                  WHERE tb_school_parent.deleted = 0 AND tb_school_parent.status = 1 AND tb_school_parent.phone = '{$phone}' LIMIT 1
                ";

        return $this->getOne($sql);
    }

    function getParentListByPhone($phone) {
        $this->escape($phone);
        $sql = "SELECT tb_school_parent.*
                FROM tb_school_parent
                  WHERE tb_school_parent.deleted = 0 AND tb_school_parent.status = 1 AND tb_school_parent.phone = '{$phone}'
                ";

        return $this->getAll($sql);
    }

    function getParentBySchoolAndPhone($schoolId, $phone) {
        $this->escape($schoolId);
        $this->escape($phone);
        $sql = "SELECT tb_school_parent.*
                FROM tb_school_parent
                  WHERE tb_school_parent.deleted = 0 AND tb_school_parent.status = 1 AND tb_school_parent.phone = '{$phone}' AND school_id = '{$schoolId}' LIMIT 1
                ";

        return $this->getOne($sql);
    }

    function validSaveParent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '请输入手机号码'),
                array('isMobile', '请输入正确的手机号码'),
            ),
            'relation_id' => array(
                array('isNotNull', '请选择与宝宝关系'),
            ),
            'student_id' => array(
                array('isNotNull', '请选择宝宝'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function getStudentParentByStudentId($id) {
        $this->escape($id);

        $sql = "SELECT tb_student_parent.*, tb_school_parent.name AS parent_name, tb_school_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$id}'";
        return $this->getOne($sql);
    }

    function getStudentParentByPhone($studentId, $phone) {
        $this->escape($studentId);
        $this->escape($phone);

        $sql = "SELECT tb_student_parent.*, tb_school_parent.name AS parent_name, tb_school_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}' AND tb_school_parent.phone = '{$phone}'";
        
        return $this->getOne($sql);
    }

    function getStudentParentByRelationId($studentId, $relationId) {
        $this->escape($studentId);
        $this->escape($relationId);
        
        $sql = "SELECT tb_student_parent.*, tb_school_parent.name AS parent_name, tb_school_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}' AND tb_student_parent.relation_id = '{$relationId}'";
        
        return $this->getOne($sql);
    }

    function getStudentParentListByStudentId($studentId, $phone=null) {
        $this->escape($studentId);

        $sql = "SELECT tb_school_parent.*, tb_school_parent.created AS parent_created, tb_student_parent.*
        FROM tb_student_parent
        LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}'";
        
        if ($phone) {
            $this->escape($phone);
            $sql .= " AND tb_school_parent.phone <> '{$phone}' ";
        }
        
        $sql .= " ORDER BY tb_student_parent.relation_id ASC ";
        
        return $this->getAll($sql);
    }

    function getSchoolParentListByStudentId($studentId){
        $this->escape($studentId);
        $sql = "SELECT * FROM tb_student_parent WHERE tb_student_parent.student_id = '{$studentId}'
        AND tb_student_parent.deleted = 0";

        return $this->getAll($sql);
    }

    function saveStudentParent($form) {
        $table = 'tb_student_parent';
        $data = array(
            'parent_id' => $form['parent_id'],
            'student_id' => $form['student_id'],
            'relation_id' => $form['relation_id'],
            'relation_title' => $form['relation_title'] ? $form['relation_title'] : '',
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
        
        if ($form['parentId'] > 0) {
            $whereId = $form['parentId'];
            $this->escape($whereId);
            $whereSql = "parent_id = '{$whereId}' AND student_id = ".$form['student_id'];
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }

    function saveParent($form) {
        $table = 'tb_school_parent';
        $data = array(
            'school_id' => $form['school_id'],
            'name' => $form['name'] ? $form['name'] : '',
            'phone' => $form['phone'],
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

    //根据主要家长Phone获取其他家长ID的家长信息，检查是否有主从关联
    function getParentByParentPhone($majorParentPhone, $otherParentId) {
        $sql = "SELECT * FROM tb_school_parent WHERE id = '{$otherParentId}' AND id IN (
            SELECT parent_id FROM tb_student_parent WHERE student_id IN (
                SELECT student_id FROM tb_student_parent WHERE parent_id IN (
                    SELECT id FROM tb_school_parent WHERE deleted = 0 AND phone = '{$majorParentPhone}'
                )
            )
        )";
        return $this->getOne($sql);
    }

    function deleteParent($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_school_parent', $data, $where);
    }

    function deleteStudentParentByParentId($parentId, $studentId) {
        $this->escape($parentId);
        $this->escape($studentId);
        $data = array('deleted' => 1);
        $where = "parent_id = '{$parentId}' AND student_id = '{$studentId}'";
        return $this->Update('tb_student_parent', $data, $where);
    }

    /*
     * @desc 根据家长Id找到家长
     * ly
     */
    function getParentRel ($studentID, $parentId) {
        $this->escape($parentId);
        $sql = "
            SELECT * FROM  tb_student_parent WHERE student_id='{$studentID}' AND parent_id = '{$parentId}' AND deleted = 0
        ";
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    function getParent($id) {
        $this->escape($id);
        
        $sql = "
            SELECT * FROM tb_school_parent WHERE id = '{$id}' AND deleted = 0
        ";
        
        return $this->getOne($sql);
    }
    
    function getParentBySchoolPhone($schoolId, $phone) {
        $this->escape($schoolId);
        $this->escape($phone);
        $sql = "SELECT tb_school_parent.*
                FROM tb_school_parent
                  WHERE tb_school_parent.deleted = 0 AND tb_school_parent.phone = '{$phone}' AND school_id = '{$schoolId}' LIMIT 1
                ";

        return $this->getOne($sql);
    }
    
    function getParentStudent($id, $studentId){
        $this->escape($id);
        $this->escape($studentId);
        
        $sql = "
            SELECT tb_school_parent.*, tb_student_parent.relation_id as value, tb_student_parent.relation_title as relation_title FROM tb_school_parent LEFT JOIN tb_student_parent ON tb_school_parent.id = tb_student_parent.parent_id AND tb_student_parent.student_id = '{$studentId}' AND tb_student_parent.deleted = 0 WHERE tb_school_parent.id = '{$id}' AND tb_school_parent.deleted = 0
        ";
        
        return $this->getOne($sql);
    }
    
}

?>
