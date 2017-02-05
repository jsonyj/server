<?php

class StudentDetectionModel extends AppModel {

    function getStudentDetectionCount($studentId, $start, $end) {
        $this->escape($studentId);
        $this->escape($start);
        $this->escape($end);

        $sql = "
            SELECT COUNT(tb_student_detection.id) AS count
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0 AND tb_student_detection.student_id = '{$studentId}' AND tb_student_detection.created >= '{$start}' AND created <= '{$end}' ";

        $rs = $this->getOne($sql);
        return isset($rs['count']) ? $rs['count'] : 0;
    }
    
    function studentDetectionList(){
        
       $sql = "SELECT * FROM tb_student_detection WHERE deleted = 0 AND org_img_url = ' ' order by  created DESC";
       
       $rs = $this->getAll($sql); 
       return $rs;
    }
    
    function updataStudentDetection($from) {
        $this->escape($from);
        $id = $from['id'];
        $data = array('org_img_url' => $from['org_img_url'], 'file_img_id'=> $from['file_img_id'],);
        $where = "id = '{$id}'";
        return $this->Update('tb_student_detection', $data, $where);
    }

    function getFile($usage_id, $usage_type, $date){
        $this->escape($usage_id);
        $this->escape($usage_type);
        $sql = "SELECT tb_file.* FROM tb_file WHERE tb_file.deleted = 0 AND tb_file.usage_type = '{$usage_type}' AND tb_file.usage_id= '{$usage_id}' AND DATE_FORMAT(tb_file.created,'%Y-%m-%d %H') = '{$date}' ORDER BY tb_file.created LIMIT 1";
        
        return $this->getOne($sql);
    }

    function getStudentDetectionImg(){
        // $sql = "SELECT tb_sd.org_img_url as studentImg,tb_sd.student_id as studentId FROM tb_student_detection as tb_sd WHERE
        // tb_sd.deleted = 0 ORDER BY tb_sd.student_id ASC";
        $sql = "SELECT tb_sd.org_img_url as studentImg,tb_sd.student_id as studentId FROM tb_student_detection as tb_sd WHERE
        tb_sd.student_id = 4 ORDER BY tb_sd.student_id ASC";
        $rs = $this->getAll($sql);
        return $rs;
    }
}

?>
