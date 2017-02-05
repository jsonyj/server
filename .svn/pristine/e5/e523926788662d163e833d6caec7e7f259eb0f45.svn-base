<?php

class ReportWeekModel extends AppModel {

    function getStudentList($start, $end) {
        $this->escape($start);
        $this->escape($end);

        $sql = "
            SELECT tb_student.id AS student_id, tb_report_week.id 
            FROM tb_student
            LEFT JOIN tb_report_week ON tb_student.id = tb_report_week.student_id AND start = '{$start}' AND end = '{$end}'
            WHERE tb_student.deleted = 0 AND tb_report_week.id IS NULL LIMIT 30";

        $rs = $this->getAll($sql);

        return $rs;
    }

    function saveReport($form) {
        $table = 'tb_report_week';
        $data = array(
            'student_id' => $form['student_id'],
            'start' => $form['start'],
            'end' => $form['end'],
        );


        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }
}

?>
