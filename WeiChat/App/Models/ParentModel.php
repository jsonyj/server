<?php
class ParentModel extends AppModel {

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
        $sql = "SELECT tb_parent.*
                FROM tb_parent
                  WHERE tb_parent.deleted = 0 AND tb_parent.status = 1 AND tb_parent.weichat_id = '{$weichatId}'
                ";

        return $this->getOne($sql);
    }

    function updateParentByWeichatId($parentId, $weichatId) {
        $table = 'tb_parent';
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
        $sql = "SELECT tb_parent.*
                FROM tb_parent
                  WHERE tb_parent.deleted = 0 AND tb_parent.status = 1 AND tb_parent.phone = '{$phone}'
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

        $sql = "SELECT tb_student_parent.*, tb_parent.name AS parent_name, tb_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_parent ON tb_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$id}'";
        return $this->getOne($sql);
    }

    function getStudentParentByPhone($studentId, $phone) {
        $this->escape($studentId);
        $this->escape($phone);

        $sql = "SELECT tb_student_parent.*, tb_parent.name AS parent_name, tb_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_parent ON tb_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}' AND tb_parent.phone = '{$phone}'";
        return $this->getOne($sql);
    }

    function getStudentParentByRelationId($studentId, $relationId) {
        $this->escape($studentId);
        $this->escape($relationId);

        $sql = "SELECT tb_student_parent.*, tb_parent.name AS parent_name, tb_parent.phone AS parent_phone
        FROM tb_student_parent
        LEFT JOIN tb_parent ON tb_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}' AND tb_student_parent.relation_id = '{$relationId}'";
        return $this->getOne($sql);
    }

    function getStudentParentListByStudentId($studentId) {
        $this->escape($studentId);

        $sql = "SELECT tb_parent.*, tb_student_parent.relation_id AS relation_id
        FROM tb_student_parent
        LEFT JOIN tb_parent ON tb_parent.id = tb_student_parent.parent_id
        WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}' ORDER BY tb_student_parent.relation_id ASC";
        return $this->getAll($sql);
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

    function saveParent($form) {
        $table = 'tb_parent';
        $data = array(
            
            'name' => $form['name'],
            'phone' => $form['phone'],
            'status' => $form['status'] ? true : false,
        );

        if(isset($form['weichat_id'])) {
            $data['weichat_id'] = $form['weichat_id'];
        }

        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }

    //根据主要家长ID获取其他家长ID的家长信息，检查是否有主从关联
    function getParentByParentId($majorParentId, $otherParentId) {
        $sql = "SELECT * FROM tb_parent WHERE id = '{$otherParentId}' AND id IN (
            SELECT parent_id FROM tb_student_parent WHERE student_id IN (
                SELECT student_id FROM tb_student_parent WHERE parent_id = '{$majorParentId}'
            )
        )";

        return $this->getOne($sql);
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
    
    function getParent($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_parent WHERE deleted = 0 AND status = 1 AND id = '{$id}'
        ";
        
        return $this->getOne($sql);
    }
    
    //爱好列表
    function getStudentHobbyList($ids){
        $this->escape($ids);
        $sql = "
                SELECT tb_hobby.*, title as name, id as value FROM tb_hobby WHERE deleted = 0
               ";
        if($ids){
            $sql .= " AND id in ({$ids})";
            
        }
        return $this->getAll($sql);
    }
    //梦想列表
    function getStudentDreamList($id){
        $this->escape($id);
        $sql = "
                SELECT tb_dream.*, id as value FROM tb_dream WHERE deleted = 0
               ";
        if($id){
            $sql .= " AND id = {$id} ";
            return $this->getOne($sql); 
            exit();
        }
        return $this->getAll($sql);
    }
    
    function updataStudentSetting($form) {
        $this->escape($form);
        $id = $form['id'];
        if($form['gender'] == '男'){
            $form['gender'] = 1;
        }else{
            $form['gender'] = 2;
        }
        $data = array(
            'birthday' => $form['birthday'], 
            'nickname' => $form['nickname'],
            'gender' => $form['gender'],
            'dream_id' => $form['dream_id'],
            'hobby' => $form['hobby'],
        );
        
        $where = " id = '{$id}' ";
        
        return $this->Update('tb_student', $data, $where);
    }



    function getOpenIdByParentPhone($phone){
        $this->escape($phone);
        $sql = "SELECT tb_weichat.openid as openid FROM  tb_weichat_bind LEFT JOIN tb_weichat ON tb_weichat_bind.weichat_id = tb_weichat.id
                WHERE tb_weichat_bind.phone =  '{$phone}' AND tb_weichat_bind.deleted = 0";
        $rs = $this->getOne($sql);
        return $rs;

    }
}

?>
