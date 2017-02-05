<?php

class ParentController extends AppController {

    var $schoolModel = null;
    var $classModel = null;
    var $studentModel = null;
    var $schoolParentModel = null;
    var $parentModel = null;
    var $relationModel = null;
    var $smsModel = null;

    function ParentController() {
        $this->AppController();
        $this->schoolModel = $this->getModel('School');
        $this->classModel = $this->getModel('Class');
        $this->studentModel = $this->getModel('Student');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->parentModel = $this->getModel('Parent');
        $this->relationModel = $this->getModel('Relation');
        $this->smsModel = $this->getModel('Sms');
    }

    function indexAction() {
        $user = $this->getSession(SESSION_USER);

        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $parentList = $this->schoolParentModel->getParentList($user['school_id'], $sh);
        $this->view->assign('paging', $this->schoolParentModel->paging);

        foreach($parentList as $k => $v) {
            $v['studentList'] = $this->studentModel->getStudentListByParentId($user['school_id'], $v['id']);
            $parentList[$k] = $v;
        }

        $this->view->assign('parentList', $parentList);

        $this->view->layout();
    }

    function inputAction() {
        $user = $this->getSession(SESSION_USER);

        $id = $this->get('id');
        $form = $this->post('form');

        $form['school_id'] = $user['school_id'];
        
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->schoolParentModel->validSaveParent($form, $errors)) {
                if ($rs = $this->schoolParentModel->saveParent($form)) {//保存学校家长
                    $this->redirect("?c=parent&a=input&pc=parent&pa=index&id={$rs}", true, '保存成功');
                    exit;
                }
                
                $this->redirect("?c=parent&a=input&pc=parent&pa=index&id={$id}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            $form = $this->schoolParentModel->getParent($id);
            if($id && empty($form)) {
                 $this->redirect("?c=parent&a=index", true, $this->lang('ID不存在'));
                 exit();
            }
            $this->view->assign('form', $form);
        }

        $parentStudentList = $this->schoolParentModel->getParentStudentList($user['school_id'], $id);
        $this->view->assign('parentStudentList', $parentStudentList);

        $relationList = $this->relationModel->getRelationOptionList();
        $this->view->assign('relationList', $relationList);

        $this->view->layout();
    }

    function deleteAction() {
        $id = $this->get('id');
        $page = $this->get('page');
        
        if ($schoolParent = $this->schoolParentModel->getParent($id)) {
            $phone = $schoolParent['phone'];
            $this->parentModel->deleteParentByPhone($phone);
        }
        
        $this->schoolParentModel->deleteParent($id);
        $this->schoolParentModel->deleteStudentParentByParentId($id);
        if($page){
            $this->redirect("?c=student&a=index&sh[page]=".$page, true, '删除成功');
        }else{
            $this->redirect("?c=student&a=index", true, '删除成功');
        }
    }

    function inputStudentAction() {

        $user = $this->getSession(SESSION_USER);

        $id = $this->get('id');
        $parentId = $this->get('parentId');
        $form = $this->post('form');

        $parent = $this->schoolParentModel->getParent($parentId);
        $this->view->assign('parent', $parent);

        $form['school_id'] = $user['school_id'];

        if ($this->isComplete()) {
            $form['id'] = $id;
            $form['parent_id'] = $parentId;
            if ($this->schoolParentModel->validSaveStudentParent($form, $errors)) {

                include(LIBRARY . 'mutex.php');

                $mutex = new Mutex("schoolAdminSaveStudentParent");
                while(!$mutex->getLock()){
                    sleep(.5);
                }

                $studentParent = $this->schoolParentModel->getStudentParentByStudentId($form['student_id'], $form['relation_id']);
                if($studentParent && $id != $studentParent['id'] && $form['relation_id'] != PARENT_TYPE_OTHER) {
                    $errors['relation_id'] = "相同家长（{$studentParent['parent_name']}，{$studentParent['parent_phone']}）称谓学生关联已经存在";
                    $this->view->assign('errors', $errors);
                    $this->view->assign('form', $form);
                } else {

                    $rs = $this->schoolParentModel->saveStudentParent($form);

                    $mutex->releaseLock();

                    if($rs) {

                        //存学生家长绑定短信
                        $student = $this->studentModel->getStudent($form['school_id'], $form['student_id']);
                        $smsTemplate = $this->code('sms.template.parentAdd');
                        $groupAddSmsUrl = WX_APP_URL;
                        $sms['message'] = str_replace(array('$studentName'), array($student['name']), $smsTemplate);
                        $sms['phone'] = $parent['phone'];

                        $this->smsModel->saveSms($sms);

                        $this->redirect("?c=parent&a=inputStudent&pc=parent&pa=index&parentId={$parentId}&id={$rs}", true, '保存成功');
                        exit;
                    }
                    
                    $this->redirect("?c=parent&a=inputStudent&pc=parent&pa=index&parentId={$parentId}&id={$id}", true, '保存失败');
                    exit;
                }
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            $form = $this->schoolParentModel->getStudentParentById($user['school_id'], $id);
            if($id && empty($form)) {
                 $this->redirect("?c=parent&a=input&pc=parent&pa=index&id={$parentId}", true, $this->lang('ID不存在'));
                 exit();
            }
            $this->view->assign('form', $form);
        }

        $classList = $this->classModel->getClassOptionList($user['school_id']);
        $this->view->assign('classList', $classList);

        $studentList = array();
        if($form['class_id']) {
            $studentList = $this->studentModel->getStudentOptionList($user['school_id'], $form['class_id']);
        }
        $this->view->assign('studentList', $studentList);

        $relationList = $this->relationModel->getRelationOptionList();
        $this->view->assign('relationList', $relationList);

        $this->view->layout();
    }

    function deleteStudentAction() {
        $user = $this->getSession(SESSION_USER);
        $id = $this->get('id');
        $studentParent = $this->schoolParentModel->getStudentParentById($user['school_id'], $id);
        $this->schoolParentModel->deleteStudentParent($user['school_id'], $id);
        $this->redirect("?c=parent&a=input&id={$studentParent['parent_id']}&tab=student-tab", true, '删除成功');
    }



    /*增加家长接送一览*/
    function pickUpAction(){
        $user = $this->getSession(SESSION_USER);
        $sh = $this->get('sh');
        if($sh['date_time']){
            if($sh['date_time']){
                $sh['date_time1'] = date("Y-m-d",strtotime($sh['date_time'])+86400);
            }   
            $this->view->assign('sh', $sh);
            $pickUpPic_list = $this->parentModel->getPickUpPicList($sh);
            $this->view->assign('paging',$this->parentModel->paging);
            $this->view->assign('pickUpPic_list',$pickUpPic_list);     
        }
        $this->view->layout();
        
    }
}

?>
