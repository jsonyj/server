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
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        $parentList = $this->schoolParentModel->getParentList($sh);
        $this->view->assign('paging', $this->schoolParentModel->paging);

        foreach($parentList as $k => $v) {
            $v['studentList'] = $this->studentModel->getStudentListByParentId($v['school_id'], $v['id']);
            $parentList[$k] = $v;
        }

        $this->view->assign('parentList', $parentList);

        $this->view->layout();
    }

    function inputAction() {

        $id = $this->get('id');
        $form = $this->post('form');
        
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->schoolParentModel->validSaveParent($form, $errors)) {
                if ($rs = $this->schoolParentModel->saveParent($form)) {
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

        $schoolList = $this->schoolModel->getSchoolOptionList();
        $this->view->assign('schoolList', $schoolList);

        $parentStudentList = $this->schoolParentModel->getParentStudentList($id);
        $this->view->assign('parentStudentList', $parentStudentList);

        $relationList = $this->relationModel->getRelationOptionList();
        $this->view->assign('relationList', $relationList);

        $this->view->layout();
    }

    function deleteAction() {
        $id = $this->get('id');
        
        if ($schoolParent = $this->schoolParentModel->getParent($id)) {
            $phone = $schoolParent['phone'];
            $this->parentModel->deleteParentByPhone($phone);
        }
        
        $this->schoolParentModel->deleteParent($id);
        $this->schoolParentModel->deleteStudentParentByParentId($id);
        $this->redirect("?c=parent&a=index", true, '删除成功');
    }

    function inputStudentAction() {

        $id = $this->get('id');
        $parentId = $this->get('parentId');
        $form = $this->post('form');

        $parent = $this->schoolParentModel->getParent($parentId);
        $this->view->assign('parent', $parent);

        if ($this->isComplete()) {
            $form['id'] = $id;
            $form['parent_id'] = $parentId;
            $form['school_id'] = $parent['school_id'];
            if ($this->schoolParentModel->validSaveStudentParent($form, $errors)) {

                include(LIBRARY . 'mutex.php');

                $mutex = new Mutex("adminSaveStudentParent");
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
                        $student = $this->studentModel->getStudent($form['student_id']);
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
            $form = $this->schoolParentModel->getStudentParentById($id);
            if($id && empty($form)) {
                 $this->redirect("?c=parent&a=input&pc=parent&pa=index&id={$parentId}", true, $this->lang('ID不存在'));
                 exit();
            }
            $this->view->assign('form', $form);
        }

        $classList = $this->classModel->getClassOptionList($parent['school_id']);
        $this->view->assign('classList', $classList);

        $studentList = array();
        if($form['class_id']) {
            $studentList = $this->studentModel->getStudentOptionList($form['class_id']);
        }
        $this->view->assign('studentList', $studentList);

        $relationList = $this->relationModel->getRelationOptionList();
        $this->view->assign('relationList', $relationList);

        $this->view->layout();
    }

    function deleteStudentAction() {
        $id = $this->get('id');
        $studentParent = $this->schoolParentModel->getStudentParentById($id);
        $this->schoolParentModel->deleteStudentParent($id);
        $this->redirect("?c=parent&a=input&id={$studentParent['parent_id']}&tab=student-tab", true, '删除成功');
    }
}

?>
