<?php 
class ClaimModel extends BaseModel {

    /**
     * @desc 保存
     * @author ly
     * @param $form
     * @return array
     */
    function saveClaim($form) {
        $table = 'tb_detection_claim';
        
        $data = array(
            'detection_id' => $form['detection_id'],
            'terminal_img_id' => $form['terminal_img_id'],
            'school_id' => $form['school_id'],
            'student_id' => $form['student_id'],
            'type' => $form['type'] ? $form['type'] : 0,
            'status' => $form['status'] ? $form['status'] : 1,
            'op_uid' => $form['op_uid'],
            'op_utype' => $form['op_utype'],
        );

        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }
    
    /**
     * @desc 根据日期和状态获取所有
     * @author ly
     * @return array
     */
    function getDetectionClaimList($school_id, $day){
        $this->escape($school_id);
        $this->escape($day);
        
        $sql = " SELECT tb_student_detection.student_id,tb_file.file_path,tb_detection_claim.* 
                 FROM tb_detection_claim 
                 LEFT JOIN tb_student_detection ON tb_student_detection.id = tb_detection_claim.detection_id
                 LEFT JOIN tb_file ON tb_file.id = tb_student_detection.file_img_id
                 WHERE tb_detection_claim.deleted = 0 
                 AND tb_detection_claim.school_id = '{$school_id}'
                 AND tb_detection_claim.type = " . DETECTION_STATUS_RETURN . " 
                 AND tb_detection_claim.status = 1 AND DATE_FORMAT(tb_detection_claim.created, '%Y-%m-%d') = '{$day}'
                 ";

        return $this->getAll($sql);
    }
    
    /**
     * @desc 根据学校和状态获取所有
     * @author ly
     * @return array
     */
    function getDetectionClaimSchoolList($school_id){
        $this->escape($school_id);
    
        $sql = "SELECT tb_file.file_path,tb_detection_claim.*
                FROM tb_detection_claim
                LEFT JOIN tb_student_detection ON tb_student_detection.id = tb_detection_claim.detection_id
                LEFT JOIN tb_file ON tb_file.id = tb_student_detection.file_img_id
                WHERE tb_detection_claim.deleted = 0
                AND tb_detection_claim.type = " . DETECTION_STATUS_RETURN . "
                AND tb_detection_claim.status = 1
                AND tb_detection_claim.school_id = '{$school_id}' AND DATE_FORMAT(tb_detection_claim.created,'%Y-%m-%d')>=DATE_SUB(CURDATE(),INTERVAL 1 day)";

        return $this->getAll($sql);
    }
    
    /**
     * @desc 获取单条
     * @author ly
     * @param $id
     * @return array
     */
    function getClaim($id){
        $this->escape($id);
        
        $sql = "SELECT tb_student_detection.file_img_id,tb_detection_claim.* FROM tb_detection_claim 
                LEFT JOIN tb_student_detection ON tb_student_detection.id = tb_detection_claim.detection_id
                WHERE tb_detection_claim.deleted = 0 AND tb_detection_claim.id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 更新状态
     * @author ly
     * @param $id
     * @return array
     */
    function updateDetectionClaimStatus($id){
        $this->escape($id);
    
        $sql = "UPDATE tb_detection_claim SET status = " . DETECTION_STATUS_RETURN . " WHERE deleted = 0 AND id = '{$id}'";
    
        return $this->getOne($sql);
    }
}


?>