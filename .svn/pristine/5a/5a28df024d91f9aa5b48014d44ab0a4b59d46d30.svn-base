<?php 
class MessageModel extends BaseModel {

    function getMessageList($schoolId, $timestamp) {
        $this->escape($schoolId);

        $timestampSql = '';
        if($timestamp) {
            $timestampSql = " AND created >= '{$timestamp}' ";
        }

        $sql = "
            SELECT tb_message.*
            FROM tb_message
            WHERE tb_message.deleted = 0 {$timestampSql} AND tb_message.student_id IN
            (
               SELECT id FROM tb_student WHERE deleted = 0 AND school_id = '{$schoolId}'
            )
            ORDER BY tb_message.created DESC
        ";
        
        $rs = $this->getAll($sql);
        foreach($rs as $k => $v) {
            $v['data'] = unserialize($v['data']);
            $rs[$k] = $v;
        }

        return $rs;
    }
}


?>