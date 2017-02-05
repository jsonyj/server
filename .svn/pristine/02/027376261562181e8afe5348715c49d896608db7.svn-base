<?php

class IndexController extends AppController {

    var $parentModel = null;
    var $schoolParentModel = null;
    var $captchaModel = null;
    var $smsModel = null;
    var $weichatBindModel = null;
    var $staffModel = null;
    var $studentModel = null;

    function IndexController() {
        $this->AppController();
        $this->parentModel = $this->getModel('Parent');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->captchaModel = $this->getModel('Captcha');
        $this->smsModel = $this->getModel('Sms');
        $this->weichatBindModel = $this->getModel('WeichatBind');
        $this->staffModel = $this->getModel('Staff');
        $this->studentModel = $this->getModel('Student');
    }

    function indexAction() {
        session_unset();
        session_destroy();
        $this->view->layout();
    }

    /**
     * @desc 宝贝中心
     * @author lxs
     */
    function bindAction() {
        header("Cache-Control:no-cache,must-revalidate,no-store");
        header("Pragma:no-cache");
        header("Expires:-1");

        $wxUser = $this->getAccessWxUser();
        
        if (!$role_list = $this->weichatBindModel->getWeichatBindList($wxUser['id'])) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        else {
            $bind_list = array();
            //剔除已删除的角色绑定
            foreach($role_list as $bind_info) {
                switch($bind_info['usage_type']) {
                    case ACT_SCHOOL_HEADMASTER:
                        //no break
                    case ACT_SCHOOL_TEACHER:
                        //no break
                    case ACT_SCHOOL_DOCTOR:
                        //no break
                    case ACT_SCHOOL_SUPPORTER:
                        if ($staff = $this->staffModel->getStaffByPhone($bind_info['phone'], $bind_info['usage_type'])) {
                            $bind_list[] = $bind_info;
                        }
                        break;
                        
                    case ACT_PARENT_ROLE:
                        if ($parent = $this->parentModel->getParentByPhone($bind_info['phone'])) {
                            $bind_list[] = $bind_info;
                        }
                        break;
                    
                    default:
                        break;
                }
            }
            
            $role_count = count($bind_list);
            
            if ($role_count == 0) {
                $this->redirect('?c=bind&a=index');
            }
            else if ($role_count == 1) {
                $usage_type = $bind_list[0]['usage_type'];
                $phone = $bind_list[0]['phone'];
                
                switch($usage_type) {
                    case ACT_PARENT_ROLE:
                        if ($parent = $this->parentModel->getParentByPhone($phone)) {
                            $this->setSession(SESSION_USER, $parent);
                            $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
                            $this->redirect('?c=parent&a=index');
                        } else {
                            $this->redirect('?c=bind&a=index');
                        }
                        break;
                        
                    case ACT_SCHOOL_HEADMASTER:
                        if ($staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_HEADMASTER)) {
                            $this->setSession(SESSION_USER, $staff);
                            $this->setSession(SESSION_ROLE, ACT_SCHOOL_HEADMASTER);
                            $this->redirect('?c=school&a=schoolStatistic');
                        } else {
                            $this->redirect('?c=bind&a=index');
                        }
                        break;
                        
                    case ACT_SCHOOL_TEACHER:
                        if ($staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_TEACHER)) {
                            $this->setSession(SESSION_USER, $staff);
                            $this->setSession(SESSION_ROLE, ACT_SCHOOL_TEACHER);
                            $this->redirect('?c=school&a=classStatistic');
                        } else {
                            $this->redirect('?c=bind&a=index');
                        }
                        break;
                        
                    case ACT_SCHOOL_DOCTOR:
                        if ($staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_DOCTOR)) {
                            $this->setSession(SESSION_USER, $staff);
                            $this->setSession(SESSION_ROLE, ACT_SCHOOL_DOCTOR);
                            $this->redirect('?c=school&a=schoolTemperatureStatistic');
                        } else {
                            $this->redirect('?c=bind&a=index');
                        }
                        break;
                        
                    case ACT_SCHOOL_SUPPORTER:
                        if ($staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_SUPPORTER)) {
                            $this->setSession(SESSION_USER, $staff);
                            $this->setSession(SESSION_ROLE, ACT_SCHOOL_SUPPORTER);
                            $this->redirect('?c=school&a=studentStatistic');
                        } else {
                            $this->redirect('?c=bind&a=index');
                        }
                        break;
                        
                    default:
                        $this->redirect('?c=bind&a=index');
                        break;
                }
            }
            else {
                $this->redirect('?c=bind&a=chooseRole');
            }
        }
        
    }

    /**
     * @desc 用户二维码界面（公众号菜单）
     * @author lxs
     */
    function qrcodeAction() {
        header("Cache-Control:no-cache,must-revalidate,no-store");
        header("Pragma:no-cache");
        header("Expires:-1");
        
        $wxUser = $this->getAccessWxUser();
        
        $bind_list = array();
        if ($weichatBindList = $this->weichatBindModel->getWeichatBindList($wxUser['id'])) {
            foreach($weichatBindList as $bind_info) {
                switch($bind_info['usage_type']) {
                    case ACT_SCHOOL_HEADMASTER:
                        //no break
                    case ACT_SCHOOL_TEACHER:
                        //no break
                    case ACT_SCHOOL_DOCTOR:
                        //no break
                    case ACT_SCHOOL_SUPPORTER:
                        if ($staff = $this->staffModel->getStaffByPhone($bind_info['phone'], $bind_info['usage_type'])) {
                            $staff['role_type'] = $bind_info['usage_type'];
                            $staff['role_name'] = $this->code('role_detail.' . $bind_info['usage_type'] . '.name');
                            $bind_list[] = $staff;
                        }
                        break;
                        
                    case ACT_PARENT_ROLE:
                        $student_list = $this->studentModel->getStudentListByParentPhone($bind_info['phone']);
                        
                        foreach ($student_list as $student) {
                            $student['role_name'] = '学生：' . $student['name'];
                            $student['role_type'] = ACT_PARENT_ROLE;
                            $bind_list[] = $student;
                        }
                        
                        break;
                    
                    default:
                        break;
                }
            }
        }
        
        $this->view->assign('bind_list', $bind_list);
        $this->view->layout();
    }
    
    function messageAction() {
        $message = $this->getSession(SESSION_MESSAGE);
        $this->view->assign('message', $message);
        $this->unsetSession(SESSION_MESSAGE);
        $this->view->layout();
    }

}

?>
