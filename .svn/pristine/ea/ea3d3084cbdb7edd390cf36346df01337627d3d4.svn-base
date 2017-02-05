<?php


class ReportController extends AppController {

    var $studentModel = null;
    var $studentDetectionModel = null;
    var $articleModel = null;
    var $reportWeekModel = null;
    var $reportMonthModel = null;
    var $chartModel = null;
    var $fileModel = null;
    var $weichatPushMessageModel = null;

    function ReportController() {
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

    function indexAction() {
        $user = $this->getSession(SESSION_USER);

        $studentId = $this->get('id');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {

            $detection = $this->studentDetectionModel->getDetection($student['id']);
            $student['studentDetection'] = $detection;

//             $student['studentDetectionImg'] = $this->studentModel->getStudentLatestDetectionImg($student['id']);
            $student['studentDetectionImg'] = $this->fileModel->getFile($detection['file_img_id']);

            $this->view->assign('student', $student);
            
            $signPackage = $this->getWeichatSignPackage();
            $this->view->assign('signPackage', $signPackage);
            $this->view->layout();

        } else {

            $this->redirect('?c=user');
            exit();
        }
    }

    function dayAction() {
        $user = $this->getSession(SESSION_USER);

        $id = $this->get('id');
        $studentId = $this->get('studentId');
        $date = $this->get('day');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {

            $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id']);
            
            if ($id) {
                if ($detection = $this->studentDetectionModel->getDetectionNoStatus($studentId, $id)) {
                    if ($detection['status'] == DETECTION_STATUS_NORMAL) {
                        $nowtime = $detection['created'];
                        $student['studentDetectionImg'] = $this->fileModel->getFile($detection['file_img_id']);
                        $detectionStatus = $this->studentDetectionModel->getDetectionStatus($studentId, $detection['created']);
                        $this->view->assign('detectionStatus', $detectionStatus);
                    }
                    
                    $date = date('Y-m-d', strtotime($detection['created']));
                }
                
            }
            
            $this->view->assign('day', $date);
            $this->view->assign('student', $student);
            $this->view->assign('detection', $detection);
            $grade = $this->calculateGrade($student['class_start']);
            $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_DAY);
            $this->view->assign('articleList', $articleList);

            if($this->get('full')) {
                $this->view->layout();
            } else {
                $this->view->display('day');
            }

        } else {

            $this->redirect('?c=user');
            exit();
        }
    }

    function weekAction() {
        $user = $this->getSession(SESSION_USER);

        $id = $this->get('id');
        $studentId = $this->get('studentId');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {

            $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id']);

            $student['studentDetectionImg'] = $this->studentModel->getStudentLatestDetectionImg($student['id']);

            $this->view->assign('student', $student);

            $report = $this->reportWeekModel->getReport($student['id'], $id);
            $this->view->assign('report', $report);

            $detectionList = $this->studentDetectionModel->getDetectionList($student['id'], $report['start'], $report['end']);

            $this->view->assign('detectionList', $detectionList);

            $grade = $this->calculateGrade($student['class_start']);

            $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_WEEK);
            $this->view->assign('articleList', $articleList);

            if($this->get('full')) {
                $this->view->layout();
            } else {
                $this->view->display('week');
            }

        } else {

            $this->redirect('?c=user');
            exit();
        }
    }

    function monthAction() {
        $user = $this->getSession(SESSION_USER);

        $id = $this->get('id');
        $studentId = $this->get('studentId');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {

            $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id']);

            $student['studentDetectionImg'] = $this->studentModel->getStudentLatestDetectionImg($student['id']);

            $this->view->assign('student', $student);

            $report = $this->reportMonthModel->getReport($student['id'], $id);
            $this->view->assign('report', $report);

            $detectionList = $this->studentDetectionModel->getDetectionList($student['id'], $report['start'], $report['end']);

            $this->view->assign('detectionList', $detectionList);

            $grade = $this->calculateGrade($student['class_start']);

            $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_MONTH);
            $this->view->assign('articleList', $articleList);

            if($this->get('full')) {
                $this->view->layout();
            } else {
                $this->view->display('month');
            }

        } else {

            $this->redirect('?c=user');
            exit();
        }
    }

    function eventAction() {

        $studentId = $this->get('studentId');
        $from = $this->get('from');
        $to = $this->get('to');

        $from = date('Y-m-d H:i:s', $from/1000);
        $to = date('Y-m-d H:i:s', $to/1000);

        $out = array();
        $detectionList = $this->studentDetectionModel->getDetectionList($studentId, $from, $to);
        
        for ($i = 0; strtotime($from.'+'.$i.' days') <= strtotime($to); $i++) {
            foreach($detectionList as $detection) {
                if(date('d', strtotime($from.'+'.$i.' days')) == date('d', strtotime($detection['created']))){
                    $out[] = array(
                        'id' => $detection['id'],
                        'class' => 'event-day',
                        'title' => '日报 ' . date('H:i', strtotime($detection['created'])),
                        'url' => "?c=report&a=day&studentId={$studentId}&id={$detection['id']}&day=". date('Y-m-d', strtotime($from.'+'.$i.' days')),
                        'start' => (strtotime($detection['created'])) . '000',
                        'end' => (strtotime($detection['created'])) .'000',
                    );
                }
            }
            
            if(strtotime($from.'+'.$i.' days') < time()){
                if(!array_key_exists($i,$out)){
                    $out[] = array(
                        'id' => 'id-'.$i,
                        'class' => '',
                        'title' => '日报：无数据，点击去领取',
                        'url' => "?c=report&a=day&studentId={$studentId}&day=". date('Y-m-d', strtotime($from.'+'.$i.' days')),
                        'start' => (strtotime($from.'+'.$i.' days')) . '000',
                        'end' => (strtotime($from.'+'.$i.' days')) .'000',
                    );
                }
             }
        }

        /** 不显示周报
        $weekReportList = $this->reportWeekModel->getReportList($studentId, $from, $to);
        foreach($weekReportList as $weekReport) {
            $out[] = array(
                'id' => $weekReport['id'],
                'class' => 'event-week',
                'title' => '周报（' . date('n月j日', strtotime($weekReport['start'])) . ' 至 ' .  date('n月j日', strtotime($weekReport['end'])) . '）',
                'url' => "?c=report&a=week&studentId={$studentId}&id={$weekReport['id']}",
                'start' => (strtotime($weekReport['end'])) . '000',
                'end' => (strtotime($weekReport['end'])) .'000',
            );
        }
        **/

        $monthReportList = $this->reportMonthModel->getReportList($studentId, $from, $to);
        foreach($monthReportList as $monthReport) {
            $out[] = array(
                'id' => $monthReport['id'],
                'class' => 'event-month',
                'title' => '月报（' . date('n月', strtotime($weekReport['start'])) . '）',
                'url' => "?c=report&a=month&studentId={$studentId}&id={$monthReport['id']}",
                'start' => (strtotime($monthReport['reported'])) . '000',
                'end' => (strtotime($monthReport['reported'])) .'000',
            );
        }

        /**
        for($i = 0; $i < 32; $i++) {
            $out[] = array(
                'id' => $i+1,
                'class' => 'event-day',
                'title' => '日报 ' . $i,
                'url' => '?c=report&a=day&id=' . ($id+1),
                'start' => (time() + $i*24*60*60) . '000',
                'end' => (time() + $i*24*60*60) .'000'
            );

            if(date('w', (time() + $i*24*60*60)) == 6) {
              $out[] = array(
                  'id' => $i+1,
                  'class' => 'event-week',
                  'title' => '周报 ' . $i,
                  'url' => '?c=report&a=week&id=' . ($id+1),
                  'start' => (time() + $i*24*60*60) . '000',
                  'end' => (time() + $i*24*60*60) .'000'
              );
            }

            if(date('j', (time() + $i*24*60*60)) == 1) {
              $out[] = array(
                  'id' => $i+1,
                  'class' => 'event-month',
                  'title' => '月报 ' . $i,
                  'url' => '?c=report&a=month&id=' . ($id+1),
                  'start' => (time() + $i*24*60*60) . '000',
                  'end' => (time() + $i*24*60*60) .'000'
              );
            }
        }
        **/

        echo json_encode(array('success' => 1, 'result' => $out));
        exit;
    }

    /**
     * 
     */
    function chartAction() {
        
        $user = $this->getSession(SESSION_USER);
        $studentId = $this->get('studentId'); //学生ID
        $type = $this->get('type'); // 1 - 一周体温 2 - 月身高曲线图 3 - 月体重曲线图
        $start = $this->get('start');//格式 yyyy-mm-dd hh:ii:ss
        switch ($type){
            case 1:
                $now = strtotime($start);
                $start = date('Y-m-d 00:00:00', $now - 6 * 24 * 60 * 60);
                $end = date('Y-m-d 23:59:59', $now);

                $data = $this->chartModel->getDetectionList($studentId, $start, $end);// 1. 查询 tb_student_detection 当前开始过去一周数据
                $this->chartModel->genWeekTemperatureChart($data, 700, 230);//2. 生成图表输出
                break;

            case 2:
                $firstday = date('Y-m-01 00:00:00', strtotime("$start -1 month"));
                $lastday = date('Y-m-d 23:59:59', strtotime("$firstday +1 month -1 day")); //计算start时间上一个月

                $data = $this->chartModel->getDetectionList($studentId, $firstday, $lastday);
                $this->chartModel->genMonthHeightChart($data, 700, 230); //获取上一个月所有数据显示曲线图 
                break;
                
            case 3:
                $firstday = date('Y-m-01 00:00:00', strtotime("$start -1 month"));
                $lastday = date('Y-m-d 23:59:59', strtotime("$firstday +1 month -1 day")); //计算start时间:上一个月
                
                $data = $this->chartModel->getDetectionList($studentId, $firstday, $lastday);
                $this->chartModel->genMonthWeightChart($data, 700, 230); //获取上一个月所有数据显示曲线图
                break;
                
            case 4://得到时间的上一年的身高
                $firstday = date('Y-01-01 00:00:00', strtotime("$start -1 year"));
                $lastday = date('Y-m-d 23:59:59', strtotime("$firstday +1 year -1 day"));
                
                $data = $this->chartModel->getDetectionList($studentId, $firstday, $lastday);
                $this->chartModel->genYearHeightChart($data, 700, 230);
                break;
                
            case 5://得到时间的上一年的体重
                $firstday = date('Y-01-01 00:00:00', strtotime("$start -1 year"));
                $lastday = date('Y-m-d 23:59:59', strtotime("$firstday +1 year -1 day"));
                
                $data = $this->chartModel->getDetectionList($studentId, $firstday, $lastday);
                $this->chartModel->genYearWeightChart($data, 700, 230);
                break;
                
            default:
                break;
        }
        
    }
    
     /**
     * @desc 心微信查询报告页面
     * @author wei
     */
    function queryChartAction() {
        $user = $this->getSession(SESSION_USER);
        
        $studentId = $this->get('studentId');
        $day = $this->get('day');
        $query_type = $this->get('query_type');
        $table_type = $this->get('table_type');
        
        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);
        
        switch($query_type){
            case SERVICE_TYPE_STATURE: //身高
                if($table_type == QUERY_TABLE_MONTH){//月表
                    $month_start = date('Y-m-01', strtotime($day));
                    $month_end = date('Y-m-d', strtotime("$month_start +1 month -1 day"));
                    $data = $this->chartModel->getDetectionList($studentId, $month_start, $month_end);
                    
                    $this->chartModel->genMonthHeightChart($data, 700, 230);
                    
                }else if($table_type == QUERY_TABLE_YEAR){//年表
                    $year_start = date('Y-01-01', strtotime($day));
                    $year_end = date('Y-12-31', strtotime($year_start));
                    $data = $this->chartModel->getDetectionYearList($studentId, $year_start, $year_end);
                    $this->chartModel->genYearHeightChart($data, 700, 230);
                    
                }else if($table_type == QUERY_TABLE_TOTAL){//总表
                    
                    $data = $this->chartModel->getDetectionQuarterList($studentId);
                    
                    $this->chartModel->genQuarterHeightChart($data, 700, 230);
                }
            break;
            
            case SERVICE_TYPE_WEIGHT: //体重
                if($table_type == QUERY_TABLE_MONTH){//月表
                    $month_start = date('Y-m-01', strtotime($day));
                    $month_end = date('Y-m-d', strtotime("$month_start +1 month -1 day"));
                    $data = $this->chartModel->getDetectionList($studentId, $month_start, $month_end);
                    $this->chartModel->genMonthWeightChart($data, 700, 230);
                    
                }else if($table_type == QUERY_TABLE_YEAR){//年表
                    $year_start = date('Y-01-01', strtotime($day));
                    $year_end = date('Y-12-31', strtotime($year_start));
                    $data = $this->chartModel->getDetectionYearList($studentId, $year_start, $year_end);
                    $this->chartModel->genYearWeightChart($data, 700, 230);
                    
                }else if($table_type == QUERY_TABLE_TOTAL){//总表
                    
                    $data = $this->chartModel->getDetectionQuarterList($studentId);
                    $this->chartModel->genQuarterWeightChart($data, 700, 230);
                }
            break;
            
            case SERVICE_TYPE_TEMPERATURE: //温度
                $first=1; //$first =1 表示每周星期一为开始时间 0表示每周日为开始时间
                $w = date("w", strtotime($day)); //获取当前周的第几天 周日是 0 周一 到周六是 1 -6
                $d = $w ? $w - $first : 6; //如果是周日 -6天
                $now_start = date("Y-m-d", strtotime("$day -".$d." days")); //本周开始时间
                $now_end = date("Y-m-d", strtotime("$now_start +6 days")); //本周结束时间
                
                $data = $this->chartModel->getDetectionList($studentId, $now_start, $now_end);// 1. 查询 tb_student_detection 当前开始过去一周数据
                $this->chartModel->genWeekTemperatureChart($data, 700, 230);//2. 生成图表输出
            break;
            
        default:
            break;
            
        }
    }
    /**
     * @desc ajax 手动发送当天日报消息
     * @author wei
     */
    function ajaxStudentReportDayAction(){
        $studentId = $this->post('studentId');
        $studentDetectionDay =  $this->studentModel->getStudentDetectionDay($studentId, date("Y-m-d", time()));
        $studentSchool = $this->studentModel->getStudentSchool($studentId);
        if($studentDetectionDay){
            //保存推送信息
            if($studentDetectionDay['weichat_num'] < DETECTION_WEICHAT_TOTAL){
                
                $parentList = $this->studentModel->getStudentParentList($studentId);
                foreach($parentList as $parent) {
                    if($parent['openid']) {
                        $form = array(
                            'open_id' => $parent['openid'],
                            'day_report_id' => $studentDetectionDay['id'],
                            'detection' => $studentDetectionDay,
                            'school_title' => $studentSchool['school_title'],
                            'studentId' => $studentId,
                            'send_time' => NOW,
                        );

                        $form['message'] = $this->weichatPushMessageModel->genDayReportMessage($form);
                        $this->weichatPushMessageModel->saveMessage($form);
                    }
                    
                }
                $this->studentModel->updateStudentDetectionDay($studentDetectionDay['id']);
                
                $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '信息发送成功。', // code 不为 0 时的错误信息
                );
                
            }else{
                $return = array(
                    'code' => 1, //  0 - 成功
                    'message' => '已发送，当日无法再次发送。', // code 不为 0 时的错误信息
                );
                
            }
            
        }else{
            $return = array(
                'code' => 1, //  0 - 成功
                'message' => '学生当天没有上传检测数据。', // code 不为 0 时的错误信息
            );
        }
        echo json_encode($return);
        exit();
        
    }

}
?>
