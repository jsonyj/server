<?php

class ReportWeekModel extends AppModel {

    function getReportList($studentId, $from, $to) {
        $this->escape($studentId);
        $this->escape($from);
        $this->escape($to);
        $sql = "SELECT  * FROM tb_report_week WHERE student_id = '{$studentId}' AND end >= '{$from}' AND end <=  '{$to}' AND NOW() >= end AND deleted = 0 ORDER BY end ASC";
        return $this->getAll($sql);
    }

    function getReport($studentId, $id) {
        $this->escape($studentId);
        $this->escape($id);
        $sql = "SELECT  * FROM tb_report_week WHERE id = '{$id}' AND student_id = '{$studentId}'";
        return $this->getOne($sql);
    }
}

?>
