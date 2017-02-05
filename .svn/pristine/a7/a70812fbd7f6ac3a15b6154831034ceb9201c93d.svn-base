<?php

class SchoolModel extends AppModel {

    function getSchoolList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_school
            WHERE deleted = 0";
        
        $this->where($sql, 'title', 'title', 'lk');
        $this->where($sql, 'address', 'address', 'lk');
        $this->where($sql, 'phone', 'phone', 'lk');
        $this->where($sql, 'key_rel_status', 'key_rel_status', 'in');
        
        if ($sh['key_new_date']) {
            $this->where($sql, "DATE_FORMAT(key_new_time, '%Y-%m-%d')", 'key_new_date', '<');
        }
        
        if ($all) {
            $this->order($sql, 'order.default');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.default');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getSchoolOptionList() {
        $this->setSearch($sh);
        $sql = "
            SELECT id AS value, title AS name FROM tb_school
            WHERE deleted = 0 AND status = 1";

        $this->order($sql, 'order.default');
        $rs = $this->getAll($sql);

        $schoolList = array();
        foreach($rs as $v) {
            $schoolList[$v['value']] = $v;
        }

        return $schoolList;
    }

    function getSchool($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_school
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveSchool($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入名称'),
            ),
            'phone' => array(
                array('isNotNull', '请输入电话'),
            ),
            'address' => array(
                array('isNotNull', '请输入地址'),
            ),
            'admin_login' => array(
                array('isNotNull', '请输入超级管理员名称'),
            )
        );

        if(!$form['id']) {
            $config['admin_password'] = array(
                array('isNotNull', '请输入超级管理员密码')
            );
        }

        if($form['admin_password']) {
            $config['admin_password_confirm'] = array(
              array('isSame', '超级管理员密码不一致，请重新输入', array('compare' => 'admin_password')),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function saveSchool($form) {
        $table = 'tb_school';
        $data = array(
            'title' => $form['title'],
            'phone' => $form['phone'],
            'address' => $form['address'],
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
            $data['key'] = md5($school_id . time());
            $data['key_new'] = $data['key'];
            $data['key_new_time'] = NOW;
            $data['key_active_time'] = NOW;
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    function deleteSchool($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_school', $data, $where);
    }
    
    /**
     * @desc 更新学校KEY
     * @desc KEY值为md5(学校ID+时间戳)
     * @author lxs
     */
    function saveSchoolKey($school_id) {
        $this->escape($school_id);
        
        $data = array(
            'key_new' => md5($school_id . time()),
            'key_new_time' => NOW,
        );
        $where = "id = '{$school_id}'";
        
        return $this->Update('tb_school', $data, $where);
    }
    
    /**
     * @desc 修改数据通用方法
     * @author lxs
     */
    function saveSchoolData($id, $data) {
        $this->escape($id);
        $where = "id = '{$id}'";
        return $this->Update('tb_school', $data, $where);
    }


    function getDataStatistics($sh){
        $this->escape($sh);
        

        /*已有学校*/
        $schoolSql = "SELECT id,title FROM tb_school WHERE deleted = 0";
        $schoolArr = $this->getAll($schoolSql);
        $schoolList = array();
        foreach ($schoolArr as $key => $value) {
            /*未识别人数*/
            $unidentificationSql = "
                SELECT c.*,f.file_path AS path,c.id AS claim_id,d.file_img_id FROM tb_detection_claim c
                LEFT JOIN tb_student_detection d ON c.detection_id = d.id
                LEFT JOIN tb_file f ON d.file_img_id = f.id AND f.usage_type = 102
                WHERE school_id = '{$value['id']}' AND c.type = 0 AND c.status = 1
                AND f.deleted = 0 AND d.deleted = 0 AND
                d.created LIKE  '%{$sh['date_time']}%'";
            $unidentificationNum = count($this->getAll($unidentificationSql));
            /*学生人数*/
            $studentSql = "SELECT * FROM tb_student WHERE school_id = '{$value['id']}' AND deleted = 0";
            $studentNum = count($this->getAll($studentSql));
            /*绑定家长数*/
            $bindParentSql = "SELECT * FROM tb_student_parent WHERE school_id = '{$value['id']}' AND deleted = 0";
            $bindParentNum = count($this->getAll($bindParentSql));
            /*成功晨检人数*/
            $successSql = "SELECT * FROM tb_student_detection AS tb_sd LEFT JOIN tb_student AS tb_s ON tb_sd.student_id = tb_s.id 
                            WHERE tb_s.school_id = '{$value['id']}' AND tb_sd.student_id != 0 AND tb_sd.deleted = 0
                            AND tb_sd.created LIKE '%{$sh['date_time']}%'";
            $successNum = count($this->getAll($successSql));  

            /*下午家长接送人数*/
            $packUpSql = "SELECT * FROM tb_student_away_record AS tb_away
            LEFT JOIN tb_student ON tb_away.student_id = tb_student.id
            WHERE tb_student.school_id = '{$value['id']}' AND tb_student.deleted = 0 AND tb_away.created LIKE '%{$sh['date_time']}%'";
            $packUpNum = count($this->getAll($packUpSql));

            $value['unidentificationNum'] = $unidentificationNum;
            $value['studentNum'] = $studentNum;
            $value['bindParentNum'] = $bindParentNum;
            $value['packUpNum'] = $packUpNum;
            $value['successNum'] = $successNum;
            $value['percent'] = round(($successNum/$studentNum)*100,2).'%';
            array_push($schoolList, $value);
        }

        return $schoolList;
    }
    
}

?>
