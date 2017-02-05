<?php

class SchoolModel extends AppModel {

    /**
     * @desc 每日到校统计
     * @param $sh
     * @author ly
     */
    function getDayArriveSchoolStatistic($school_id, $date){
        $this->escape($date);
        $this->escape($school_id);

        $sql = "SELECT COUNT(id) AS num FROM (
                    SELECT tb_student_detection.* FROM tb_student_detection
                    LEFT JOIN tb_student ON tb_student.id = tb_student_detection.student_id
                    WHERE tb_student_detection.deleted = 0 AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$date}' 
                    AND tb_student.school_id = '{$school_id}' 
                    GROUP BY tb_student_detection.student_id 
                ) AS temp
                ";

        $rs = $this->getOne($sql);
        return $rs['num'];
    }

    /**
     * @desc 每日离校统计
     * @param $sh
     * @author ly
     */
    function getDayLeaveSchoolStatistic($school_id, $date){
        $this->escape($date);
        $this->escape($school_id);

        $sql = "SELECT COUNT(id) AS num FROM (
                    SELECT tb_student_away_record.* FROM tb_student_away_record
                    LEFT JOIN tb_student ON tb_student.id = tb_student_away_record.student_id
                    WHERE DATE_FORMAT(tb_student_away_record.created, '%Y-%m-%d') = '{$date}' 
                    AND tb_student.school_id = '{$school_id}' 
                    GROUP BY tb_student_away_record.student_id 
                ) AS temp
                ";

        $rs = $this->getOne($sql);
        return $rs['num'];
    }

    /**
     * @desc 每日班级到校统计
     * @param $sh
     * @author ly
     */
    function getClassArriveSchoolNumList($school_id, $date){
        $this->escape($date);
        $this->escape($school_id);

        $sql = "SELECT temp.class_id,temp.title,COUNT(temp.id) AS num FROM (
                    SELECT
                        tb_student.class_id,
                        tb_class.title,
                        tb_student_detection.*
                    FROM tb_student_detection
                    LEFT JOIN tb_student ON tb_student.id = tb_student_detection.student_id
                    LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
                    WHERE tb_student_detection.deleted = 0 AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$date}' 
                    AND tb_student.school_id = '{$school_id}' 
                    GROUP BY tb_student_detection.student_id 
                ) AS temp GROUP BY temp.class_id
                ";

        return $this->getAll($sql);
    }

    /**
     * @desc 每日班级离校统计
     * @param $sh
     * @author ly
     */
    function getClassLeaveSchoolNumList($school_id, $date){
        $this->escape($date);
        $this->escape($school_id);

        $sql = "SELECT temp.class_id,temp.title,COUNT(temp.id) AS num FROM (
                    SELECT
                        tb_student.class_id,
                        tb_class.title,
                        tb_student_away_record.*
                    FROM tb_student_away_record
                    LEFT JOIN tb_student ON tb_student.id = tb_student_away_record.student_id
                    LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
                    WHERE DATE_FORMAT(tb_student_away_record.created, '%Y-%m-%d') = '{$date}' 
                    AND tb_student.school_id = '{$school_id}' 
                    GROUP BY tb_student_away_record.student_id 
                ) AS temp GROUP BY temp.class_id
                ";

        return $this->getAll($sql);
    }

    /**
     * @desc 每日老师班级到校统计
     * @param $sh
     * @author ly
     */
    function getTeacherClassArriveSchoolList($sh, $date){
        $this->escape($date);
        $this->setSearch($sh);
    
        $sql = "SELECT
                    tb_student.class_id,
                    tb_class.title,
                    tb_student_detection.*
                    FROM tb_student_detection
                    LEFT JOIN tb_student ON tb_student.id = tb_student_detection.student_id
                    LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
                    LEFT JOIN tb_staff_class ON tb_staff_class.class_id = tb_class.id
                    LEFT JOIN tb_staff ON tb_staff.id = tb_staff_class.staff_id
                    WHERE tb_student_detection.deleted = 0 AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$date}'
                ";
        
        $this->where($sql, "tb_student.school_id", "school_id", "=");
        $this->where($sql, "tb_staff.type", "role", "=");
        $this->where($sql, "tb_staff.phone", "phone", "=");

        $sql .= " GROUP BY tb_student_detection.student_id ";
        
        $sqlCount = "SELECT temp.class_id,temp.title,COUNT(temp.id) AS num FROM (".$sql.") AS temp GROUP BY temp.class_id";
        
        return $this->getAll($sqlCount);
    }


    /**
     * @desc 每日老师班级离校统计
     * @param $sh
     * @author ly
     */
    function getTeacherClassLeaveSchoolList($sh, $date){
        $this->escape($date);
        $this->setSearch($sh);
    
        $sql = "SELECT
                    tb_student.class_id,
                    tb_class.title,
                    tb_student_away_record.*
                    FROM tb_student_away_record
                    LEFT JOIN tb_student ON tb_student.id = tb_student_away_record.student_id
                    LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
                    LEFT JOIN tb_staff_class ON tb_staff_class.class_id = tb_class.id
                    LEFT JOIN tb_staff ON tb_staff.id = tb_staff_class.staff_id
                    WHERE DATE_FORMAT(tb_student_away_record.created, '%Y-%m-%d') = '{$date}'
                ";
        
        $this->where($sql, "tb_student.school_id", "school_id", "=");
        $this->where($sql, "tb_staff.type", "role", "=");
        $this->where($sql, "tb_staff.phone", "phone", "=");
        
        $sql .= " GROUP BY tb_student_away_record.student_id ";
        
        $sqlCount = "SELECT temp.class_id,temp.title,COUNT(temp.id) AS num FROM (".$sql.") AS temp GROUP BY temp.class_id";
        
        return $this->getAll($sqlCount);
    }

    /**
     * @desc 每日班级到校学生信息
     * @param $sh
     * @author ly
     */
    function getClassArriveStudentList($sh, $date){
        $this->escape($date);
        $this->setSearch($sh);

        $sql = "SELECT
                    tb_student.name,
                    tb_student_detection.*
                FROM tb_student_detection
                LEFT JOIN tb_student ON tb_student.id = tb_student_detection.student_id
                WHERE tb_student_detection.deleted = 0 AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$date}'
                ";

        $this->where($sql, "tb_student.school_id", "school_id", "=");
        $this->where($sql, "tb_student.class_id", "class_id", "=");

        $sql .= " GROUP BY tb_student_detection.student_id ";

        return $this->getAll($sql);
    }

    /**
     * @desc 每日班级离校学生信息
     * @param $sh
     * @author ly
     */
    function getClassLeaveStudentList($sh, $date){
        $this->escape($date);
        $this->setSearch($sh);

        $sql = "SELECT
                    tb_student.name,
                    tb_relation.title,
                    tb_student_away_record.*
                FROM tb_student_away_record
                LEFT JOIN tb_student ON tb_student.id = tb_student_away_record.student_id
                LEFT JOIN tb_student_parent ON tb_student_parent.school_id = tb_student.school_id
                AND tb_student_parent.class_id = tb_student.class_id
                AND tb_student_parent.student_id = tb_student.id
                LEFT JOIN tb_relation ON tb_relation.id = tb_student_parent.relation_id
                WHERE DATE_FORMAT(tb_student_away_record.created, '%Y-%m-%d') = '{$date}'
                ";

        $this->where($sql, "tb_student.school_id", "school_id", "=");
        $this->where($sql, "tb_student.class_id", "class_id", "=");

        $sql .= " GROUP BY tb_student_away_record.student_id ";

        return $this->getAll($sql);
    }

    /**
     * @desc 统计学校体温偏高
     * @author ly
     * @param $school_id
     * @param $date
     * @return array
     */
    function getSchoolTemperatureStatisticList($school_id, $date){
        $this->escape($school_id);
        $this->escape($date);
        
        $sql = "SELECT temp2.* FROM (
                    SELECT temp.* FROM (
                        SELECT tb_class.title,tb_student.name,tb_student_detection.* FROM tb_student_detection
                        LEFT JOIN tb_student ON tb_student.id = tb_student_detection.student_id
                        LEFT JOIN tb_class ON tb_class.id = tb_student.class_id
                        WHERE tb_student_detection.deleted = 0 
                        AND tb_student.school_id = '{$school_id}'
                        AND DATE_FORMAT(tb_student_detection.created, '%Y-%m-%d') = '{$date}'
                        ORDER BY tb_student_detection.created DESC
                    ) AS temp
                    GROUP BY temp.student_id
                    ORDER BY temp.created DESC
                ) AS temp2
                WHERE temp2.temperature > temp2.temperature_threshold ";
        
        return $this->getAll($sql);
    }

    /**
     * @desc 班级学生应该到校人数
     * @param
     * @author wjd
     */
    function getStudentsNum($class_id){
        $this->escape($class_id);

        $sql = "
        SELECT COUNT(*) AS mum FROM tb_student
        WHERE deleted = 0
        AND class_id = '{$class_id}' ";

        return $this->getAll($sql);
    }

    /**
     * @desc 当日签到统计
     * @author ly
     * @param $school_id
     * @param $date
     * @return array
     */
    function getDaySignStatisticList($school_id, $date){
        $this->escape($school_id);
        $this->escape($date);
    
        $sql = "SELECT tb_class.title,tb_staff_signdate.sign_status,
                tb_staff_signdate.in_time,
                tb_staff_signdate.out_time,
                tb_staff.* 
                FROM tb_staff
                LEFT JOIN tb_staff_signdate ON tb_staff_signdate.staff_id = tb_staff.id 
                AND tb_staff_signdate.sign_timestamp = '{$date}' 
                LEFT JOIN tb_staff_class ON tb_staff_class.staff_id = tb_staff.id
                LEFT JOIN tb_class ON tb_class.id = tb_staff_class.class_id
                WHERE tb_staff.deleted = 0
                AND tb_staff.school_id = '{$school_id}'
                AND tb_staff.type != " . ACT_SCHOOL_HEADMASTER . "
                ORDER BY tb_staff.type DESC
                ";

        return $this->getAll($sql);
    }

    /**
     * @desc 本月统计
     * @author ly
     * @param $school_id
     * @param $type
     * @param $staff_id
     * @param $date
     * @return array
     */
    function getMonthSignStatisticList($school_id, $staff_id, $date){
        $this->escape($school_id);
        $this->escape($date);
        $this->escape($staff_id);
        
        $sql = "SELECT
                    COUNT(tb_staff_signdate.id) AS num,
                    tb_staff_signdate.*
                FROM
                    tb_staff_signdate
                LEFT JOIN tb_staff ON tb_staff.id = tb_staff_signdate.staff_id
                WHERE
                    tb_staff_signdate.deleted = 0
                AND tb_staff_signdate.staff_id = '{$staff_id}'
                AND tb_staff.school_id = '{$school_id}'
                AND FROM_UNIXTIME(tb_staff_signdate.sign_timestamp,'%Y-%m') = '{$date}'
                GROUP BY tb_staff_signdate.sign_status
                ";

        return $this->getAll($sql);
    }

    /**
     * @desc 获取所有职工信息
     * @author ly
     * @param $sh
     * @return array
     */
    function getStaffList($school_id){
        $this->escape($school_id);
    
        $sql = "SELECT tb_class.title,tb_staff.* FROM tb_staff
                LEFT JOIN tb_staff_class ON tb_staff_class.staff_id = tb_staff.id
                LEFT JOIN tb_class ON tb_class.id = tb_staff_class.class_id
                WHERE tb_staff.deleted = 0 
                AND tb_staff.school_id = '{$school_id}'
                AND tb_staff.type != " . ACT_SCHOOL_HEADMASTER. "
                ORDER BY tb_staff.type DESC
                ";
    
        return $this->getAll($sql);
    }
}

?>
