<?php

class StudentModel extends AppModel {

    function getStudentList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT tb_student.*, tb_school.title AS school_title, tb_class.title AS class_title
            FROM tb_student
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.deleted = 0 ";
        
        $this->where($sql, 'tb_student.name', 'name', 'lk');
        $this->where($sql, 'tb_student.school_id', 'school_id', '=');
        $this->where($sql, 'tb_student.class_id', 'class_id', '=');
        $this->where($sql, 'tb_student.id', 'sids', 'in');
        
        if ($all) {
            $this->order($sql, 'order.student');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.student');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getStudent($id) {
        $this->escape($id);
        $sql = "
            SELECT tb_student.*, tb_class.start,tb_student_detection.org_img_url
            FROM tb_student
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            LEFT JOIN tb_student_detection ON tb_student_detection.student_id = tb_student.id
            WHERE tb_student.id = '{$id}' AND tb_student.deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveStudent($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'name' => array(
                array('isNotNull', '请输入姓名'),
            ),
            'gender' => array(
                array('isNotNull', '请选择性别'),
            ),
            'birthday' => array(
                array('isNotNull', '请输入生日'),
                array('isDate', '请输入正确的生日'),
            ),
            'school_id' => array(
                array('isNotNull', '请选择学校'),
            ),
            'class_id' => array(
                array('isNotNull', '请选择班级'),
            )
        );
        
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

    function saveStudent($form) {
        $table = 'tb_student';
        $data = array(
            'name' => $form['name'],
            'birthday' => $form['birthday'],
            'gender' => $form['gender'],
            'school_id' => $form['school_id'],
            'class_id' => $form['class_id'],
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
    
    function deleteStudent($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_student', $data, $where);
    }

    function getStudentListByParentPhone($parentPhone) {
        $this->escape($parentPhone);
        $sql = "
            SELECT tb_student.*, tb_school.title AS school_title, tb_class.title AS class_title, tb_student_parent.relation_id, tb_school_parent.type AS parent_type, tb_student_parent.qrcode_url
            FROM tb_student
            LEFT JOIN tb_student_parent ON tb_student_parent.student_id = tb_student.id AND tb_student_parent.parent_id IN
              (
              SELECT id FROM tb_school_parent WHERE phone = '{$parentPhone}'
              )
            LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id AND tb_school_parent.deleted = 0
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.deleted = 0 AND tb_student_parent.deleted = 0 ORDER BY tb_student.created DESC";
        
        $rs = $this->getAll($sql);
        return $rs;
    }

    function getStudentByParentId($parentId, $studentId) {
        $this->escape($parentId);
        $this->escape($studentId);

        $sql = "
            SELECT tb_student.*, tb_school.title AS school_title, tb_class.title AS class_title, tb_class.start AS class_start, tb_student_parent.relation_id, tb_school_parent.type AS parent_type
            FROM tb_student
            LEFT JOIN tb_student_parent ON tb_student_parent.student_id = tb_student.id AND tb_student_parent.parent_id = '{$parentId}' AND tb_student.id = '{$studentId}'
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_school_parent ON tb_school_parent.id = tb_student_parent.parent_id AND tb_school_parent.deleted = 0
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.deleted = 0  AND tb_student.id = '{$studentId}'";

        $rs = $this->getOne($sql);
        return $rs;
    }

    function getStudentLatestDetectionImg($studentId) {
        $sql = "SELECT * FROM tb_file WHERE deleted = 0 AND usage_type = " . FILE_USAGE_TYPE_STUDENT_DETECTION . " AND usage_id = '{$studentId}' ORDER BY created DESC LIMIT 1";
        $rs = $this->getOne($sql);
        return $rs;
    }

    function getStudentDetectionImg($studentId, $time) {
        $this->escape($studentId);
        $time = date('Y-m-d',strtotime($time));
        $sql = "SELECT * FROM tb_file WHERE deleted = 0 AND usage_type = " . FILE_USAGE_TYPE_STUDENT_DETECTION . " AND usage_id = '{$studentId}' AND DATE_FORMAT(created,'%Y-%m-%d')='{$time}' ORDER BY created DESC LIMIT 1";
        $rs = $this->getOne($sql);
        return $rs;
    }

    /*  
     * @desc 小孩已接页面数据查询
     * @author wzl
     */
    function getStudentAway ($studentAwayId, $studentId, $data) {
        $this->escape($studentAwayId);
        $this->escape($studentId);
        $sql = "
            SELECT * FROM tb_student_away_record WHERE ";
            
        if($studentAwayId){
            $sql .= ' id = ' . $studentAwayId;
        }else{
            $time = date('Y-m-d',strtotime($data));
            $sql .= " student_id = '{$studentId}' AND  DATE_FORMAT(created,'%Y-%m-%d') = '{$time}' ";
        }
        
        $rs = $this->getOne($sql);
        return $rs;
    }
    
    /*  
     * @desc 获取相同姓名的学生列表
     * @author wzl
     */
    function getStudentListByName ($name) {
        $this->escape($name);
        $sql = "
            SELECT * FROM tb_student WHERE name = '{$name}' AND deleted = 0 AND status = 1
        ";
        return $this->getAll($sql);
    }
    
    /**
     * @desc 查询学生下的学校
     * @author wei
     */
    function getStudentSchool($studentId){
        $this->escape($studentId);
        
        $sql = "SELECT tb_student.*,tb_school.title as  school_title FROM tb_student LEFT JOIN tb_school ON tb_student.school_id = tb_school.id AND tb_school.deleted = 0 AND tb_school.status = 1 WHERE tb_student.deleted = 0 AND tb_student.status = 1  AND tb_student.id = '{$studentId}' ";
        
        $rs = $this->getOne($sql);
        return $rs; 
    }
    /**
     * @desc 查询学生下的当日数据
     * @author wei
     */
    function getStudentDetectionDay($studentId, $dateTmie){
        $this->escape($studentId);
        
        $sql = "SELECT * FROM tb_student_detection WHERE deleted = 0 AND status = 1  AND student_id = '{$studentId}' AND DATE_FORMAT(created, '%Y-%m-%d') = '{$dateTmie}' ";
        
        
        $rs = $this->getOne($sql);
        return $rs; 
    }
    
    /**
     * @desc 查询学生下的id数据
     * @author wei
     */
    function getStudentDetectionId($studentId, $detection_id){
      $this->escape($studentId);
        
        $sql = "SELECT * FROM tb_student_detection WHERE deleted = 0 AND status = 1  AND student_id = '{$studentId}' AND id = '{$detection_id}' ";
        
        $rs = $this->getOne($sql);
        return $rs; 
    }
    
    /**
     * @desc 查询学生下的家长
     * @author wei
     */
    function getStudentParentList($studentId) {
        $this->escape($studentId);

        $sql = "
            SELECT tb_parent.*, tb_weichat.openid
            FROM tb_parent
            LEFT JOIN tb_weichat ON tb_weichat.id = tb_parent.weichat_id
            WHERE tb_parent.deleted = 0 AND tb_parent.phone IN (
                SELECT phone FROM tb_school_parent WHERE id IN 
                (
                  SELECT parent_id FROM tb_student_parent WHERE deleted = 0 AND student_id = '{$studentId}'
                )
            )";

        $rs = $this->getAll($sql);
        
        return $rs;
    }
    /**
     * @desc 修改学生下的当日数据
     * @author wei
     */
    function updateStudentDetectionDay($id) {
        $this->escape($id);
        
        $sql = "UPDATE tb_student_detection SET weichat_num = weichat_num +1 WHERE id = '{$id}' ";
        
        $this->Execute($sql);
    }
    /**
     * @desc 修改学生下的当日数据
     * @author wei
     */
    function getUserMessageIS($user_id){
        $this->escape($user_id);
        
        $sql = "SELECT * FROM tb_user_message WHERE t_uid = '{$user_id}' AND is_read = " . APP_UNIFIED_FALSE;
        
        return $this->getAll($sql);
    }
    /**
     * @desc 获取当前学校的班级学生用于下拉框
     * @author ly
     * @param $school_id
     * @param $class_id
     * @return array
     */
    function getStudents($school_id, $class_id){
        $this->escape($school_id);
        $this->escape($class_id);
        
        $sql = "SELECT id AS value,name FROM tb_student
        		WHERE deleted = 0 AND school_id = '{$school_id}'
        		AND class_id = '{$class_id}'";
        
        return $this->getAll($sql);
    }
    

    /*当前学生所在班级的同班同学（不包括本人）*/

    function getClassmatesList($classId,$studentId) {
        $this->escape($classId);
        $this->escape($studentId);
        $sql = "
            SELECT tb_s.id, tb_s.name, tb_sd.org_img_url, tb_s.created
            FROM tb_student AS tb_s
            LEFT JOIN tb_student_detection AS tb_sd ON tb_s.id = tb_sd.student_id
            WHERE tb_s.deleted =0
            AND tb_s.status =1
            AND tb_s.class_id = '{$classId}'
            AND tb_s.id != '{$studentId}'
            GROUP BY tb_s.id
            ORDER BY CONVERT( tb_s.name USING gbk ) 
            COLLATE gbk_chinese_ci ASC";
        $rs = $this->getAll($sql);
        return $rs;
    }


    function getReturnStudentId($returnStudentId)
    {
        $this->escape($returnStudentId);
        $sql="SELECT tb_s.id, tb_s.name, tb_sd.org_img_url, tb_s.created
            FROM tb_student AS tb_s
            LEFT JOIN tb_student_detection AS tb_sd ON tb_s.id = tb_sd.student_id
            WHERE tb_s.deleted =0
            AND tb_s.status =1
            AND tb_s.id = '{$returnStudentId}'";
        return $this->getOne($sql);
    }
}

?>
