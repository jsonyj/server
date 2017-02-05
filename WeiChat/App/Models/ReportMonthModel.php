<?php

class ReportMonthModel extends AppModel {

    function getReportList($studentId, $from, $to) {
        $this->escape($studentId);
        $this->escape($from);
        $this->escape($to);
        $sql = "SELECT  * FROM tb_report_month WHERE student_id = '{$studentId}' AND reported >= '{$from}' AND reported <=  '{$to}' AND NOW() >= end AND deleted = 0 ORDER BY end ASC";
        return $this->getAll($sql);
    }

    function getReport($studentId, $id) {
        $this->escape($studentId);
        $this->escape($id);
        $sql = "SELECT  * FROM tb_report_month WHERE id = '{$id}' AND student_id = '{$studentId}'";
        return $this->getOne($sql);
    }
    
    function getReportMonth($studentId , $start, $end){
        $this->escape($studentId);
        $this->escape($start);
        $this->escape($end);
        
        $sql = "SELECT  * FROM tb_report_month WHERE DATE_FORMAT(start,'%Y-%m-%d')  = '{$start}' and '{$end}' = DATE_FORMAT(end,'%Y-%m-%d')  AND student_id = '{$studentId}'";
        
        return $this->getOne($sql);
        
    }
    
    function getReportMonthXin($studentId){
        $this->escape($studentId);
        
        $sql = "SELECT  * FROM tb_report_month WHERE student_id = '{$studentId}'  ORDER BY start DESC LIMIT 1";
        
        return $this->getOne($sql);
        
    }
}

?>
