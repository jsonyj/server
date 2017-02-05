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
            SELECT * FROM tb_student
            WHERE id = '{$id}' AND deleted = 0
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
            'school_id' => array(
                array('isNotNull', '请选择学校'),
            ),
            'class_id' => array(
                array('isNotNull', '请选择班级'),
            )
        );
        
        if ($form['birthday']) {
            $config['birthday'] = array(
                array('isDate', '请输入正确的生日'),
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

    function saveStudent($form) {
        $table = 'tb_student';
        $data = array(
            'name' => $form['name'],
            'birthday' => $form['birthday'] ? $form['birthday'] : '0000-00-00',
            'gender' => $form['gender'],
            'dream_id' => $gender['gender'] == 1 ? 8 : 3,
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

    function deleteStudentParent($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "student_id = '{$id}'";
        return $this->Update('tb_student_parent', $data, $where);
    }

    function getStudentListByParentId($schoolId, $parentId) {
        $this->escape($parentId);
        $this->escape($schoolId);

        $sql = "
            SELECT tb_student.*, tb_school.title AS school_title, tb_class.title AS class_title, tb_student_parent.relation_title, tb_school_parent.type AS parent_type
            FROM tb_student
            LEFT JOIN tb_student_parent ON tb_student_parent.student_id = tb_student.id AND tb_student_parent.parent_id = '{$parentId}'
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIn tb_school_parent ON tb_school_parent.school_id = tb_school.id AND tb_school_parent.id = '{$parentId}'
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.deleted = 0 AND tb_student_parent.deleted = 0 AND tb_student.school_id = '{$schoolId}' ORDER BY tb_student.created DESC";

        $rs = $this->getAll($sql);
        return $rs;
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
    	// 优化为 0.05秒
    			$sql = "select * from (SELECT sc.phone FROM tb_school_parent sc left join tb_student_parent st  on sc.id=st.parent_id where sc.deleted=0 and st.deleted=0 and st.student_id = '{$studentId}') as tmp1 , (SELECT tb_parent.*, tb_weichat.openid
    			FROM tb_parent
    			LEFT JOIN tb_weichat ON tb_weichat.id = tb_parent.weichat_id
    			WHERE tb_parent.deleted = 0 ) as tmp2 where tmp1.phone=tmp2.phone";
    
    			$rs = $this->getAll($sql);
    
    			return $rs;
    }

    function getStudentSchool($studentId) {
        $this->escape($studentId);

        $sql = "
            SELECT tb_school.*
            FROM tb_school
            WHERE tb_school.id = (
              SELECT school_id FROM tb_student WHERE id = '{$studentId}'
            )";

        $rs = $this->getOne($sql);
        
        return $rs;
    }
    
    
     /**
     * @desc 批量处理删除已经认领过的数据 （每天）  
     * @author wei
     */
    function getIsDetectionClaim(){
        
        $sql = "SELECT * FROM tb_detection_claim
            WHERE deleted = 0 AND whether_claim = 1";
        
        $rs = $this->getAll($sql);
        
        return $rs;
    }
    
    function deleteIsDetectionClaim($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_detection_claim', $data, $where);
    }
}

?>
