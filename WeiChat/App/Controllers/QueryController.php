<?php

/**
 * @desc 查询相关功能页面
 * @author
 */

class QueryController extends AppController {

    var $studentModel = null;
    var $studentDetectionModel = null;
    var $articleModel = null;
    var $reportWeekModel = null;
    var $reportMonthModel = null;
    var $chartModel = null;
    var $fileModel = null;
    var $weichatPushMessageModel = null;
    
    function QueryController() {
        $this->AppController();
        $this->studentModel = $this->getModel('Student');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
        $this->articleModel = $this->getModel('Article');
        $this->reportWeekModel = $this->getModel("ReportWeek");
        $this->reportMonthModel = $this->getModel("ReportMonth");
        $this->chartModel = $this->getModel("Chart");
        $this->fileModel = $this->getModel("File");
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
    }

    /**
     * @desc 查询页面
     * @author
     */
    function indexAction() {
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $student_list = $this->studentModel->getStudentListByParentPhone($user['phone']);
        
        $this->view->assign('student_list', $student_list);
        $studentId = $this->get('studentId') ? $this->get('studentId') : $student_list[0]['id'];
        $day = $this->get('day') ? $this->get('day') : date("Y-m-d", time());
        $query_type = $this->get('query_type') ? $this->get('query_type') : SERVICE_TYPE_STATURE;
        $table_type = $this->get('table_type');
        $date_year = $this->get('date_year');
        $date_month = $this->get('date_month');
        $date_week = $this->get('date_week');
        
        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);
        $student['query_type'] = $query_type;
        $student['day'] = $day;
        $student['date_year'] = $date_year;
        $student['date_month'] = $date_month;
        $student['date_week'] = $date_week;
        
        
        $week = $date_week ? $date_week : $day;
        $last_week = date('Y-m-d',strtotime("$week - 7 days")); //上一周时间
        $next_week = date('Y-m-d',strtotime("$week + 7 days")); //下一周时间
        
        $student['last_week'] = $last_week;
        $student['next_week'] = $next_week;
        
        $month = $date_month ? $date_month : $day;
        $last_month = date('Y-m-d', strtotime("$month -1 months"));//上一月时间
        $next_month = date('Y-m-d', strtotime("$month +1 months")); //下一月时间
        
        $student['last_month'] = $last_month;
        $student['next_month'] = $next_month;
        
        $year = $date_year ? $date_year : $day;
        $last_year = date('Y-m-d', strtotime("$year -1 years"));//上一年时间
        $next_year = date('Y-m-d', strtotime("$year +1 years")); //下一月时间
        
        $student['last_year'] = $last_year;
        $student['next_year'] = $next_year;
        
        switch($query_type){
            
            
            case SERVICE_TYPE_IMG: //照片
                $first=1; //$first =1 表示每周星期一为开始时间 0表示每周日为开始时间
                $w = date("w", strtotime($week)); //获取当前周的第几天 周日是 0 周一 到周六是 1 -6
                $d = $w ? $w - $first : 6; //如果是周日 -6天
                $now_start = date("Y-m-d", strtotime("$week -".$d." days")); //本周开始时间
                $now_end = date("Y-m-d", strtotime("$now_start +6 days")); //本周结束时间
                
                $detectionImgList = $this->chartModel->getDetectionImgList($studentId, $now_start, $now_end);
                
                $student['detectionImgList'] = $detectionImgList;
            break;
            
        default:
            break;
            
        }
        
        $this->view->assign('student', $student);
        
        $this->view->layout();
    }

}
?>
