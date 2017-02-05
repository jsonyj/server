<?php

class BindController extends AppController {

    var $weichatBindModel = null;
    var $captchaModel = null;
    var $smsModel = null;
    var $studentModel = null;
    var $parentModel = null;
    var $schoolParentModel = null;
    var $weichatPushMessageModel = null;
    var $staffModel = null;

    function BindController() {
        $this->AppController();
        $this->weichatBindModel = $this->getModel('WeichatBind');
        $this->captchaModel = $this->getModel('Captcha');
        $this->smsModel = $this->getModel('Sms');
        $this->studentModel = $this->getModel('Student');
        $this->parentModel = $this->getModel('Parent');
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
        $this->staffModel = $this->getModel('Staff');
    }

    /**
     * @desc 绑定界面首页
     * @pageauthor ly
     * @funcauthor lxs
     */
    function indexAction() {
        $wxUser = $this->getAccessWxUser();
        
        if ($this->isComplete()) {
            $form = $this->post('form');
            $return = array();

            if ($this->weichatBindModel->validBind($form, $errors)) {
                
                $phone = trim($form['phone']);
                
                $captcha = $this->captchaModel->getCaptcha($phone, $form['captcha']);
                if($captcha) {
                    
                    switch ($form['role']) {
                        case ACT_SCHOOL_GENERAL: //职工绑定
                            $return = $this->bindStaff($form, $wxUser);
                            break;
                            
                        case ACT_PARENT_ROLE: //家长绑定
                            $return = $this->bindParent($form, $wxUser);
                            break;
                            
                        default:
                            $return = array(
                                'code' => 1,
                                'message' => '绑定角色错误',
                            );
                            break;
                    }
                    
                    //删除已使用验证码
                    $this->captchaModel->deleteCaptcha($captcha['id']);
                } 
                else {
                    $return = array(
                        'code' => 1,
                        'message' => '验证码不正确，请重新输入',
                    );
                }
                
            } 
            else {
                $return = array(
                    'code' => 1,
                    'message' => implode(';', $errors),
                );
            }

            print json_encode($return);
            exit();
        }
        else {
            
            $this->unsetSession(BIND_PHONE);
            $this->unsetSession(ADD_BIRTHDAY);
            $this->unsetSession(STUDENT_BIRTHDAY);
            $this->unsetSession(RELATION_TYPE);
            $this->unsetSession(RELATION_TITLE);
            $this->unsetSession(STUDENT_IDS);
            
            if ($role = (int) $this->get('role')) {
                $this->view->assign('role_defalut', $role);
                
                //获取该微信号已经绑定的家长
                if ($bind_parent = $this->weichatBindModel->getWeichatBindParent($wxUser['id'])) {
                    $this->setSession(BIND_PHONE, trim($bind_parent['phone']));
                    $this->view->assign('parent_is_bind', 1);
                }
                
            }
            else {
                
                //获取该微信号已经绑定的家长
                if ($bind_parent = $this->weichatBindModel->getWeichatBindParent($wxUser['id'])) {
                    $this->setSession(BIND_PHONE, trim($bind_parent['phone']));
                    $this->redirect('?c=bind&a=bindStudent');
                    exit();
                }
                
                $this->view->assign('role_defalut', ACT_PARENT_ROLE);
            }
        }
        
        $this->view->layout();
    }
    
    /**
     * @desc 选择绑定职工时的处理
     * @author lxs
     */
    function bindStaff($form, $wxUser) {
        $phone = trim($form['phone']);
        
        //获取职工信息
        $staff = $this->staffModel->getStaffByPhone($phone);
        if($staff) {
            $staff_type = $staff['type'];
            
            //查询职工是否已经绑定
            if ($bind_staff = $this->weichatBindModel->getBindInfoByPhone($phone, $staff_type)) {
                $this->weichatBindModel->updateBindWeichatID($bind_staff['id'], $wxUser['id']);
            }
            else {
                $bind_staff = array(
                    'weichat_id' => $wxUser['id'],
                    'phone' => $phone,
                    'usage_type' => $staff_type,
                );
                $this->weichatBindModel->saveBind($bind_staff);
            }
            
            $this->setSession(SESSION_USER, $staff);
            $this->setSession(SESSION_ROLE, $staff_type);
            
            //确定跳转的url
            switch($staff_type) {
                case ACT_SCHOOL_HEADMASTER:
                    $url = "?c=school&a=schoolStatistic";
                    break;
                case ACT_SCHOOL_TEACHER:
                    $url = "?c=school&a=classStatistic";
                    break;
                case ACT_SCHOOL_DOCTOR:
                    $url = "?c=school&a=schoolTemperatureStatistic";
                    break;
                case ACT_SCHOOL_SUPPORTER:
                    $url = "?c=school&a=studentStatistic";
                    break;
                default:
                    $url = "?c=index&a=bind";
                    break;
            }
            
            $this->setSession(SESSION_MESSAGE, array(
                'type' => 'success',
                'body' => '绑定成功，请点击进入宝贝中心',
                'url' => $url,
            ));
            
            $return = array(
                'code' => 0,
            );
        } 
        else {
            $return = array(
                'code' => 1,
                'message' => '职工手机号不存在，请联系统管理员',
            );
        }
        
        return $return;
    }
    
    /**
     * @desc 选择绑定家长时的处理
     * @author lxs
     */
    function bindParent($form, $wxUser) {
        $phone = trim($form['phone']);
        
        //绑定微信用户信息与账户信息
        if($parent = $this->parentModel->getParentByPhone($phone)) {
            //微信绑定tb_parent
            $this->parentModel->updateParentByWeichatId($parent['id'], $wxUser['id']);
            
            //微信绑定tb_weichat_bind
            if ($bind_parent = $this->weichatBindModel->getBindInfoByPhone($phone, ACT_PARENT_ROLE)) {
                $this->weichatBindModel->updateBindWeichatID($bind_parent['id'], $wxUser['id']);
            }
            else {
                $bind_parent = array(
                    'weichat_id' => $wxUser['id'],
                    'phone' => $phone,
                    'usage_type' => ACT_PARENT_ROLE,
                );
                $this->weichatBindModel->saveBind($bind_parent);
            }

            $this->setSession(SESSION_USER, $parent);
            $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
            $this->setSession(SESSION_MESSAGE, array(
                'type' => 'success',
                'body' => '绑定成功，请点击进入宝贝中心',
                'url' => '?c=user',
            ));

            $return = array(
                'code' => 0,
            );
        }
        else if($schoolParent = $this->schoolParentModel->getParentByPhone($phone)) {

            $parent_data = array(
                'weichat_id' => $wxUser['id'],
                'name' => $schoolParent['name'],
                'phone' => $schoolParent['phone'],
                'status' => 1,
            );
            $this->parentModel->saveParent($parent_data);
            
            //微信绑定tb_weichat_bind
            if ($bind_parent = $this->weichatBindModel->getBindInfoByPhone($phone, ACT_PARENT_ROLE)) {
                $this->weichatBindModel->updateBindWeichatID($bind_parent['id'], $wxUser['id']);
            }
            else {
                $bind_parent = array(
                    'weichat_id' => $wxUser['id'],
                    'phone' => $phone,
                    'usage_type' => ACT_PARENT_ROLE,
                );
                $this->weichatBindModel->saveBind($bind_parent);
            }
            
            $parent = $this->parentModel->getParentByPhone($phone);
            $this->setSession(SESSION_USER, $parent);
            $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
            $this->setSession(SESSION_MESSAGE, array(
                'type' => 'success',
                'body' => '绑定成功，请点击进入宝贝中心',
                'url' => '?c=user',
            ));

            $return = array(
                'code' => 0,
            );
        } 
        else {
            $this->setSession(BIND_PHONE, $phone);
            $return = array(
                'code' => 301,
            );
        }
        
        return $return;
    }

    /**
     * @desc 输入需要绑定的学生信息
     * @author lxs
     */
    function bindStudentAction() {
        $wxUser = $this->getAccessWxUser();
        
        $form = $this->post('form');
        
        //获取手机号
        if ($phone = $this->getSession(BIND_PHONE)) {
            $form['phone'] = $phone;
        }
        
        if ($this->isComplete()) {
            $return = array();

            if ($this->weichatBindModel->validBindStudent($form, $errors)) {
                $name = trim($form['name']);
                $birthday = date('Y-m-d', strtotime(trim($form['birthday'])));
                if ($form['relation_type'] != PARENT_TYPE_OTHER) {
                    $form['relation_title'] = $this->code('relation.'.$form['relation_type'].'.name');
                }
                
                //1.检查该学生姓名是否存在
                if (!$student_list = $this->studentModel->getStudentListByName($name)) {
                    $return = array(
                        'code' => 1,
                        'message' => '该学生不存在，请检查并重新输入',
                    );
                }
                
                $student_count = count($student_list);
                
                //2、该姓名只有一个学生
                if ($student_count == 1) {
                    
                    $add_birthday = 0;
                    $add_school_parent = 0;
                    $add_parent = 0;
                    
                    $student_info = $student_list[0];
                    $student_birthday = $student_info['birthday'];
                    $student_id = $student_info['id'];
                    
                    //2.1、检查该名学生生日是否录入了系统
                    if ($student_birthday && ($student_birthday != "0000-00-00")) {
                        if ($student_birthday != $birthday) {
                            $return = array(
                                'code' => 1,
                                'message' => '学生生日不正确，请检查并重新输入',
                            );
                            print json_encode($return);
                            exit();
                        }
                    }
                    else {
                        $add_birthday = 1;
                    }
                    
                    //2.2、检查该名学生是否已有其他手机号家长
                    if ($parent_list = $this->schoolParentModel->getStudentParentListByStudentId($student_id, $phone)) {
                        
                        //2.3检查学生已有家长是否绑定了微信
                        $parent_bind_flag = 0;
                        $weichat_arr = array();
                        foreach($parent_list as $parent_info) {
                            
                            if ($bind_parent = $this->weichatBindModel->getBindWxInfoByPhone($parent_info['phone'], ACT_PARENT_ROLE)) {
                                
                                if ($bind_parent['weichat_id'] != $wxUser['id']) {
                                    $parent_bind_flag = 1;
                                    $weichat_arr[] = array(
                                        'weichat_id' => $bind_parent['weichat_id'],
                                        'openid' => $bind_parent['openid'],
                                        'relation_title' => $parent_info['relation_title'],
                                    );
                                }
                            }
                        }
                        
                        if ($parent_bind_flag) {
                            //2.4.1有微信绑定家长，等待其他家长微信验证
                            //生成绑定key
                            $BraveCrypt = $this->load(EXTEND, 'BraveCrypt');
                            $BraveCrypt->init();
                            
                            $bind_key = $phone . '#' . $student_id . '#' . $form['relation_type'] . '#' . $form['relation_title'] . '#' . $wxUser['id']; 
                            $bind_key = $BraveCrypt->encrypt($bind_key, AES_BIND_KEY, AES_BIND_IV);
                            
                            //生成绑定申请记录
                            $apply_data = array(
                                'weichat_id' => $wxUser['id'],
                                'student_id' => $student_id,
                                'phone' => $phone,
                                'relation_id' => trim($form['relation_type']),
                                'relation_title' => trim($form['relation_title']),
                                'bind_key' => $bind_key,
                                'status' => BIND_PARENT_APPLY_UNDO,
                            );
                            $apply_id = $this->weichatBindModel->saveBindParentApply($apply_data);
                            
                            $relation_title_arr = array();
                            foreach($weichat_arr as $weichat_info) {
                                $wx_data = array(
                                    'open_id' => $weichat_info['openid'],
                                    'phone' => $phone,
                                    'student_name' => $student_info['name'],
                                    'student_id' => $student_id,
                                    'relation_type' => trim($form['relation_type']),
                                    'relation_title' => trim($form['relation_title']),
                                    'send_time' => NOW,
                                    'apply_id' => $apply_id,
                                    'bind_key' => urlencode($bind_key),
                                );
                                
                                $wx_data['message'] = $this->weichatPushMessageModel->genValidBindApplyMessage($wx_data);
                                $this->weichatPushMessageModel->saveMessage($wx_data);
                                
                                $relation_title_arr[] = $weichat_info['relation_title'];
                            }
                            
                            //生成消息提示
                            $relation_title_str = '';
                            if (count($relation_title_arr) <= 2) {
                                $relation_title_str = implode(',', $relation_title_arr);
                            }
                            else {
                                $relation_title_str = $relation_title_arr[0] . ',' . $relation_title_arr[1]. '等等';
                            }
                            
                            $this->setSession(SESSION_MESSAGE, array(
                                'type' => 'false',
                                'body' => "您的绑定申请已经发给{$student_info['name']}小朋友的{$relation_title_str}，申请通过后，将完成绑定。",
                                'url' => '?c=index',
                            ));
                            
                            $return = array(
                                'code' => 0,
                                'message' => implode(';', $errors),
                            );
                        }
                        else {
                            //2.4.2无微信绑定家长，跳转到家长手机号验证界面
                            
                            $this->setSession(ADD_BIRTHDAY, $add_birthday);
                            $this->setSession(STUDENT_BIRTHDAY, $birthday);
                            $this->setSession(RELATION_TYPE, trim($form['relation_type']));
                            $this->setSession(RELATION_TITLE, trim($form['relation_title']));
                            
                            $parent_count = count($parent_list);
                            $rand_num = mt_rand(0, $parent_count-1);
                            $school_parent_id = $parent_list[$rand_num]['parent_id'];
                            $return = array(
                                'code' => 302,
                                'sid' => $student_id,
                                'pid' => $school_parent_id,
                            );
                            print json_encode($return);
                            exit();
                        }
                    }
                    else {
                        //无家长，进行绑定操作
                        $form['student_id'] = $student_id;
                        $form['school_id'] = $student_info['school_id'];
                        $form['class_id'] = $student_info['class_id'];
                        $form['weichat_id'] = $wxUser['id'];
                        
                        $return = $this->weichatBindModel->bindStudentParent($form, $add_birthday);
                        
                        if ($return['code'] == 0) {
                            $parent = $this->parentModel->getParentByPhone($form['phone']);
                            $this->setSession(SESSION_USER, $parent);
                            $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
                            
                            //保存向其他家长推送绑定成功的微信推送消息
                            if($openid_list = $this->weichatBindModel->getParentOpenidList($student_id, $wxUser['id'])) {
                                foreach($openid_list as $openid_info) {
                                    $wx_data = array(
                                        'open_id' => $openid_info['openid'],
                                        'phone' => $phone,
                                        'student_name' => $student_info['name'],
                                        'student_id' => $student_id,
                                        'send_time' => NOW,
                                    );
                                    
                                    $wx_data['message'] = $this->weichatPushMessageModel->genBindSuccessMessage($wx_data);
                                    $this->weichatPushMessageModel->saveMessage($wx_data);
                                }
                            }

                            $this->setSession(SESSION_MESSAGE, array(
                                'type' => 'success',
                                'body' => '绑定成功，请点击进入宝贝中心',
                                'url' => '?c=user',
                            ));
                        }
                        
                    }
                    
                }
                else {
                    //3、该姓名有多个学生
                    $student_ids = array();
                    foreach($student_list as $student_info) {
                        $student_birthday = $student_info['birthday'];
                        
                        if ((!$student_birthday) || ($student_birthday == "0000-00-00")) {
                            $student_ids[] = $student_info['id'];
                        }
                        else if ($student_birthday == $birthday) {
                            $student_ids[] = $student_info['id'];
                        }
                    }
                    
                    if ($student_ids) {
                        $this->setSession(STUDENT_IDS, $student_ids);
                        $this->setSession(STUDENT_BIRTHDAY, $birthday);
                        $this->setSession(RELATION_TYPE, trim($form['relation_type']));
                        $this->setSession(RELATION_TITLE, trim($form['relation_title']));
                        $return = array(
                            'code' => 303,
                        );
                    }
                    else {
                        $return = array(
                            'code' => 1,
                            'message' => '绑定失败，请确定你家小朋友的姓名和生日是否正确',
                        );
                    }
                    
                }
            }
            else {
                $return = array(
                    'code' => 1,
                    'message' => implode(';', $errors),
                );
            }

            print json_encode($return);
            exit();
        }
        
        $this->view->layout();
    }
    
    /**
     * @desc 验证家长的手机号码
     * @author ly
     */
    function validParentPhoneAction(){
        $wxUser = $this->getAccessWxUser();
        
        $parent_id = $this->get('pid');
        $student_id = $this->get('sid');
        
        //获取手机号
        $form = $this->post('form');
        if ($phone = $this->getSession(BIND_PHONE)) {
            $form['phone'] = $phone;
        }
        if ($relation_type = $this->getSession(RELATION_TYPE)) {
            $form['relation_type'] = $relation_type;
        }
        if ($relation_title = $this->getSession(RELATION_TITLE)) {
            $form['relation_title'] = $relation_title;
        }
        if ($add_birthday = $this->getSession(ADD_BIRTHDAY)) {
            $form['add_birthday'] = $add_birthday;
        }
        if ($birthday = $this->getSession(STUDENT_BIRTHDAY)) {
            $form['birthday'] = $birthday;
        }
        
        if ($this->isComplete()) {
            $return = array();
            
            if (!$parent_info = $this->schoolParentModel->getParent($parent_id)) {
                $return = array(
                    'code' => 1,
                    'message' => '参数错误',
                );
                print json_encode($return);
                exit();
            }
            else if (!$student_info = $this->studentModel->getStudent($student_id)) {
                $return = array(
                    'code' => 1,
                    'message' => '参数错误',
                );
                print json_encode($return);
                exit();
            }

            if ($this->weichatBindModel->validParentPhone($form, $errors)) {
                //验证手机号
                $parent_phone = $parent_info['phone'];
                $phone_middle = $parent_phone[3].$parent_phone[4].$parent_phone[5].$parent_phone[6];
                $phone_middle_form = trim($form['phone_middle']);
                if ($phone_middle != $phone_middle_form) {
                    $return = array(
                        'code' => 1,
                        'message' => '手机号验证失败，请重新输入',
                    );
                    print json_encode($return);
                    exit();
                }
                
                $form['student_id'] = $student_id;
                $form['school_id'] = $student_info['school_id'];
                $form['class_id'] = $student_info['class_id'];
                $form['weichat_id'] = $wxUser['id'];
                
                $return = $this->weichatBindModel->bindStudentParent($form, $add_birthday);
                
                if ($return['code'] == 0) {
                    $parent = $this->parentModel->getParentByPhone($form['phone']);
                    $this->setSession(SESSION_USER, $parent);
                    $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
                    
                    //保存向其他家长推送绑定成功的微信推送消息
                    if($openid_list = $this->weichatBindModel->getParentOpenidList($student_id, $wxUser['id'])) {
                        foreach($openid_list as $openid_info) {
                            $wx_data = array(
                                'open_id' => $openid_info['openid'],
                                'phone' => $phone,
                                'student_name' => $student_info['name'],
                                'student_id' => $student_id,
                                'send_time' => NOW,
                            );
                            
                            $wx_data['message'] = $this->weichatPushMessageModel->genBindSuccessMessage($wx_data);
                            $this->weichatPushMessageModel->saveMessage($wx_data);
                        }
                    }

                    $this->setSession(SESSION_MESSAGE, array(
                        'type' => 'success',
                        'body' => '绑定成功，请点击进入宝贝中心',
                        'url' => '?c=user',
                    ));
                }
                
            }
            else {
                $return = array(
                    'code' => 1,
                    'message' => implode(';', $errors),
                );
            }

            print json_encode($return);
            exit();
        }
        else {
            if (!$parent_info = $this->schoolParentModel->getParent($parent_id)) {
                $this->redirect('?c=bind&a=index');
                exit();
            }
            else if (!$student_info = $this->studentModel->getStudent($student_id)) {
                $this->redirect('?c=bind&a=index');
                exit();
            }
            else {
                if (!$relation = $this->schoolParentModel->getParentRel($student_id, $parent_id)) {
                    $this->redirect('?c=bind&a=index');
                    exit();
                }
                
                $parent_phone = $parent_info['phone'];
                $phone_front = $parent_phone[0].$parent_phone[1].$parent_phone[2];
                $phone_end = $parent_phone[7].$parent_phone[8].$parent_phone[9].$parent_phone[10];
                
                $this->view->assign('student_name', $student_info['name']);
                $this->view->assign('phone_front', $phone_front);
                $this->view->assign('phone_end', $phone_end);
                $this->view->assign('relation_title', $relation['relation_title']);
            }
        }
        
        $this->view->layout();
    }

    /**
     * @desc 当有多个学生时选择需要绑定的学生
     * @pageauthor ly
     * @funcauthor lxs
     */
    function chooseStudentAction() {
        $wxUser = $this->getAccessWxUser();
        
        if (!$phone = $this->getSession(BIND_PHONE)) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        else if (!$relation_type = $this->getSession(RELATION_TYPE)) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        else if (!$relation_title = $this->getSession(RELATION_TITLE)) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        else if (!$birthday = $this->getSession(STUDENT_BIRTHDAY)) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        else if (!$student_ids = $this->getSession(STUDENT_IDS)) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        
        if (!$student_list = $this->studentModel->getStudentList(array('sids' => $student_ids))) {
            $this->redirect('?c=bind&a=index');
            exit();
        }
        
        $this->view->assign('student_name', $student_list[0]['name']);
        $this->view->assign('student_list', $student_list);
        $this->view->layout();
    }
    
    /**
     * @desc 当有多个学生时选择确认绑定的学生
     * @method post ajax
     * @funcauthor lxs
     */
    function confirmStudentAction() {
        if (!$wxUser = $this->getAccessWxUser()) {
            $return = array(
                'code' => 1,
                'message' => '请先授权'
            );
        }
        
        if (!$phone = $this->getSession(BIND_PHONE)) {
            $return = array(
                'code' => 1,
                'message' => '超过绑定时效，请返回第一步重新绑定'
            );
        }
        else if (!$relation_type = $this->getSession(RELATION_TYPE)) {
            $return = array(
                'code' => 1,
                'message' => '超过绑定时效，请返回第一步重新绑定'
            );
        }
        else if (!$relation_title = $this->getSession(RELATION_TITLE)) {
            $return = array(
                'code' => 1,
                'message' => '超过绑定时效，请返回第一步重新绑定'
            );
        }
        else if (!$birthday = $this->getSession(STUDENT_BIRTHDAY)) {
            $return = array(
                'code' => 1,
                'message' => '超过绑定时效，请返回第一步重新绑定'
            );
        }
        else if (!$student_ids = $this->getSession(STUDENT_IDS)) {
            $return = array(
                'code' => 1,
                'message' => '超过绑定时效，请返回第一步重新绑定'
            );
        }
        
        if (!$student_id = $this->post('sid')) {
            $return = array(
                'code' => 1,
                'message' => '请选择学生'
            );
        }
        
        if (!in_array($student_id, $student_ids)) {
            $return = array(
                'code' => 1,
                'message' => '选择的学生不正确'
            );
        }
        
        if ($return) {
            print json_encode($return);
            exit();
        }
        
        if (!$student_info = $this->studentModel->getStudent($student_id)) {
            $return = array(
                'code' => 1,
                'message' => '选择的学生不正确'
            );
            print json_encode($return);
            exit();
        }
        
        //1、检查该名学生生日是否录入了系统
        $add_birthday = 0;
        $student_birthday = $student_info['birthday'];
        if ((!$student_birthday) || ($student_birthday == "0000-00-00")) {
            $add_birthday = 1;
            $this->setSession(ADD_BIRTHDAY, $add_birthday);
        }
        
        //2、检查该名学生是否已有其他手机号家长
        if ($parent_list = $this->schoolParentModel->getStudentParentListByStudentId($student_id, $phone)) {
            
            //3、检查学生已有家长是否绑定了微信
            $parent_bind_flag = 0;
            $weichat_arr = array();
            foreach($parent_list as $parent_info) {
                
                if ($bind_parent = $this->weichatBindModel->getBindWxInfoByPhone($parent_info['phone'], ACT_PARENT_ROLE)) {
                    
                    if ($bind_parent['weichat_id'] != $wxUser['id']) {
                        $parent_bind_flag = 1;
                        $weichat_arr[] = array(
                            'weichat_id' => $bind_parent['weichat_id'],
                            'openid' => $bind_parent['openid'],
                            'relation_title' => $parent_info['relation_title'],
                        );
                    }
                }
            }
            
            if ($parent_bind_flag) {
                //3.1有微信绑定家长，等待其他家长微信验证
                //生成绑定key
                $BraveCrypt = $this->load(EXTEND, 'BraveCrypt');
                $BraveCrypt->init();
                
                $bind_key = $phone . '#' . $student_id . '#' . $relation_type . '#' . $relation_title . '#' . $wxUser['id']; 
                $bind_key = $BraveCrypt->encrypt($bind_key, AES_BIND_KEY, AES_BIND_IV);
                
                //生成绑定申请记录
                $apply_data = array(
                    'weichat_id' => $wxUser['id'],
                    'student_id' => $student_id,
                    'phone' => $phone,
                    'relation_id' => trim($relation_type),
                    'relation_title' => trim($relation_title),
                    'bind_key' => $bind_key,
                    'status' => BIND_PARENT_APPLY_UNDO,
                );
                $apply_id = $this->weichatBindModel->saveBindParentApply($apply_data);
                
                $relation_title_arr = array();
                foreach($weichat_arr as $weichat_info) {
                    $wx_data = array(
                        'open_id' => $weichat_info['openid'],
                        'phone' => $phone,
                        'student_name' => $student_info['name'],
                        'student_id' => $student_id,
                        'relation_type' => trim($relation_type),
                        'relation_title' => trim($relation_title),
                        'send_time' => NOW,
                        'apply_id' => $apply_id,
                        'bind_key' => urlencode($bind_key),
                    );
                    
                    $wx_data['message'] = $this->weichatPushMessageModel->genValidBindApplyMessage($wx_data);
                    $this->weichatPushMessageModel->saveMessage($wx_data);
                    
                    $relation_title_arr[] = $weichat_info['relation_title'];
                }
                
                //生成消息提示
                $relation_title_str = '';
                if (count($relation_title_arr) <= 2) {
                    $relation_title_str = implode(',', $relation_title_arr);
                }
                else {
                    $relation_title_str = $relation_title_arr[0] . ',' . $relation_title_arr[1]. '等等';
                }
                
                $this->setSession(SESSION_MESSAGE, array(
                    'type' => 'false',
                    'body' => "您的绑定申请已经发给{$student_info['name']}小朋友的{$relation_title_str}，申请通过后，将完成绑定。",
                    'url' => '?c=index',
                ));
                
                $return = array(
                    'code' => 0,
                    'message' => implode(';', $errors),
                );
            }
            else {
                //4、无微信绑定家长，跳转到家长手机号验证界面
                $parent_count = count($parent_list);
                $rand_num = mt_rand(0, $parent_count-1);
                $school_parent_id = $parent_list[$rand_num]['parent_id'];
                $return = array(
                    'code' => 302,
                    'sid' => $student_id,
                    'pid' => $school_parent_id,
                );
            }
            
            print json_encode($return);
            exit();
        }
        else {
            //无家长，进行绑定操作
            $form = array(
                'phone' => $phone,
                'relation_type' => $relation_type,
                'relation_title' => $relation_title,
                'birthday' => $birthday,
                'student_id' => $student_id,
                'school_id' => $student_info['school_id'],
                'class_id' => $student_info['class_id'],
                'weichat_id' => $wxUser['id'],
            );
            
            $return = $this->weichatBindModel->bindStudentParent($form, $add_birthday);
            
            if ($return['code'] == 0) {
                $parent = $this->parentModel->getParentByPhone($form['phone']);
                $this->setSession(SESSION_USER, $parent);
                $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
                
                //保存向其他家长推送绑定成功的微信推送消息
                if($openid_list = $this->weichatBindModel->getParentOpenidList($student_id, $wxUser['id'])) {
                    foreach($openid_list as $openid_info) {
                        $wx_data = array(
                            'open_id' => $openid_info['openid'],
                            'phone' => $phone,
                            'student_name' => $student_info['name'],
                            'student_id' => $student_id,
                            'send_time' => NOW,
                        );
                        
                        $wx_data['message'] = $this->weichatPushMessageModel->genBindSuccessMessage($wx_data);
                        $this->weichatPushMessageModel->saveMessage($wx_data);
                    }
                }

                $this->setSession(SESSION_MESSAGE, array(
                    'type' => 'success',
                    'body' => '绑定成功，请点击进入宝贝中心',
                    'url' => '?c=user',
                ));
            }
            
        }
        
    }
    
    /**
     * @desc 选择绑定的角色
     * @pageauthor ly
     * @funcauthor lxs
     */
    function chooseRoleAction() {
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
        }
        
        if ($bind_list) {
            $this->view->assign('weichatBindList', $bind_list);
            $this->view->layout();  
        } else {
            $this->redirect('?c=bind&a=index');
        }
        
    }
    
    /**
     * @desc 确认选择的角色
     * @method post ajax
     * @funcauthor lxs
     */
    function confirmRoleAction() {
        $wxUser = $this->getAccessWxUser();
        $usage_type = $this->post('usage_type');
        $phone = $this->post('phone');
        
        switch($usage_type) {
            case ACT_PARENT_ROLE:
                $parent = $this->parentModel->getParentByPhone($phone);
                $this->setSession(SESSION_USER, $parent);
                $this->setSession(SESSION_ROLE, ACT_PARENT_ROLE);
                $return = array(
                    'code' => 0,
                    'url' => '?c=parent&a=index',
                );
                break;
                
            case ACT_SCHOOL_HEADMASTER:
                $staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_HEADMASTER);
                $this->setSession(SESSION_USER, $staff);
                $this->setSession(SESSION_ROLE, ACT_SCHOOL_HEADMASTER);
                $return = array(
                    'code' => 0,
                    'url' => '?c=school&a=schoolStatistic',
                );
                break;
                
            case ACT_SCHOOL_TEACHER:
                $staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_TEACHER);
                $this->setSession(SESSION_USER, $staff);
                $this->setSession(SESSION_ROLE, ACT_SCHOOL_TEACHER);
                $return = array(
                    'code' => 0,
                    'url' => '?c=school&a=classStatistic',
                );
                break;
                
            case ACT_SCHOOL_DOCTOR:
                $staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_DOCTOR);
                $this->setSession(SESSION_USER, $staff);
                $this->setSession(SESSION_ROLE, ACT_SCHOOL_DOCTOR);
                $return = array(
                    'code' => 0,
                    'url' => '?c=school&a=schoolTemperatureStatistic',
                );
                break;
                
            case ACT_SCHOOL_SUPPORTER:
                $staff = $this->staffModel->getStaffByPhone($phone, ACT_SCHOOL_SUPPORTER);
                $this->setSession(SESSION_USER, $staff);
                $this->setSession(SESSION_ROLE, ACT_SCHOOL_SUPPORTER);
                $return = array(
                    'code' => 0,
                    'url' => '?c=school&a=studentStatistic',
                );
                break;
            
            default:
                $return = array(
                    'code' => 1,
                    'url' => '角色选择失败',
                );
                break;
        }
        
        print json_encode($return);
        exit();
    }

    /**
     * @desc 获取绑定验证码
     * @author lxs
     */
    function bindCaptchaAction() {

        $type = $this->post('type');
        $phone = $this->post('phone');

        $form = array(
            'phone' => $phone,
        );

        if ($this->captchaModel->validGetCaptcha($form, $errors)) {

            $form['captcha'] = $this->captchaModel->genCaptcha();
            $this->captchaModel->saveCaptcha($form);

            if($type == ACT_SCHOOL_GENERAL) {
                if (!$staff = $this->staffModel->getStaffByPhone($phone)) {
                    $return = array(
                        'code' => 1,
                        'message' => '职工手机号不存在，请联系统管理员',
                    );
                    print json_encode($return);
                    exit();
                }
                
                $message = $this->code('sms.template.staffBind');
            }
            else {
                $message = $this->code('sms.template.parentBind');
            }

            $form['message'] = str_replace(array('$captcha'), array($form['captcha']), $message);

            $this->smsModel->saveSms($form);

            $return = array(
                'code' => 0,
            );
        } else {
            $return = array(
                'code' => 1,
                'message' => implode(';', $errors),
            );
        }

        print json_encode($return);
        exit();
    }

    /**
     * @desc 申请绑定详情
     * @pageauthor ly
     */
    function bindDetailAction(){
        $wxUser = $this->getAccessWxUser();
        
        $id = $this->get('id');
        $bind_key = trim($this->get('key'));
        
        if (!$apply_info = $this->weichatBindModel->getBindParentApply($id)) {
            $this->redirect('?c=index');
            exit();
        }
        
        if ($apply_info['bind_key'] != $bind_key) {
            $this->redirect('?c=index');
            exit();
        }
        
        $BraveCrypt = $this->load(EXTEND, 'BraveCrypt');
        $BraveCrypt->init();
        
        $bind_key_str = $BraveCrypt->decrypt($bind_key, AES_BIND_KEY, AES_BIND_IV);
        $bind_key_arr = explode('#', $bind_key_str);
        
        $phone = $bind_key_arr[0];
        $student_id = $bind_key_arr[1];
        $relation_title = $bind_key_arr[3];
        
        if (!$student_info = $this->studentModel->getStudent($student_id)) {
            $this->redirect('?c=index');
            exit();
        }
        
        $this->view->assign('apply_info', $apply_info);
        $this->view->assign('student_info', $student_info);
        $this->view->assign('phone', $phone);
        $this->view->assign('relation_title', $relation_title);
        $this->view->layout();
    }
    
    /**
     * @desc 同意申请
     * @author lxs
     */
    function agreeBindAction() {
        $wxUser = $this->getAccessWxUser();
        
        $id = $this->post('id');
        $bind_key = trim($this->post('key'));
        
        if (!$bind_parent = $this->weichatBindModel->getWeichatBindParent($wxUser['id'])) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！你没有权限操作',
            );
            print json_encode($return);
            exit();
        }
        $bind_parent_phone = $bind_parent['phone'];
        
        if (!$apply_info = $this->weichatBindModel->getBindParentApply($id)) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！申请信息有误',
            );
            print json_encode($return);
            exit();
        }
        
        if ($apply_info['bind_key'] != $bind_key) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！申请信息有误',
            );
            print json_encode($return);
            exit();
        }
        
        if ($apply_info['status'] != BIND_PARENT_APPLY_UNDO) {
            $return = array(
                'code' => 201,
                'message' => '请求失败！申请已被处理',
            );
            print json_encode($return);
            exit();
        }
        
        $student_id = $apply_info['student_id'];
        if (!$student_info = $this->studentModel->getStudent($student_id)) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！学生信息有误',
            );
            print json_encode($return);
            exit();
        }
        
        if (!$bind_parent_info = $this->schoolParentModel->getStudentParentByPhone($student_id, $bind_parent_phone)) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！你无权对该学生进行操作',
            );
            print json_encode($return);
            exit();
        }
        
        $bind_data = array(
            'phone' => $apply_info['phone'],
            'relation_type' => $apply_info['relation_id'],
            'relation_title' => $apply_info['relation_title'],
            'student_id' => $student_id,
            'school_id' => $student_info['school_id'],
            'class_id' => $student_info['class_id'],
            'weichat_id' => $apply_info['weichat_id'],
        );
        
        $return = $this->weichatBindModel->bindStudentParent($bind_data, false);
        
        if ($return['code'] == 0) {
            $return['sid'] = $student_id;
            
            //设置申请状态
            $this->weichatBindModel->saveBindApplyStatus($id, BIND_PARENT_APPLY_AGREE);
            
            //向申请者发送绑定成功微信消息推送
            if ($weichat_info = $this->weichatBindModel->getWeichat($apply_info['weichat_id'])) {
                $wx_data = array(
                    'open_id' => $weichat_info['openid'],
                    'student_name' => $student_info['name'],
                    'relation_title' => $bind_parent_info['relation_title'],
                    'send_time' => NOW,
                );
                
                $wx_data['message'] = $this->weichatPushMessageModel->genBindApplySuccessMessage($wx_data);
                $this->weichatPushMessageModel->saveMessage($wx_data);
            }
        }
        
        print json_encode($return);
        exit();
    }
    
    /**
     * @desc 拒绝申请
     * @author lxs
     */ 
    function refuseBindAction() {
        $wxUser = $this->getAccessWxUser();
        
        $id = $this->post('id');
        $bind_key = trim($this->post('key'));
        
        if (!$bind_parent = $this->weichatBindModel->getWeichatBindParent($wxUser['id'])) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！你没有权限操作',
            );
            print json_encode($return);
            exit();
        }
        $bind_parent_phone = $bind_parent['phone'];
        
        if (!$apply_info = $this->weichatBindModel->getBindParentApply($id)) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！申请信息有误',
            );
            print json_encode($return);
            exit();
        }
        
        if ($apply_info['bind_key'] != $bind_key) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！申请信息有误',
            );
            print json_encode($return);
            exit();
        }
        
        if ($apply_info['status'] != BIND_PARENT_APPLY_UNDO) {
            $return = array(
                'code' => 201,
                'message' => '请求失败！申请已被处理',
            );
            print json_encode($return);
            exit();
        }
        
        $student_id = $apply_info['student_id'];
        if (!$student_info = $this->studentModel->getStudent($student_id)) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！学生信息有误',
            );
            print json_encode($return);
            exit();
        }
        
        if (!$bind_parent_info = $this->schoolParentModel->getStudentParentByPhone($student_id, $bind_parent_phone)) {
            $return = array(
                'code' => 1,
                'message' => '请求失败！你无权对该学生进行操作',
            );
            print json_encode($return);
            exit();
        }
        
        //设置申请状态
        if ($this->weichatBindModel->saveBindApplyStatus($id, BIND_PARENT_APPLY_REFUSE)) {
            //向申请者发送绑定被拒的微信消息推送
            if ($weichat_info = $this->weichatBindModel->getWeichat($apply_info['weichat_id'])) {
                $wx_data = array(
                    'open_id' => $weichat_info['openid'],
                    'student_name' => $student_info['name'],
                    'relation_title' => $bind_parent_info['relation_title'],
                    'send_time' => NOW,
                );
                
                $wx_data['message'] = $this->weichatPushMessageModel->genBindApplyRefuesMessage($wx_data);
                $this->weichatPushMessageModel->saveMessage($wx_data);
            }
            
            $return = array(
                'code' => 0,
                'message' => '保存成功',
            );
        }
        else {
            $return = array(
                'code' => 1,
                'message' => '请求失败！请重新操作',
            );
        }
        
        print json_encode($return);
        exit();
    }
    
}

?>
