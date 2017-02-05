<?php

class ReportMonthModel extends AppModel {

    function getStudentList($start, $end) {
        $this->escape($start);
        $this->escape($end);

        $sql = "
            SELECT tb_student.id AS student_id, tb_report_month.id 
            FROM tb_student
            LEFT JOIN tb_report_month ON tb_student.id = tb_report_month.student_id AND start = '{$start}' AND end = '{$end}'
            WHERE tb_student.deleted = 0 AND tb_report_month.id IS NULL LIMIT 30";

        $rs = $this->getAll($sql);

        return $rs;
    }

    function saveReport($form) {
        $table = 'tb_report_month';
        $data = array(
            'student_id' => $form['student_id'],
            'start' => $form['start'],
            'end' => $form['end'],
            'reported' => $form['reported'],
        );


        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }
}

?>
