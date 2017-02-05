<?php

class StudentModel extends AppModel {

    function getStudentList($schoolId, $sh, $all = false) {
        $this->escape($schoolId);
        $this->setSearch($sh);
        $sql = "
            SELECT tb_student.*, tb_class.title AS class_title
            FROM tb_student
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.school_id = '{$schoolId}' AND tb_student.deleted = 0 ";         

        $this->where($sql, 'tb_student.name', 'name', 'lk');
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

    function getStudent($schoolId, $id) {
        $this->escape($schoolId);
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_student
            WHERE tb_student.school_id = '{$schoolId}' AND id = '{$id}' AND deleted = 0
            LIMIT 1
        ";

        return $this->getOne($sql);
    }

    function validSaveStudent($form, &$errors) {
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

    function getStudentOptionList($schoolId, $classId) {
        $this->escape($schoolId);
        $this->escape($classId);
        $sql = "
            SELECT id AS value, name FROM tb_student
            WHERE deleted = 0 AND status = 1 AND school_id = '{$schoolId}' AND class_id = '{$classId}'";

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

    function deleteStudent($schoolId, $id) {
        $this->escape($schoolId);
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "school_id = '{$schoolId}' AND id = '{$id}'";
        return $this->Update('tb_student', $data, $where);
    }

    function deleteStudentParent($schoolId, $id) {
        $this->escape($schoolId);
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "school_id = '{$schoolId}' AND student_id = '{$id}'";
        return $this->Update('tb_student_parent', $data, $where);
    }

    function getStudentListByParentId($schoolId, $parentId) {
        $this->escape($schoolId);
        $this->escape($parentId);
        $sql = "
            SELECT tb_student.*, tb_school.title AS school_title, tb_class.title AS class_title, tb_student_parent.relation_title
            FROM tb_student
            LEFT JOIN tb_student_parent ON tb_student_parent.student_id = tb_student.id AND tb_student_parent.parent_id = '{$parentId}'
            LEFT JOIN tb_school ON tb_school.id = tb_student.school_id
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student_parent.school_id = '{$schoolId}' AND tb_student.deleted = 0 AND tb_student_parent.deleted = 0 ORDER BY tb_student.created DESC";

        $rs = $this->getAll($sql);
        return $rs;
    }

    function getClassStudentByName ($studentName, $classId,$studentBirthday) {
        $this->escape($studentName);
        $this->escape($studentBirthday);
        $this->escape($classId);
        $sql = "
            SELECT * FROM tb_student
            WHERE tb_student.class_id = '{$classId}'  AND tb_student.birthday = '{$studentBirthday}' AND tb_student.name = '{$studentName}' AND tb_student.deleted = 0
        ";
        $rs = $this->getOne($sql);
        return $rs;
    }

    /**
     *查找未完成认领的学生头像
     */
    function getClaimImgList() {
        $sid = $_SESSION[SESSION_USER]["school_id"];
        $sql = "
            SELECT c.*,f.file_path AS path,c.id AS claim_id,d.file_img_id FROM tb_detection_claim c
            LEFT JOIN tb_student_detection d ON c.detection_id = d.id
            LEFT JOIN tb_file f ON d.file_img_id = f.id AND f.usage_type = 102
            WHERE school_id = '{$sid}' AND c.type = 0 AND c.status = 1
            AND f.deleted = 0 AND d.deleted = 0
            AND DATE_FORMAT(c.created,'%Y-%m-%d')>=DATE_SUB(CURDATE(),INTERVAL 1 day)
        ";
        $rs = $this->getAll($sql);
        return $rs;
    }

    /**
     *查找学生认领的班级
     */
    function getClaimClassList() {
        $sql = "
             SELECT c.id AS value,c.title AS name FROM tb_class c
             WHERE deleted = 0 AND school_id = '{$_SESSION[SESSION_USER]['school_id']}'
        ";
        $rs = $this->getAll($sql);
        return $rs;
    }

     /**
      *根据学生班级查找学生信息
      */
     function getStudentListByClassId($class_id) {
         $this->escape($class_id);
         $sql ="
            SELECT * FROM tb_student s
            WHERE s.class_id = '{$class_id}'
            AND s.school_id = '{$_SESSION[SESSION_USER]['school_id']}'
            AND deleted = 0
          ";
          $rs = $this->getAll($sql);
          return $rs;
      }


     /**
      * @desc 修改单条学生认领信息
      * @author wjd
      */
     function updateClaimStudent($form) {
         $table = 'tb_detection_claim';
         $data = array(
             'status' => 0,
         );

             $whereId = $form['detection_id'];
             $this->escape($whereId);
             $whereSql = "detection_id = '{$whereId}'";
             $this->Update($table, $data, $whereSql);
             return $form['detection_id'];

     }

     /**
      * @desc 新增一条学生认领信息
      * @author wjd
      */
     function saveClaimStudent($form) {
         $table = 'tb_detection_claim';
         $data = array(
             'school_id' => $_SESSION[SESSION_USER]["school_id"],
             'detection_id' => $form['detection_id'],
             'terminal_img_id' => $form['terminal_img_id'],
             'student_id' => $form['student_id'],
             'type' => 1,
             'status' => 1,
             'op_uid' => $_SESSION[SESSION_USER]['id'],
             'op_utype' => $_SESSION[SESSION_ROLE],
         );

             $data['created'] = NOW;
             return $this->Insert($table, $data);

     }

     /**
      * @desc 修改学生检测数据表(学生ID、状态)
      * @author wjd
      */
     function updateStudentDection($form) {
         $table = 'tb_student_detection';
         $data = array(
             'student_id' => $form['student_id'],
             'status' => 1,
         );

             $whereId = $form['detection_id'];
             $this->escape($whereId);
             $whereSql = "id = '{$whereId}'";
             $this->Update($table, $data, $whereSql);
             return $form['student_id'];
     }

     /**
      * @desc 修改学生检测数据表(是否第一条、最后一条数据)
      * @author wjd
      */
     function updateFirstLastestStudentDection($form) {
         //获取当天第一条数据
         $sql_first = "
            SELECT d.id,d.created FROM tb_student_detection d
            WHERE d.student_id = '{$form['student_id']}'
            AND date_format(d.created,'%Y-%m-%d') = date_format((SELECT s.created FROM tb_student_detection s
            WHERE s.id = '{$form['detection_id']}'
            AND s.deleted = 0),'%Y-%m-%d')
            AND d.first = 1
            AND d.deleted = 0
         ";
         $rs_first = $this->getOne($sql_first);

         //获取当天最后一条数据
         $sql_lastest = "
            SELECT d.id,d.created FROM tb_student_detection d
            WHERE d.student_id = '{$form['student_id']}'
            AND date_format(d.created,'%Y-%m-%d') = date_format((SELECT s.created FROM tb_student_detection s
            WHERE s.id = '{$form['detection_id']}'
            AND s.deleted = 0),'%Y-%m-%d')
            AND d.lastest = 1
            AND d.deleted = 0
         ";
         $rs_lastest = $this->getOne($sql_lastest);

         //获取当前一条数据
         $sql_new = "
             SELECT d.id,d.created FROM tb_student_detection d
             WHERE d.id = '{$form['detection_id']}'
             AND d.deleted = 0
         ";
         $rs_new = $this->getOne($sql_new);

         $table = 'tb_student_detection';
         $data = array(
             'first' => $rs_first && ($rs_first['created'] <= $rs_new['created']) ? 0 : 1,
             'lastest' => $rs_lastest && ($rs_lastest['created'] >= $rs_new['created']) ? 0 : 1,
         );

         //修改当前数据
         $whereId = $form['detection_id'];
         $this->escape($whereId);
         $whereSql = "id = '{$whereId}'";
         $this->Update($table, $data, $whereSql);

         //修改最新一条数据
         if ($rs_first && ($rs_new['created']<$rs_first['created'])) {
            $data = array(
                'first' => 0,
            );
            $whereId = $rs_first['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
        }

        //修改最后一条数据
        if ($rs_lastest && ($rs_new['created']>$rs_lastest['created'])) {
             $data = array(
                 'lastest' => 0,
             );
             $whereId = $rs_lastest['id'];
             $this->escape($whereId);
             $whereSql = "id = '{$whereId}'";
             $this->Update($table, $data, $whereSql);
        }

     }

     /**
      * @desc 修改头像表(学生关联ID)
      * @author wjd
      */
     function updateFile($form) {
         $table = 'tb_file';
         $data = array(
             'usage_id' => $form['student_id'],
         );

             $whereId = $form['file_img_id'];
             $this->escape($whereId);
             $whereSql = "id = '{$whereId}'";
             $this->Update($table, $data, $whereSql);
             return $form['usage_id'];

     }

    /**
     * @desc 验证认领学生
     * @author wjd
     */
    function validClaimStudent($form, &$errors) {

        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'class_id' => array(
                array('isNotNull', '请输入学生姓名'),
            )
        );

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    /**
    *@author: wzl
    *@desc: exportStudent
    **/
    function exportStudent($sh, $height=null, $weight=null, $temperature=null, $start_time=null, $end_time=null,$all = false) {

        $this->setSearch($sh);

        $student_name = $sh['name'];
        $class_id = $sh['class_id'];

        $sql = "
            SELECT
                tb_student.name, tb_student.gender,
                tb_class.title
        ";
        if ($height) {
            $sql .= "
                , (SELECT tb_student_detection.height FROM
                tb_student_detection WHERE tb_student_detection.deleted=0 AND tb_student_detection.status=1 AND tb_student_detection.student_id = (SELECT tb_student.id)
                ORDER BY tb_student_detection.created LIMIT 1) AS height
            ";
            // $sql .= "
            //     , (SELECT tb_student_detection.height FROM
            //     tb_student_detection WHERE tb_student_detection.deleted=0 AND tb_student_detection.status=1 AND tb_student_detection.student_id = (SELECT tb_student.id)
            //     ORDER BY tb_student_detection.created LIMIT 1) AS height
            // ";
        }
        if ($weight) {
            $sql .= "
                , (SELECT tb_student_detection.weight FROM
                tb_student_detection WHERE tb_student_detection.deleted=0 AND tb_student_detection.status=1 AND tb_student_detection.student_id = (SELECT tb_student.id)
                ORDER BY tb_student_detection.created LIMIT 1) AS weight
            ";

        }
        if ($temperature) {
            $sql .= "
                , (SELECT tb_student_detection.temperature FROM
                tb_student_detection WHERE tb_student_detection.deleted=0 AND tb_student_detection.status=1 AND tb_student_detection.student_id = (SELECT tb_student.id)
                ORDER BY tb_student_detection.created LIMIT 1) AS temperature
            ";
        }
        $sql .= "
            FROM
                tb_student
            LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
            WHERE tb_student.deleted = 0
        ";

        $this->where($sql, 'tb_student.name', 'name', 'lk');
        $this->where($sql, 'tb_student.class_id', 'class_id', '=');
        $this->where($sql, 'tb_student.school_id', 'school_id', '=');

        $rs = $this->getAll($sql);
        return $rs;
    }

    /**
    *@author: wzl
    *@desc: 根据班级id找班级名
    **/
    function getClassTitle($class_id) {
        $this->escape($class_id);
        $sql = "SELECT title FROM tb_class WHERE id = '{$class_id}'";
        return $this->getOne($sql);
    }


    function getStuentByParentPhone($schoolId,$phone,$all = false){
        $this->escape($schoolId);
        $this->escape($phone);
        $sql = "SELECT  tb_student.*,tb_class.title AS class_title FROM tb_student_parent AS tb_stp
                LEFT JOIN tb_school_parent AS tb_scp ON tb_stp.parent_id = tb_scp.id
                LEFT JOIN tb_student ON tb_stp.student_id = tb_student.id
                LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
                WHERE tb_scp.phone =  '{$phone}'
                AND tb_stp.school_id = '{$schoolId}' AND tb_scp.deleted =0 AND tb_stp.deleted =0";
        if ($all) {
            $this->order($sql, 'order.student');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.studentSearch');
            $rs = $this->paginate($sql);
        }
        return $rs;
    }
}
?>
