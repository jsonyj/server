<?php

class StudentController extends AppController {

    var $parentModel = null;
    var $studentModel = null;
    var $schoolParentModel = null;
    var $articleModel = null;
    var $relationModel = null;
    
    function StudentController() {
        $this->AppController();
        $this->parentModel = $this->getModel('Parent');
        $this->studentModel = $this->getModel('Student');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->articleModel = $this->getModel('Article');
        $this->relationModel = $this->getModel('Relation');
    }

    /**
     * @desc 接小孩二维码查看
     * @desc 界面 - wzl
     */
    function qrcodeAction() {
        $wxUser = $this->getAccessWxUser();
        
        //强制页面不缓存
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
        
        $student_id = $this->get('student_id', 0);
        
        $parent = $this->parentModel->getParentByWeichatId($wxUser['id']);
        $student_list = $this->studentModel->getStudentListByParentPhone($parent['phone']);
        
        if ($student_id) {
            $student_info = array();
            foreach ($student_list as $student) {
                if ($student['id'] == $student_id) {
                    $student_info[] = $student;
                }
            }
            $student_list = $student_info;
        }
        
        $parent['student_list'] = $student_list;
        
        $this->view->assign('random_num', time());
        $this->view->assign('parent', $parent);
        $this->view->layout();
    }

    /**
     * @desc 小孩已接通知页面
     * @desc 界面 - wzl
     */
    function awayNoticeAction() {
        $studentAwayId = $this->get('id');
        $awayNotice = array();
        
        $rs = $this->studentModel->getStudentAway($studentAwayId);
        $awayNotice['time'] = $rs['created'] ? date('Y-m-d H:i',strtotime($rs['created'])) : '';
        $awayNotice['img'] = $rs['img'];
        
        $student = $this->studentModel->getStudent($rs['student_id']);
        $awayNotice['student'] = $student['name'];
        
        $parent = $this->schoolParentModel->getParentRel($rs['student_id'], $rs['parent_id']);
        $awayNotice['parent'] = $parent['relation_title'];
        
        $grade = $this->calculateGrade($student['start']);
        $articleList = $this->articleModel->getArticleList($student['school_id'], $student['class_id'], $grade, ARTICLE_AUTH_REPORT_DAY);
        $this->view->assign('articleList', $articleList);
        
        $this->view->assign('awayNotice', $awayNotice);
        $this->view->layout();
    }
}
?>
