<?php

/**
 * @desc 家庭成员管理
 * @author
 */
class GroupController extends AppController {

    var $relationModel = null;
    var $schoolParentModel = null;
    var $studentModel = null;
    var $smsModel = null;

    function GroupController() {
        $this->AppController();
        $this->relationModel = $this->getModel('Relation');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->studentModel = $this->getModel('Student');
        $this->smsModel = $this->getModel('Sms');
    }
    /**
     * @desc 家庭成员列表页
     * @author
     */
    function indexAction() {
        $wxUser = $this->getAccessWxUser();
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $student_list = $this->studentModel->getStudentListByParentPhone($user['phone']);
       
        $this->view->assign('student_list', $student_list);
        $studentId = $this->get('id') ? $this->get('id') : $student_list[0]['id'];
        $this->view->assign('studentId', $studentId);
        $phone = $user['phone'];
        
        $signPackage = $this->getWeichatSignPackage();
        $this->view->assign('signPackage', $signPackage);

        $login_parent_info = $this->schoolParentModel->getParentByPhone($phone);
        $this->view->assign('login_parent_info', $login_parent_info);
        
        $parentList = $this->schoolParentModel->getStudentParentListByStudentId($studentId);
        
        $this->view->assign('parentList', $parentList);
        $this->view->layout();
    }

    function inputAction() {

        $user = $this->getSession(SESSION_USER);

        $studentId = $this->get('id');
        $parent_id = $this->get('parent_id');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {
            $relationList = $this->relationModel->getRelationOptionList();
            $this->view->assign('relationList', $relationList);

            if ($this->isComplete()) {
                $form = $this->post('form');
                $form['student_id'] = $studentId;

                if ($this->schoolParentModel->validSaveParent($form, $errors)) {

                    include(LIBRARY . 'mutex.php');

                    $mutex = new Mutex("saveStudentParent");
                    while(!$mutex->getLock()){
                        sleep(.5);
                    }
                    if($form['parent_id']){
                        $studentParent = $this->schoolParentModel->getStudentParentByRelationId($studentId, $form['relation_id']);
                        if($studentParent && $form['relation_id'] != PARENT_TYPE_OTHER){
                            $return = array(
                                'code' => 1,
                                'message' => "相同关系成员（{$studentParent['relation_title']}，{$studentParent['parent_name']}，{$studentParent['parent_phone']}）已经存在",
                            );
                            
                        }else{
                            $parentId = $this->schoolParentModel->saveParent(array(
                                'school_id' => $student['school_id'],
                                'name' => $form['name'],
                                'phone' => $form['phone'],
                                'status' => true,
                                'id' => $form['parent_id'],
                            ));
                            $form['parentId'] = $parentId;
                            $form['school_id'] = $student['school_id'];
                            $form['class_id'] = $student['class_id'];
                            $studentParentId = $this->schoolParentModel->saveStudentParent($form);
                            
                            $return = array(
                                'code' => 0,
                            );
                        }
                        
                    } else if($studentParent = $this->schoolParentModel->getStudentParentByPhone($studentId, $form['phone'])) { 
                    
                        $return = array(
                            'code' => 1,
                            'message' => "相同电话号码成员（{$studentParent['parent_name']}，{$studentParent['parent_phone']}）已经存在",
                        );
                    } else if($studentParent = $this->schoolParentModel->getStudentParentByRelationId($studentId, $form['relation_id']) && $form['relation_id'] != PARENT_TYPE_OTHER) {
                        
                        $return = array(
                            'code' => 1,
                            'message' => "相同关系成员（{$studentParent['relation_title']}，{$studentParent['parent_name']}，{$studentParent['parent_phone']}）已经存在",
                        );
                    } else {

                        $parent = $this->schoolParentModel->getParentByPhone($form['phone']);
                       
                        if($parent) {
                            $form['parent_id'] = $parent['id'];
                        } else {
                            $parentId = $this->schoolParentModel->saveParent(array(
                                'school_id' => $student['school_id'],
                                'name' => $form['name'],
                                'phone' => $form['phone'],
                                'status' => true,
                            ));
                            $form['parent_id'] = $parentId;
                        }
                        
                        $form['school_id'] = $student['school_id'];
                        $form['class_id'] = $student['class_id'];
                        
                        $studentParentId = $this->schoolParentModel->saveStudentParent($form);
                        
                        //存推广短信
                        $smsTemplate = $this->code('sms.template.groupAdd');
                        $groupAddSmsUrl = WX_APP_URL;
                        $sms['message'] = str_replace(array('$studentName', '$parentName'), array($student['name'], $user['name']), $smsTemplate);
                        $sms['phone'] = $form['phone'];

                        $this->smsModel->saveSms($sms);

                        $mutex->releaseLock();

                        if($studentParentId) {
                            $return = array(
                                'code' => 0,
                            );
                        } else {
                            $return = array(
                                'code' => 1,
                                'message' => '系统忙，请稍后再试',
                            );
                        }
                    }
                } else {
                    $return = array(
                        'code' => 1,
                        'message' => implode(';', $errors),
                    );
                }

                print json_encode($return);
                exit();
            }
             else{
                $parent = $this->schoolParentModel->getParentStudent($parent_id, $studentId);
                
            }
            
            $this->view->assign('parent', $parent);
            $this->view->assign('student', $student);
            
            $this->view->layout();

        } else {
            $this->redirect('?c=user');
            exit();
        }
    }

    function deleteAction() {
        $user = $this->getSession(SESSION_USER);
        
        $id = $this->get('id');
        $studentId = $this->get('studentId');
        
        if($parent = $this->schoolParentModel->getParentByParentPhone($user['phone'], $id)) {

            $schoolParent = $this->schoolParentModel->getParentBySchoolAndPhone($parent['school_id'], $user['phone']);

            if($schoolParent) {
                $this->schoolParentModel->deleteStudentParentByParentId($id, $studentId);

                $return = array(
                    'code' => 0,
                );
            } else {
                $return = array(
                    'code' => 1,
                    'message' => '主账号权限错误，请稍后再试',
                );
            }
        } else {
            $return = array(
                'code' => 1,
                'message' => '系统错误，请稍后再试',
            );
        }

        print json_encode($return);
        exit();
    } 
}
?>
