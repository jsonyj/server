<?php

class StudentDetectionModel extends AppModel {

    function getDetectionList($studentId, $start, $end) {
        $sh = array(
            'studentId' => $studentId,
            'start' => $start,
            'end' => $end,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT tb_student_detection.*
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0 AND tb_student_detection.first = 1 
            AND tb_student_detection.`status` = " . DETECTION_STATUS_NORMAL;
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.created', 'start', '>=');
        $this->where($sql, 'tb_student_detection.created', 'end', '<=');

        $sql .= " ORDER BY tb_student_detection.created ";
        
        $rs = $this->getAll($sql);

        return $rs;
    }

    function getDetection($studentId, $id) {
        $sh = array(
            'studentId' => $studentId,
            'id' => $id,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT tb_student_detection.*, tb_file.file_path as file_path
            FROM tb_student_detection
            LEFT JOIN tb_file ON  tb_student_detection.file_img_id = tb_file.id  AND tb_file.deleted = 0
            WHERE tb_student_detection.deleted = 0
            AND tb_student_detection.`status` = " . DETECTION_STATUS_NORMAL;
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.id', 'id', '=');

        $this->order($sql, 'order.default');
        $rs = $this->getOne($sql);

        return $rs;
    }
    
    function getDetectionById($id) {
        $this->escape($id);
        
        $sql = "
            SELECT *
            FROM tb_student_detection
            WHERE deleted = 0 AND id = '{$id}'
            AND status = 
        " . DETECTION_STATUS_NORMAL;
        
        return $this->getOne($sql);
    }
    
    function getDetectionNoStatus($studentId, $id) {
        $sh = array(
            'studentId' => $studentId,
            'id' => $id,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT tb_student_detection.*
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0
        ";
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.id', 'id', '=');
        
        
        if($id == ""){
            $time = date('Y-m-d', time());
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') = '{$time}' AND tb_student_detection.status = " . DETECTION_STATUS_NORMAL;
            
        }

        $this->order($sql, 'order.default');
        
        $rs = $this->getOne($sql);

        return $rs;
    }
    /**
     * @desc 获取家长小孩的照片
     * @author wei
     */    
    function getDetectionNoStatusList($studentId, $date, $id) {
        $this->escape($studentId);
        $time = date('Y-m-d', strtotime($date));
        
        $sql = "
            SELECT tb_student_detection.* FROM tb_student_detection WHERE tb_student_detection.deleted = 0 AND tb_student_detection.student_id = '{$studentId}' AND DATE_FORMAT(created,'%Y-%m-%d') = '{$time}' 
            AND tb_student_detection.status = ". DETECTION_STATUS_NORMAL ."          
            ";
        
        $this->where($sql, 'tb_student_detection.id', 'id', '=');
        
        $sql .= " order by created DESC ";
        
        $rs = $this->getAll($sql);
        return $rs;
    }

    /**
     * @desc 获取家长小孩的照片
     * @author ly
     * @param $studentId
     * @return array
     */
    function getDetectionStatus($studentId, $datetime) {
        $this->escape($studentId);
        $this->escape($datetime);

        $sql = "
            SELECT tb_file.file_path,tb_student_detection.*
            FROM tb_student_detection
            LEFT JOIN tb_file ON tb_file.id = tb_student_detection.file_img_id
            WHERE tb_student_detection.deleted = 0
            AND tb_student_detection.`status` = " . DETECTION_STATUS_NORMAL . "
            AND tb_student_detection.student_id = '{$studentId}'
            AND tb_student_detection.created < '{$datetime}'
            ORDER BY created DESC LIMIT 1";

        $rs = $this->getOne($sql);
        
        return $rs;
    }

    function getDetectionMaxWeight($studentId, $start, $end) {
        $sh = array(
            'studentId' => $studentId,
            'start' => $start,
            'end' => $end,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT MAX(tb_student_detection.weight) AS v
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0 AND tb_student_detection.first = 1 ";
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.created', 'start', '>=');
        $this->where($sql, 'tb_student_detection.created', 'end', '<=');

        $rs = $this->getOne($sql);

        return $rs['v'];
    }

    function getDetectionMinWeight($studentId, $start, $end) {
        $sh = array(
            'studentId' => $studentId,
            'start' => $start,
            'end' => $end,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT MIN(tb_student_detection.weight) AS v
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0 AND tb_student_detection.first = 1 ";
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.created', 'start', '>=');
        $this->where($sql, 'tb_student_detection.created', 'end', '<=');

        $rs = $this->getOne($sql);

        return $rs['v'];
    }

    function getDetectionMaxHeight($studentId, $start, $end) {
        $sh = array(
            'studentId' => $studentId,
            'start' => $start,
            'end' => $end,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT MAX(tb_student_detection.height) AS v
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0 AND tb_student_detection.first = 1 ";
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.created', 'start', '>=');
        $this->where($sql, 'tb_student_detection.created', 'end', '<=');

        $rs = $this->getOne($sql);

        return $rs['v'];
    }

    function getDetectionMinHeight($studentId, $start, $end) {
        $sh = array(
            'studentId' => $studentId,
            'start' => $start,
            'end' => $end,
        );

        $this->setSearch($sh);
        $sql = "
            SELECT MIN(tb_student_detection.height) AS v
            FROM tb_student_detection
            WHERE tb_student_detection.deleted = 0 AND tb_student_detection.first = 1 ";
        
        $this->where($sql, 'tb_student_detection.student_id', 'studentId', '=');
        $this->where($sql, 'tb_student_detection.created', 'start', '>=');
        $this->where($sql, 'tb_student_detection.created', 'end', '<=');

        $rs = $this->getOne($sql);

        return $rs['v'];
    }
    
    /**
     * @desc 更新状态
     * @author ly
     * @param $id
     * @return array
     */
    function updateStudentDetectionStatus($id){
        $this->escape($id);
        
        $sql = "UPDATE tb_student_detection SET status = " . DETECTION_STATUS_RETURN . " WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 更新状态及学生id
     * @author ly
     * @param $id
     * @param $student_id
     * @return array
     */
    function updateStudentDetectionStudentIdAndStatus($id, $student_id){
        $this->escape($id);
        $this->escape($student_id);
        
        $sql = "UPDATE tb_student_detection SET status = " . DETECTION_STATUS_NORMAL . ", student_id = '{$student_id}' WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据学生id获取当天第一条数据
     * @author ly
     * @param $student_id
     * @param $day
     * @return array
     */
    function getStudentDetectionDesc($student_id, $day){
        $this->escape($student_id);
        $this->escape($day);
        
        $sql = "SELECT * FROM tb_student_detection WHERE deleted = 0 AND student_id = '{$student_id}'
        AND DATE_FORMAT(created, '%Y-%m-%d') = '{$day}' AND status = ".DETECTION_STATUS_NORMAL."
        ORDER BY created DESC LIMIT 1 ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据学生id获取当天最后一条数据
     * @author ly
     * @param $student_id
     * @param $day
     * @return array
     */
    function getStudentDetectionAsc($student_id, $day){
        $this->escape($student_id);
        $this->escape($day);
        
        $sql = "SELECT * FROM tb_student_detection WHERE deleted = 0 AND student_id = '{$student_id}'
        AND DATE_FORMAT(created, '%Y-%m-%d') = '{$day}' AND status = ".DETECTION_STATUS_NORMAL."
        ORDER BY created ASC LIMIT 1 ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据学生id查询当天是否第一条数据
     * @author ly
     * @param $student_id
     */
    function getStudentDetectionByFirst($student_id, $day){
        $this->escape($student_id);
        $this->escape($day);
        
        $sql = " SELECT * FROM tb_student_detection
                WHERE deleted = 0 AND student_id = '{$student_id}' AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$day}' 
                AND first = 1 AND status = " . DETECTION_STATUS_NORMAL;
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 根据学生id查询当天是否最后一条数据
     * @author ly
     * @param $student_id
     */
    function getStudentDetectionByLastest($student_id, $day){
        $this->escape($student_id);
        $this->escape($day);
        
        $sql = " SELECT * FROM tb_student_detection
                WHERE deleted = 0 AND student_id = '{$student_id}' AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$day}' 
                AND lastest = 1 AND status =" . DETECTION_STATUS_NORMAL;
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 修改第一条字段
     * @author ly
     * @param $id
     * @return NULL|unknown
     */
    function updateDetectionFirst($id, $first){
        $this->escape($id);
        $this->escape($first);
        
        $sql = "UPDATE tb_student_detection SET first = '{$first}' WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 修改最后一条字段
     * @author ly
     * @param $id
     * @return NULL|unknown
     */
    function updateDetectionLastest($id, $lastest){
        $this->escape($id);
        $this->escape($lastest);
        
        $sql = "UPDATE tb_student_detection SET lastest = '{$lastest}' WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
}

?>
