<?php

class StudentModel extends AppModel {

    function getStudentList($schoolId, $timestamp) {
        $this->escape($schoolId);
        $this->escape($timestamp);

        $timestampSql = '';
        if($timestamp) {
            $timestampSql = " AND tb_student.updated >= '{$timestamp}'";
        }

        $sql = "
            SELECT tb_student.*, tb_class.title AS class_title
            FROM tb_student
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.deleted = 0 AND tb_student.school_id = '{$schoolId}' {$timestampSql} ORDER BY tb_student.updated DESC";

        $rs = $this->getAll($sql);

        return $rs;
    }

    function getStudent($schoolId, $id) {
        $this->escape($id);
        $this->escape($schoolId);
        $sql = "
            SELECT * FROM tb_student
            WHERE id = '{$id}' AND deleted = 0 AND school_id = '{$schoolId}'
            LIMIT 1
        ";
        return $this->getOne($sql);
    }
    
    function getStudentOne( $id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_student
            WHERE id = '{$id}' AND deleted = 0 AND deleted = 0
            LIMIT 1
        ";
        return $this->getOne($sql);
    }
    
    function getSchoolParent($id) {
        $this->escape($id);     
        $sql = "
            SELECT * FROM tb_school_parent
            WHERE id = '{$id}' AND deleted = 0  LIMIT 1
        ";
        
        return $this->getOne($sql);
    }    
    
    function validGetStudentList($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function validPutStudentDetection($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'id' => array(
                array('isNotNull', '请输入学生ID'),
            ),
            'height' => array(
                array('isNotNull', '请输入身高'),
            ),
            'weight' => array(
                array('isNotNull', '请输入体重'),
            ),
            'temperature' => array(
                array('isNotNull', '请输入体温'),
            ),
            'env_temperature' => array(
                array('isNotNull', '请输入环境温度'),
            ),
            'raw_temperature' => array(
                array('isNotNull', '请输入原始温度'),
            ),
            'temperature_threshold' => array(
                array('isNotNull', '请输入体温阈值'),
            ),
        );
        if($form['create_time']){
            $config = array(
                'create_time' => array( 
                    array('isDateTimeTwo', '上传时间格式不正确。'),
                ),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function getStudentOptionList($classId) {
        $this->escape($classId);
        $sql = "
            SELECT id AS value, name FROM tb_student
            WHERE deleted = 0 AND status = 1 AND class_id = '{$classId}'";

        $this->order($sql, 'order.default');
        $rs = $this->getAll($sql);

        $classList = array();
        foreach($rs as $v) {
            $classList[$v['value']] = $v;
        }

        return $classList;
    }
	// ／**废弃使用带first lastest值插入
    function saveStudentDetection($form) {
        $table = 'tb_student_detection';
        $data = array(
            'student_id' => $form['student_id'],
            'height' => $form['height'],
            'weight' => $form['weight'],
            'temperature' => $form['temperature'],
            'state_type' => $form['state_type'],
            'recognition_type' => $form['recognition_type'],
            'env_temperature' => $form['env_temperature'],
            'raw_temperature' => $form['raw_temperature'],
            'temperature_threshold' => $form['temperature_threshold'],
            'terminal_img_id' => $form['terminal_img_id'],
            'file_img_id' => $form['file_img_id'],
            'org_img_url' => $form['org_img_url'],
        );

        $data['created'] = $form['create_time'] ? date("Y-m-d H:i:s", strtotime($form['create_time'])) : NOW;
        return $this->Insert($table, $data);
    }
    // ／**带first lastest=1值插入 *／
    function saveStudentDetectionWithFirst($form,$first) {
        $table = 'tb_student_detection';
        $data = array(
            'student_id' => $form['student_id'],
            'height' => $form['height'],
            'weight' => $form['weight'],
            'temperature' => $form['temperature'],
            'state_type' => $form['state_type'],
            'recognition_type' => $form['recognition_type'],
            'env_temperature' => $form['env_temperature'],
            'raw_temperature' => $form['raw_temperature'],
            'temperature_threshold' => $form['temperature_threshold'],
            'terminal_img_id' => $form['terminal_img_id'],
            'file_img_id' => $form['file_img_id'],
            'org_img_url' => $form['org_img_url'],
            'first' => $first,
            'lastest' => 1,
        );

        $data['created'] = $form['create_time'] ? date("Y-m-d H:i:s", strtotime($form['create_time'])) : NOW;
        return $this->Insert($table, $data);
    }
    

    function validUploadImg($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'id' => array(
                array('isNotNull', '请输入ID'),
            ),
            'type' => array(
                array('isNotNull', '请输入类型'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function getStudentParentList($studentId) {
        $this->escape($studentId);
// 执行效率太低，4.7秒
//         $sql = "
//             SELECT tb_parent.*, tb_weichat.openid
//             FROM tb_parent
//             LEFT JOIN tb_weichat ON tb_weichat.id = tb_parent.weichat_id
//             WHERE tb_parent.deleted = 0 AND tb_parent.phone IN (
//                 SELECT phone FROM tb_school_parent WHERE id IN 
//                 (
//                   SELECT parent_id FROM tb_student_parent WHERE deleted = 0 AND student_id = '{$studentId}'
//                 )
//             )";
// 
// 20170120 stephen.wang 优化为 0.05秒
        $sql = "select * from (SELECT sc.phone FROM tb_school_parent sc left join tb_student_parent st  on sc.id=st.parent_id where sc.deleted=0 and st.deleted=0 and st.student_id = '{$studentId}') as tmp1 , (SELECT tb_parent.*, tb_weichat.openid
        		FROM tb_parent
        		LEFT JOIN tb_weichat ON tb_weichat.id = tb_parent.weichat_id
        		WHERE tb_parent.deleted = 0 ) as tmp2 where tmp1.phone=tmp2.phone";

        $rs = $this->getAll($sql);
        
        return $rs;
    }
	/**废弃，select 循环嵌套太影响效率*/
    function updateStudentDetectionOrder($studentId, $date) {
        $this->escape($studentId);
        $this->escape($date);

        $sql = "UPDATE tb_student_detection SET lastest = 0 WHERE deleted = 0 AND student_id = '{$studentId}' AND DATE_FORMAT(created, '%Y-%m-%d') = '{$date}'";
        $this->Execute($sql);

        $sql = "UPDATE tb_student_detection SET lastest = 1 WHERE id = (SELECT T.id FROM (
                  SELECT id FROM `tb_student_detection` WHERE DATE_FORMAT(created, '%Y-%m-%d') = '{$date}' AND student_id = '{$studentId}' ORDER BY created DESC LIMIT 1
               ) AS T)";

        $this->Execute($sql);

        $sql = "UPDATE tb_student_detection SET first = 0 WHERE deleted = 0 AND student_id = '{$studentId}' AND DATE_FORMAT(created, '%Y-%m-%d') = '{$date}'";
        $this->Execute($sql);

        $sql = "UPDATE tb_student_detection SET first = 1 WHERE id = (SELECT T.id FROM (
                  SELECT id FROM `tb_student_detection` WHERE DATE_FORMAT(created, '%Y-%m-%d') = '{$date}' AND student_id = '{$studentId}' ORDER BY created ASC LIMIT 1
               ) AS T)";

        $this->Execute($sql);
    }
    /**更新上一条的字段Lastest值 条件Lastest=1 2017.1.17 */
    function updateLastLastest($studentId, $date) {
        $this->escape($studentId);
        $this->escape($date);

        $sql = "UPDATE tb_student_detection SET lastest = 0 WHERE deleted = 0 AND lastest = 1 And student_id = '{$studentId}' AND DATE_FORMAT(created, '%Y-%m-%d') = '{$date}'";
        $this->Execute($sql);
    }
   	/**查当前学生当日检测次数 2017.1.17  */
    function getStudentDetectionCount($studentId, $date) {
       	$this->escape($studentId);
        $this->escape($date);
        $sql = "select count(id) as count FROM tb_student_detection WHERE deleted = 0 AND student_id = '{$studentId}' AND DATE_FORMAT(created, '%Y-%m-%d') = '{$date}'";
        $rs = $this->getOne($sql);
        return isset($rs['count']) ? $rs['count'] : 0;
    }
    
    function validGetSchooID($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'school_id' => array(
                array('isNotNull', '请输入学校ID'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    /**
     * @desc 查询当前学生下的学生信息
     * @author   wei
     */
    function getSchoolStudentParentList($schoolId, $timestamp) {
        $this->escape($schoolId);
        $this->escape($timestamp);
        $timestampSql = '';
        if($timestamp) {
            $timestampSql = " AND  (tb_student.updated >= '{$timestamp}' or tb_student_parent.updated >='{$timestamp}' or tb_school_parent.updated >= '{$timestamp}')";
        }

        $sql = "
            SELECT tb_student.* FROM tb_student 
            LEFT JOIN tb_student_parent ON  tb_student.id = tb_student_parent.student_id AND tb_student_parent.deleted = 0 
            LEFT JOIN tb_school_parent ON tb_student_parent.parent_id = tb_school_parent.id AND tb_school_parent.deleted = 0 WHERE  tb_student.deleted = 0 AND tb_student.school_id = '{$schoolId}' {$timestampSql} ORDER BY tb_student.updated DESC";
        
        $rs = $this->getAll($sql);
        return $rs;
    }
    /**
     * @desc 查询当前学生下的家长信息
     * @author   wei
     */
    function getParentList($student_id){
        $this->escape($student_id); 
        
        $sql = "SELECT tb_student_parent.student_id as student_id, tb_student_parent.relation_title, tb_student_parent.relation_id, tb_student_parent.rfid ,  tb_school_parent.* FROM tb_student_parent LEFT JOIN tb_school_parent ON tb_student_parent.parent_id = tb_school_parent.id AND tb_school_parent.deleted = 0 WHERE tb_student_parent.student_id = '{$student_id}'  AND tb_student_parent.deleted = 0 ORDER BY tb_student_parent.updated DESC";
        
        $rs = $this->getAll($sql);
        return $rs; 
    }

     /**
     * @desc 查询当前学生所在的学习和班级
     * @author   wei
     */
     function getSchoolClass($school_id, $class_id){
         $this->escape($school_id); 
         $this->escape($class_id); 
         $sql = "SELECT tb_class.title AS class_title, tb_class.start AS class_start, tb_school.* 
                FROM tb_class LEFT JOIN tb_school ON tb_class.school_id = tb_school.id AND tb_school.deleted = 0 
                WHERE tb_class.deleted = 0 AND  tb_class.school_id = '{$school_id}' AND tb_class.id = '{$class_id}'  ";
        $rs = $this->getOne($sql);
        return $rs;
     }
     
     
     function validSubmitTakeAway($form, &$errors){
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'student_id' => array(
                array('isNotNull', '请输入学生ID'),
            ),
            'parent_id' => array(
                array('isNotNull', '请输入父母ID'),
            ),
        );
        
        if($form['create_time']){
            $config = array(
                'create_time' => array( 
                    array('isDateTimeTwo', '上传时间格式不正确。'),
                ),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
         
     }
     /**
     * @desc 提交学生已被接走信息
     * @author   wei
     */
     function saveStudentTakeAwayFile($form){
        $table = 'tb_student_away_record';
        
        $data = array(
            'student_id' => $form['student_id'],
            'parent_id' => $form['parent_id'],
            'file_img_id' => $form['file_img_id'],
            'img' => $form['img'],
            'sub_img' => $form['sub_img'],
            'file_sub_img_id' => $form['file_sub_img_id'],
        );

        $data['created'] = $form['create_time'] ? date("Y-m-d H:i:s", strtotime($form['create_time'])) : NOW;
        return $this->Insert($table, $data);
         
     }

     function saveStudentTakeAwayFile1($form){
        $table = 'tb_student_away_record';
        
        $data = array(
            'student_id' => $form['student_id'],
            'parent_id' => $form['parent_id'],
            // 'file_img_id' => $form['file_img_id'],
            'img' => $form['img'],
        );

        $data['created'] = $form['create_time'] ? date("Y-m-d H:i:s", strtotime($form['create_time'])) : NOW;
        return $this->Insert($table, $data);
         
     }

     
     function validSchoolKey($form, &$errors){
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'school_id' => array(
                array('isNotNull', '学校ID不正确。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
     }
     
     /**
     * @desc 获取学校最新key值
     * @author   wei
     */
    function getSchoolKey($school_id) {
        $this->escape($school_id);
        $sql = "
            SELECT * FROM tb_school
            WHERE id = '{$school_id}' AND deleted = 0
            LIMIT 1
        ";
        $school = $this->getOne($sql);
        
        if($school['key'] != $school['key_new']){
            $data = array(
                'key' => $school['key_new'],
                'key_active_time' => NOW,
            );
            $where = " deleted = 0  AND id = "  . $school_id;
            $this->Update('tb_school', $data, $where);
            
            $sql = "
                SELECT * FROM tb_school
                WHERE id = '{$school_id}' AND deleted = 0
                LIMIT 1
            ";
            $school = $this->getOne($sql);
        }
        return  $school; 
    }

    function getParentRel ($studentID, $parentId) {
        $this->escape($parentId);
        $sql = "
            SELECT * FROM  tb_student_parent WHERE student_id='{$studentID}' AND parent_id = '{$parentId}' AND deleted = 0
        ";
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    /**
     * @desc 判断孩子今天是否被接走
     * @author lxs
     */
    function isTodayTakeAway($student_id) {
        $this->escape($student_id);
        
        $sql = "
            SELECT * FROM tb_student_away_record WHERE student_id = '{$student_id}'
            AND date(created) = curdate()
        ";
        
        $rs = $this->getOne($sql);
        return $rs ? $rs : false;
    }
    
    /**
     * @desc 获取认领数据
     * @author wei
     */
    function getStudentClaimList($school_id, $timestamp){
        $this->escape($school_id);
        $this->escape($timestamp);
        $timestampSql = '';
        if($timestamp) {
            $time = date('Y-m-d H:i:s',strtotime($timestamp));
            $timestampSql = " AND  DATE_FORMAT(tb_detection_claim.created,'%Y-%m-%d %H:%i:%s') >= '{$time}' ";
        }
        $sql = "SELECT tb_detection_claim.* FROM tb_detection_claim LEFT JOIN tb_student 
                ON tb_detection_claim.student_id = tb_student.id AND tb_student.deleted = 0 AND tb_student.status = 1 
                WHERE tb_detection_claim.deleted = 0  AND tb_student.school_id = '{$school_id}' {$timestampSql}  order by tb_detection_claim.created  DESC ";
        
        $rs = $this->getAll($sql);
        return $rs;
    }
    
    /**
     * @desc 获取认领数据同步终端状态修改
     * @author wei
     */
    function getDetectionClaimWhether($id){
        $this->escape($id);
        
        $sql = "UPDATE tb_detection_claim SET whether_claim = 1 WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    function validPutDetectionClaim($form, &$errors){
        $validator = $this->load(APP_CORE, 'AppValidator');
       $config = array(
            'height' => array(
                array('isNotNull', '请输入身高'),
            ),
            'weight' => array(
                array('isNotNull', '请输入体重'),
            ),
            'temperature' => array(
                array('isNotNull', '请输入体温'),
            ),
            'env_temperature' => array(
                array('isNotNull', '请输入环境温度'),
            ),
            'raw_temperature' => array(
                array('isNotNull', '请输入原始温度'),
            ),
            'temperature_threshold' => array(
                array('isNotNull', '请输入体温阈值'),
            ),
        );
        
        if($form['create_time']){
            $config = array(
                'create_time' => array( 
                    array('isDateTimeTwo', '上传时间格式不正确。'),
                ),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
        
    }
    /**
     * @desc 未认领数据新增
     * @author wei
     */
    function saveStudentDetectionClaim($form) {
        $table = 'tb_student_detection';
        $data = array(
            'student_id' => $form['student_id'] ? $form['student_id'] : '0',
            'height' => $form['height'],
            'weight' => $form['weight'],
            'temperature' => $form['temperature'],
            'state_type' => $form['state_type'],
            'recognition_type' => $form['recognition_type'],
            'env_temperature' => $form['env_temperature'],
            'raw_temperature' => $form['raw_temperature'],
            'temperature_threshold' => $form['temperature_threshold'],
            'terminal_img_id' => $form['terminal_img_id'],
            'file_img_id' => $form['file_img_id'],
            'org_img_url' => $form['org_img_url'],
            'status' => DETECTION_STATUS_RETURN,
        );
        
        $data['created'] = $form['create_time'] ? date("Y-m-d H:i:s", strtotime($form['create_time'])) : NOW;
        return $this->Insert($table, $data);
    }
    /**
     * @desc 未认领数据新增
     * @author wei
     */
    function saveDetectionClaim($form){
        $table = 'tb_detection_claim';
        $data = array(
            'detection_id' => $form['detection_id'],
            'terminal_img_id' => $form['terminal_img_id'],
            'school_id' => $form['school_id'],
            'student_id' => $form['student_id'] ? $form['student_id']  :"0",
            'op_uid' =>  $form['op_uid'] ? $form['op_uid'] : '0',
            'op_utype' => ACT_API,
            'type' => DETECTION_STATUS_RETURN,
            'status' => APP_UNIFIED_TRUE,
        );

        $data['created'] = NOW;
        return $this->Insert($table, $data);
        
    }
    
    /**
     * @desc 验证学生信息
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validStudent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'name' => array(
                array('isNotNull', '请输入姓名'),
                array('isClassStudentNotExisted', '相同姓名学生已经存在', array('name' => $form['name'])),
            ),
            'gender' => array(
                array('isNotNull', '请选择性别'),
            ),
            'school_id' => array(
                array('isNotNull', '请选择学校'),
                array('isNotSchoolExist', '学校不存在，请重新选择'),
            ),
            'class_id' => array(
                array('isNotNull', '请选择班级'),
                array('isNotSchoolClassExist', '学校班级不存在，请重新选择', $form['school_id']),
            ),
            'birthday' => array(
                array('isNotNull', '请输入生日'),
                array('isDate2', '请输入正确的生日'),
            )
        );

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    /**
     * @desc 保存学生信息
     * @author ly
     * @param $form
     * @return unknown|boolean
     */
    function saveStudent($form) {
        $table = 'tb_student';
        $data = array(
            'name' => $form['name'],
            'birthday' => $form['birthday'] ? date('Y-m-d', strtotime($form['birthday'])) : '',
            'gender' => $form['gender'],
            'dream_id' => $gender['gender'] == 1 ? 8 : 3,
            'school_id' => $form['school_id'],
            'class_id' => $form['class_id'],
            'status' => $form['status'] ? false : true,
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
    
    /**
     * @desc 保存学生关联家长信息
     * @author ly
     * @param $form
     * @return unknown|boolean
     */
    function saveStudentParent($form){
        $table = 'tb_student_parent';
        $data = array(
            'school_id' => $form['school_id'],
            'class_id' => $form['class_id'],
            'student_id' => $form['student_id'],
            'parent_id' => $form['parent_id'],
            'relation_id' => $form['relation_id'],
            'relation_title' => $form['relation_title'],
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
    
    /**
     * @desc 保存学校关联家长信息
     * @author wei
     * @param $form
     * @return unknown|boolean
     */
    function saveSchoolParent($form){
        $table = 'tb_school_parent';
        $data = array(
            'school_id' => $form['school_id'],
            'name' => $form['name']? $form['name'] : '',
            'phone' => $form['phone'],
            'type' => $form['type'],
            'status' => $form['status'],
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
    
    /**
     * @desc 验证家长电话
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validParentPhone($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '电话不能为空'),
                array('isPhoneNo', '电话格式不正确'),
            ),
            'relation_id' => array(
                array('isNotRelationExist', '关系ID：'. $form['relation_id'] .'家长已关联该学生', $form),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    
    /**
     * @desc 验证关系家长电话格式
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validRelationFromat($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '电话不能为空'),
                array('isPhoneNo', '电话格式不正确'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    
    /**
     * @desc 根据学生id获取学生信息
     * @author ly
     * @param $student
     */
    function getStudentClass($student_id) {
        $this->escape($student_id);
        
        $sql = "SELECT tb_student.* FROM tb_student 
                WHERE tb_student.deleted = 0 AND tb_student.id = '{$student_id}'";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 验证学生，家长
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validStudentParent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
    
        $config = array(
            'student_id' => array(
                array('isNotNull', '学生不能为空'),
            ),
            'parent_list' => array(
                array('isNotNull', '家长列表不能为空'),
            ),
        );
    
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    /**
     * @desc 验证学生ID
     * @author wei
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validPutStudentRegistered($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
    
        $config = array(
            'student_id' => array(
                array('isNotNull', '学生ID不能为空。'),
            ),
        );
    
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    
    /**
     * @desc 验证红黄绿检测结果
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validStudentDetection($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
    
        $config = array(
            'detection_id' => array(
                array('isNotNull', '检测数据ID不能为空'),
                array('isNotDetection', '检测数据ID不存在'),
            ),
        );
    
        if($form['result']){
            if($form['result'] == HANDHELD_STATUS_RED || $form['result'] == HANDHELD_STATUS_YELLOW || $form['result'] == HANDHELD_STATUS_GREEN){
            }else{
                $errors['result'] = '检测结果不存在';
                return false;
            }
        }else{
            $config['result'] = array('isNotNull', '检测结果不能为空');
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    
    /**
     * @desc 更新检测结果信息状态
     * @author ly
     * @param $detection_id
     * @param $stateType
     * @return NULL|unknown
     */
    function updateDetectionStateType($detection_id, $stateType){
        $this->escape($detection_id);
        $this->escape($stateType);
        
        $sql = "UPDATE tb_student_detection SET state_type = '{$stateType}' WHERE deleted = 0 AND id = '{$detection_id}'";
        
        return $this->getOne($sql);
    }
    /**
     * @desc 更新学生信息是否终端注册
     * @author wei
     * @param $id
     * @return NULL|unknown
     */
    function updateStudentRegistered($id){
        $this->escape($id);
        
        $sql = "UPDATE tb_student SET device_registered = 1 WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    /**
     * @desc 验证红黄绿检测图像
     * @author ly
     * @param $form
     * @param $errors
     * @return boolean
     */
    function validDetection($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
    
        $config = array(
            'detection_id' => array(
                array('isNotNull', '检测数据ID不能为空'),
                array('isNotDetection', '检测数据ID不存在'),
            ),
        );
    
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    
    function validStudentAddParent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
    
        $config = array(
            'student_id' => array(
                array('isNotNull', '学生ID不能为空。'),
            ),
            'phone' => array(
                array('isNotNull', '电话号码不能为空。'),
                array('isPhoneNo', '电话格式不正确。'),
            ),
            'relation' => array(
                array('isNotNull', '关系ID不能为空。'),
            ),
        );
        if($form['relation'] == PARENT_TYPE_OTHER){
            $config = array(
                'relation_title' => array(
                    array('isNotNull', '关系名称不能为空。'),
                ),
                'student_id' => array(
                    array('isNotNull', '学生ID不能为空。'),
                ),
                'phone' => array(
                    array('isNotNull', '电话号码不能为空。'),
                    array('isPhoneNo', '电话格式不正确。'),
                ),
            );
            
        }
    
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
    
        return true;
    }
    
    /**
     * @desc 验证关系是否存在
     * @author wei
     */ 
    function getIsStudentParentRelation($student_id,  $relation_id){
        $this->escape($student_id);
        $this->escape($relation_id);
        
        $sql = "SELECT * FROM tb_student_parent 
            WHERE student_id = '{$student_id}'  AND relation_id = '{$relation_id} ' AND deleted = 0";
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    /**
     * @desc 验证电话是否存在
     * @author wei
     */ 
    function getIsStudentParentPhone($school_id, $phone){
        $this->escape($school_id);
        $this->escape($phone); 
        
        $sql = "SELECT * FROM tb_school_parent 
            WHERE school_id = '{$school_id}'  AND phone = '{$phone} ' AND deleted = 0";
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    /**
     * @desc 验证学生家长是否有关系
     * @author wei
     */ 
    function getIsStudentParent($parent_id,$student_id){
        $this->escape($student_id);
        $this->escape($parent_id); 
        
        $sql = "SELECT * FROM tb_student_parent 
            WHERE student_id = '{$student_id}'  AND parent_id = '{$parent_id} ' AND deleted = 0";
        
        $rs = $this->getOne($sql);
        return $rs;
        
    }



   /*判断孩子是否有晨检记录*/
   // function isTodayDetection($student_id) {
   //      $this->escape($student_id);
        
   //      $sql = "
   //          SELECT * FROM tb_student_detection WHERE student_id = '{$student_id}'
   //          AND date(created) = curdate()
   //      ";
        
   //      $rs = $this->getOne($sql);
   //      return $rs ? $rs : false;
   //  }
    
}

?>
