<?php
/**
 * @description 服务model
 */
class ServiceModel extends AppModel {

    function getService($no) {
        $this->escape($no);
       
        return $this->getOne($sql);
    }
    
    function validIsSearch($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'id' => array(
                array('isNotNull', '请输入学生ID。'),
            ),
            'type' => array(
                array('isNotNull', '请输入获取数据类型。'),
            ),
            'date_start' => array(
                array('isNotNull', '请输入起始时间。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    /**
     * @desc  查询日报
     * @author   wei
     */
    function getServiceDailyList($id, $date_start, $date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
        $start_time = date('Y-m-d', strtotime($date_start));
        
        $sql = "SELECT *, DATE_FORMAT(created,'%Y-%m-%d') as ym FROM tb_student_detection WHERE student_id = '{$id}'   AND deleted = 0 ";
        if ($date_start && $date_end){
            $start_time = date('Y-m-d', strtotime($date_start));
            $end_time = date('Y-m-d', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m-%d') <= '{$end_time}' ORDER BY created DESC";
            
        }else{
            $start_time = date('Y-m-d', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d')= '{$start_time}' GROUP BY DATE_FORMAT(created,'%Y-%m-%d')  LIMIT 1";
            
        }
        
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        return $this->getAll($sqls);
    }
    /**
     * @desc  查询月报
     * @author   wei
     */ 
    function getServiceMonthList($id, $date_start ,$date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
         
        $sql = "SELECT * FROM  tb_report_month WHERE student_id = '{$id}'   AND deleted = 0";
        if ($date_start && $date_end){
            $start_time = date('Y-m', strtotime($date_start));
            $end_time = date('Y-m', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(start,'%Y-%m') >= '{$start_time}' AND DATE_FORMAT(end,'%Y-%m') <= '{$end_time}' ORDER BY created DESC";
            
        }else{
            $start_time = date('Y-m', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(start,'%Y-%m')= '{$start_time}' ORDER BY created DESC ";
            
        }
        
        return $this->getAll($sql);
    }
    /**
     * @desc  接送报告
     * @author   wei
     */ 
    function getServiceShuttleList($id, $date_start ,$date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end); 
        
        $sql = "SELECT *,  DATE_FORMAT(created,'%Y-%m-%d') as ym FROM  tb_student_away_record WHERE student_id = '{$id}'  ";
        
        if ($date_start && $date_end){
            $start_time = date('Y-m-d', strtotime($date_start));
            $end_time = date('Y-m-d', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m-%d') <= '{$end_time}' ORDER BY created DESC";
            
        }else{
            $start_time = date('Y-m-d', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        return $this->getAll($sqls);
    }
    /**
     * @desc  身高体重月报
     * @author   wei
     */ 
    function getServiceStatureList($id, $date_start ,$date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
        
        $sql = "SELECT *, DATE_FORMAT(created,'%Y-%m-%d') as ym FROM tb_student_detection WHERE student_id = '{$id}'   AND deleted = 0 ";
        
        if ($date_start && $date_end){
            $start_time = date('Y-m-d', strtotime($date_start));
            $end_time = date('Y-m-d', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m-%d') <= '{$end_time}' ORDER BY created ";
            
        }else{
            $start_time = date('Y-m-d', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        return $this->getAll($sqls);
    }
    /**
     * @desc  身高体重年报
     * @author   wei
     */ 
    function getServiceStatureYearList($id, $date_start ,$date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
        
        $sql = "SELECT *, DATE_FORMAT(created,'%Y-%m') as ym FROM tb_student_detection WHERE student_id = '{$id}'   AND deleted = 0 ";
        
        if ($date_start && $date_end){
            $start_time = date('Y-m', strtotime($date_start));
            $end_time = date('Y-m', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m') <= '{$end_time}'   ORDER BY id  DESC ";
            
        }else{
            $start_time = date('Y-m', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        return $this->getAll($sqls);
    }
    /**
     * @desc  身高体重总季报
     * @author   wei
     */ 
    function getServiceStatureQuarterList($id){
        $this->escape($id);
        
        $sql = "SELECT a.* FROM
                (
                    SELECT sd.*, DATE_FORMAT(sd.created,'%Y-%m') AS ym FROM tb_student_detection as sd
                    WHERE sd.student_id = {$id} AND sd.deleted = 0 
                    ORDER BY id DESC
                ) AS a 
                GROUP BY a.ym;
                ";
        
        return $this->getAll($sql);
    } 
        
    function getServiceImgList($id, $date_start ,$date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
        
        $sql = "SELECT *, DATE_FORMAT(created,'%Y-%m') as ym FROM tb_file WHERE usage_id = '{$id}' AND deleted = 0 AND usage_type =" . FILE_USAGE_TYPE_STUDENT_DETECTION;
        
        if ($date_start && $date_end){
            $start_time = date('Y-m-d', strtotime($date_start));
            $end_time = date('Y-m-d', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m-%d') <= '{$end_time}' GROUP BY DATE_FORMAT(created,'%Y-%m-%d')  ORDER BY created DESC";
            
        }else{
            $start_time = date('Y-m-d', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        return $this->getAll($sqls);
        
    }
    
    function validStudentMessage($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'app_id' => array(
                array('isNotNull', '请输入应用ID。'),
            ),
            'key' => array(
                array('isNotNull', '请上传key值。'),
            ),
            'student_id' => array(
                array('isNotNull', '请输入学生ID。'),
            ),
            'content' => array(
                array('isNotNull', '输入内容不能为空。'),
            ),
            'url' => array(
                array('isNotNull', '跳转url不能为空。'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function getApplication($app_id){
        $this->escape($app_id);
        
        $sql = "SELECT * FROM tb_application WHERE id = '{$app_id}' AND deleted = 0 AND status = 1 ";
        
        return $this->getOne($sql);
    }
    
    function sverStudentMessage($form){
        $table = 'tb_student_message';
        
        $data = array(
            't_uid' => $form['student_id'],
            't_utype' => ACT_STUDENT_ROLE,
            'f_uid' => $form['app_id'],
            'f_utype' => ACT_APPLICATION,
            'is_reply' => 0,
            'is_url' => $form['url'] ? 1 : 0,
            'url' => $form['url'],
            'status' => APP_UNIFIED_FALSE,
        );

        if ($form['id'] > 0) {
            $id = $form['id'];
            $whereSql = "id = '{$id}'";
            $this->Update($table, $data, $whereSql);
            return $id;
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    // 将设备当前信息写入数据库
    function savedevice($data){
        $table = 'tb_device';
        
        $record = array(
            'no' => $data['service_no'],       // 设备编号
            'version' => $data['version'],      // 设备当前版本
            'model' => $data['model'],      //  设备型号
            'version_type' => $data['version_type'],    // 当前版本类型
            );
        $sql = "
            SELECT * FROM `tb_device` 
            WHERE no = '{$record['no']}' AND deleted = 0 ORDER BY id DESC
            ";
        $deviceOne = $this -> getOne($sql);
        
        if (!empty($deviceOne['id'])) {
            if($record['version_type'] > 0){
                $form = array('branch_version' => $record['version']);
            }else{
                $form = array('main_version' => $record['version']);
            }
            // $form = array();
            // $record['version_type'] ? ($form['branch_version'] = $record['version']) : ($form['main_version'] = $record['version']);

            $form['version_type'] = $record['version_type'];
            $file_id = $deviceOne['id'];
            $this->escape($file_id);

            $where = "id = '{$file_id}'";
            $this->Update($table, $form, $where);
            return $file_id;
        }
        else {
            $form = array(
                'created' => $data['create_time'] ? date("Y-m-d H:i:s", strtotime($data['create_time'])) : NOW,
                'no' => $record['no'], 
                'model' => $record['model'],
                'main_version' => $record['version'],
                'version_type' => $record['version_type'],
                );
            return $this->Insert($table, $form);
        }
    }
    // 返回最新版本信息
    function getVersion($data){
        $table = 'tb_version';
        $return = array();
        $record = array(
            'device_type' => $data['system_type'],  // 目标系统
            );
        $sql = "
            SELECT * FROM `tb_version` 
            WHERE device_type = '{$record['device_type']}' AND deleted = 0 ORDER BY id DESC
            ";
        $versionOne = $this -> getOne($sql);
        if($versionOne){
            if($versionOne['branch_id'] > 0){
                $return['version'] = $versionOne['branch_version'];
                $return['version_type'] = 1;
            }else {  
                $return['version'] = $versionOne['main_version'];
                $return['version_type'] = 0;
            }
        }else{
            return FALSE;
        }

        $return['version_url'] = $versionOne['version_url'];
        $return['version_details'] = $versionOne['version_des'];
        return $return;
    }
    function getDeviceVersion($no) {
        $this->escape($no);
        $sql = "
            SELECT * FROM tb_device 
            WHERE no = '{$no}' AND deleted = 0
        ";
        
        return $this->getOne($sql);
    }
    
    function validPutVersion($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'service_no' => array(
                array('isNotNull', '请输入设备编号'),
            ),
            'model' => array(
                array('isNotNull', '请输入设备型号'),
            ),
            'system_type' => array(
                array('isNotNull', '请输入目标系统'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    /**结束分割**/
}

?>
