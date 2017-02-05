<?php

class WeichatBindModel extends AppModel {

    /**
     * @desc 获取微信号已经绑定的角色列表
     * @author lxs
     */
    function getWeichatBindList($weichat_id) {
        $this->escape($weichat_id);

        $sql = "
            SELECT * FROM tb_weichat_bind 
            WHERE weichat_id = '{$weichat_id}' AND deleted = 0
        ";

        return $this->getAll($sql);
    }

    /**
     * @desc 查询微信号已经绑定的家长
     * @author lxs
     */
    function getWeichatBindParent($weichat_id) {
        $this->escape($weichat_id);

        $sql = "
            SELECT * FROM tb_weichat_bind 
            WHERE weichat_id = '{$weichat_id}' AND deleted = 0 AND usage_type = 
        " . ACT_PARENT_ROLE;

        return $this->getOne($sql);
    }
    
    /**
     * @desc 验证电话号码绑定数据
     * @author lxs
     */
    function validBind($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'role' => array(
                array('isNotNull', '请选择绑定角色'),
                array('isInArray', '角色选择错误。', array('in_arr' => array(ACT_PARENT_ROLE, ACT_SCHOOL_GENERAL))),
            ),
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
    
    /**
     * @desc 根据电话号码查询绑定信息
     * @author lxs
     */
    function getBindInfoByPhone($phone, $type) {
        $this->escape($phone);
        $this->escape($type);
        
        $sql = "
            SELECT * FROM tb_weichat_bind WHERE phone = '{$phone}' AND deleted = 0 AND usage_type = '{$type}'
        ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据电话号码查询微信号信息
     * @author lxs
     */
    function getBindWxInfoByPhone($phone, $type) {
        $this->escape($phone);
        $this->escape($type);
        
        $sql = "
            SELECT tb_weichat_bind.*, tb_weichat.openid 
            FROM tb_weichat_bind
            LEFT JOIN tb_weichat ON tb_weichat_bind.weichat_id = tb_weichat.id AND tb_weichat.deleted = 0
            WHERE tb_weichat_bind.phone = '{$phone}' AND tb_weichat_bind.deleted = 0 AND tb_weichat_bind.usage_type = '{$type}'
        ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 更改已经绑定的微信号
     * @author lxs
     */
    function updateBindWeichatID($id, $weichat_id) {
        $this->escape($id);
        
        $data = array(
            'weichat_id' => $weichat_id
        );
        $where = "id = '{$id}' AND deleted = 0";
        return $this->Update('tb_weichat_bind', $data, $where);
    }
    
    /**
     * @desc 更改已经绑定的微信号
     * @author lxs
     */
    function saveBind($bind) {
        $table = "tb_weichat_bind";
        
        $data = array(
            'weichat_id' => $bind['weichat_id'],
            'phone' => $bind['phone'],
            'usage_type' => $bind['usage_type'],
        );
        
        if ($data['id'] > 0) {
            $id = $data['id'];
            $this->escape($id);
            $where = "id = '{$id}'";
            return $this->Update($table, $data, $where);
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    /**
     * @desc 验证输入的学生信息数据
     * @author lxs
     */
    function validBindStudent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '手机号已失效，请返回上一步重新操作'),
                array('isMobile', '请输入正确的手机号码'),
            ),
            'name' => array(
                array('isNotNull', '请输入学生姓名'),
            ),
            'birthday' => array(
                array('isNotNull', '请输入学生生日'),
                array('isDate', '请按规定格式输入学生生日'),
            ),
            'relation_type' => array(
                array('isNotNull', '请选择与学生关系'),
            ),
        );
        
        if ($form['relation_type'] == PARENT_TYPE_OTHER) {
            $config['relation_title'] =  array(
                array('isNotNull', '请输入与学生关系'),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    /**
     * @desc 绑定学生、家长、微信
     * @author lxs
     */
    function bindStudentParent($form, $add_birthday) {
        
        $parentModel = $this->getModel('Parent');
        $schoolParentModel = $this->getModel('SchoolParent');
        
        $add_birthday = (int) $add_birthday;
        $weichat_id = (int) $form['weichat_id'];
        $student_id = (int) $form['student_id'];
        $school_id = (int) $form['school_id'];
        $class_id = (int) $form['class_id'];
        $phone = trim($form['phone']);
        $student_birthday = trim($form['birthday']);
        $relation_type = (int)$form['relation_type'];
        $relation_title = trim($form['relation_title']);
        
        $this->transaction('begin');
        
        if ($add_birthday) {
            $this->escape($student_id);
            $student_data = array(
                'birthday' => $student_birthday
            );
            $student_where = "id = '{$student_id}' AND deleted = 0";
            
            if (!$this->Update('tb_student', $student_data, $student_where)) {
                $this->transaction('rollback');
                return array(
                    'code' => 1,
                    'message' => '保存失败',
                );
            }
        }
        
        //检查tb_school_parent家长是否注册
        if ($school_parent = $schoolParentModel->getParentBySchoolPhone($school_id, $phone)) {
            $school_parent_id = $school_parent['id'];
        }
        else {
            $school_parent_data = array(
                'school_id' => $school_id,
                'phone' => $phone,
                'type' => 0,
                'status' => 1,
                'created' => NOW,
            );
            
            if (!$school_parent_id = $this->Insert('tb_school_parent', $school_parent_data)) {
                $this->transaction('rollback');
                return array(
                    'code' => 1,
                    'message' => '保存失败',
                );
            }
        }
        
        //检查学生与家长是否绑定
        if ($relation_info = $this->getParentRel($student_id, $school_parent_id)) {
            $student_parent_data = array(
                'relation_id' => $relation_type,
                'relation_title' => $relation_title,
            );
            $relation_id = $relation_info['id'];
            $relation_where = "id = '{$relation_id}'";
            
            if (!$this->Update('tb_student_parent', $student_parent_data, $relation_where)) {
                $this->transaction('rollback');
                return array(
                    'code' => 1,
                    'message' => '保存失败',
                );
            }
        }
        else {
            $student_parent_data = array(
                'school_id' => $school_id,
                'class_id' => $class_id,
                'student_id' => $student_id,
                'parent_id' => $school_parent_id,
                'relation_id' => $relation_type,
                'relation_title' => $relation_title,
                'created' => NOW
            );
            
            if (!$relation_id = $this->Insert('tb_student_parent', $student_parent_data)) {
                $this->transaction('rollback');
                return array(
                    'code' => 1,
                    'message' => '保存失败',
                );
            }
        }
        
        //检查家长tb_parent表是否有记录
        if ($parent = $parentModel->getParentByPhone($phone)) {
            $parent_id = $parent['id'];
            $parent_data = array(
                'weichat_id' => $weichat_id,
            );
            $parent_where = "id = '{$parent_id}'";
            if (!$this->Update('tb_parent', $parent_data, $parent_where)) {
                $this->transaction('rollback');
                return array(
                    'code' => 1,
                    'message' => '保存失败',
                );
            }
        }
        else {
            $parent_data = array(
                'weichat_id' => $weichat_id,
                'phone' => $phone,
                'status' => 1,
                'created' => NOW,
            );
            
            if (!$parent_id = $this->Insert('tb_parent', $parent_data)) {
                $this->transaction('rollback');
                return array(
                    'code' => 1,
                    'message' => '保存失败',
                );
            }
        }
        
        //查询家长是否已经绑定
        if ($bind_parent = $this->getBindInfoByPhone($phone, ACT_PARENT_ROLE)) {
            if ($this->updateBindWeichatID($bind_parent['id'], $weichat_id)) {
                $this->transaction('commit');
                return array(
                    'code' => 0,
                    'message' => '保存成功',
                );
            }
        }
        else {
            $bind_parent = array(
                'weichat_id' => $weichat_id,
                'phone' => $phone,
                'usage_type' => ACT_PARENT_ROLE,
            );
            if ($this->saveBind($bind_parent)) {
                $this->transaction('commit');
                return array(
                    'code' => 0,
                    'message' => '保存成功',
                );
            }
        }
        
        $this->transaction('rollback');
        return array(
            'code' => 1,
            'message' => '保存失败',
        );
    }
    
    /**
     * @desc 验证学生某个家长的手机号码
     * @author lxs
     */
    function validParentPhone($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '手机号已失效，请返回上一步重新操作'),
                array('isMobile', '请输入正确的手机号码'),
            ),
            'phone_middle' => array(
                array('isNotNull', '请输入相关家长的手机号码'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    /**
     * @desc 查询学生与家长关系
     * @author lxs
     */
    function getParentRel ($studentID, $parentId) {
        $this->escape($parentId);
        $sql = "
            SELECT * FROM  tb_student_parent WHERE student_id='{$studentID}' AND parent_id = '{$parentId}' AND deleted = 0
        ";
        return $this->getOne($sql);
    }
    
    /**
     * @desc 获取除特定微信外其他家长OPENID
     * @author lxs
     */
    function getParentOpenidList($student_id, $weichat_id=0) {
        $student_id = (int) $student_id;
        $weichat_id = (int) $weichat_id;
        
        $sql = "
            SELECT DISTINCT tb_weichat.openid
            FROM tb_student_parent
            LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id
            LEFT JOIN tb_parent ON tb_parent.phone = tb_school_parent.phone AND tb_parent.deleted = 0
            LEFT JOIN tb_weichat ON tb_parent.weichat_id = tb_weichat.id
            WHERE tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$student_id}'
        ";
        
        if ($weichat_id) {
            $sql .= " AND tb_weichat.id <> '{$weichat_id}' ";
        }
        
        return $this->getAll($sql);
    }
    
    /**
     * @desc 检查学生的家长角色是否已绑定
     * @author lxs
     */
    function isStudentBindedRelation($student_id, $relation_title, $phone) {
        $this->escape($student_id);
        $this->escape($relation_title);
        $this->escape($phone);
        
        $sql = "
            SELECT tb_student_parent.* 
            FROM tb_student_parent
            LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id 
            WHERE tb_student_parent.student_id = '{$student_id}' AND tb_student_parent.deleted = 0 AND tb_student_parent.relation_title = '{$relation_title}' AND tb_school_parent.deleted = 0 AND tb_school_parent.phone <> '{$phone}'
        ";
        
        $rs = $this->getOne($sql);
        return $rs ? true : false;
    }
    
    /**
     * @desc 获取学生家长申请绑定的信息
     * @author lxs
     */
    function getBindParentApply($id) {
        $this->escape($id);
        
        $sql = "
            SELECT * FROM tb_bindparent_apply WHERE id = '{$id}' AND deleted = 0
        ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 保存学生家长申请绑定的信息
     * @author lxs
     */
    function saveBindParentApply($bind) {
        $data = array(
            'weichat_id' => $bind['weichat_id'],
            'student_id' => $bind['student_id'],
            'phone' => $bind['phone'],
            'relation_id' => $bind['relation_id'],
            'relation_title' => $bind['relation_title'],
            'bind_key' => $bind['bind_key'],
            'status' => $bind['status'],
        );
        
        if ($id = $bind['id']) {
            $this->escape($id);
            $where = "id = '{$id}'";
            return $this->Update('tb_bindparent_apply', $data, $where);
        }
        else {
            $data['created'] = NOW;
            return $this->Insert('tb_bindparent_apply', $data);
        }
        
    }
    
    /**
     * @desc 修改学生家长申请绑定状态
     * @author lxs
     */
    function saveBindApplyStatus($id, $status) {
        $this->escape($id);
        
        $data = array(
            'status' => $status,
        );
        
        $where = "id = '{$id}'";
        return $this->Update('tb_bindparent_apply', $data, $where);
    }
    
    /**
     * @desc 获取微信号信息
     * @anthor lxs
     */
    function getWeichat($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_weichat
            WHERE id = '{$id}' AND deleted = 0
        ";
        
        return $this->getOne($sql);
    }
    
}

?>
