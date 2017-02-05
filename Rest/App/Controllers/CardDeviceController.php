<?php

/**
 * @description 刷卡终端相关接口
 */
class CardDeviceController extends AppController {

    var $studentModel = null;

    var $fileModel = null;
    var $messageModel = null;
    var $weichatPushMessageModel = null;
    var $relationModel = null;
    var $schoolRoleModel = null;
    var $ossClient = null;
    var $bucket = null;

    function CardDeviceController() {
        $this->AppController();
        $this->studentModel = $this->getModel('Student');
        $this->fileModel = $this->getModel('File');
        $this->messageModel = $this->getModel('Message');
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
        $this->relationModel = $this->getModel('Relation');
        $this->schoolRoleModel = $this->getModel('SchoolRole');
        $this->ossClient = $this->getOssClient();
        $this->bucket = $this->code('oss.default.bucket');
    }
    
    /**
     * @author   wei
     * @description  ✔  同步学生和家长信息到刷卡终端
     * @param name=时间戳 var=timestamp type=string required=false remark=时间戳，用于增量更新，格式：YYYYMMDDHHMMSS，非必填
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              'school_id' => 111, //学校ID
              'school_title' => '幼儿园名称', //幼儿园名称
              'student' => array(
                  array(
                      'id' => 999, // 唯一ID
                      'class_id' => 67, //班级ID
                      'class_title' => '班级名称', //班级名称
                      'name' => '小诺',  //姓名
                      'gender' => 1, //性别 1 - 男 2 - 女
                      'birthday' => '2015-11-28', //生日
                      'is_registered' => 0, //是否已在终端注册：0-否、1-是
                      'parents_list' => array( //家长列表
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                      )
                  ),
                  array(
                      'id' => 999, // 唯一ID
                      'class_id' => 67, //班级ID
                      'class_title' => '班级名称', //班级名称
                      'name' => '小诺',  //姓名
                      'gender' => 1, //性别 1 - 男 2 - 女
                      'birthday' => '2015-11-28', //生日
                      'is_registered' => 0, //是否已在终端注册：0-否、1-是
                      'parents_list' => array( //家长列表
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                      )
                  )
              )
          )
      )
     */
    function getStudentListAction() {
        $device = $this->getAccessDevice();
        $request = $this->rawData2Arr();
        
        if($this->studentModel->validGetSchooID($device['school_id'], $errors)) {
            
            $timestamp = '';
            $schoo_id = $device['school_id'];
            if($request['timestamp']) {
                $timestamp = date('Y-m-d H:i:s', strtotime($request['timestamp']));
            }
            
            $schoolStudentParentList = $this->studentModel->getSchoolStudentParentList($schoo_id, $timestamp); //查询学校下面所有学生
            $tmp_arr = array();
            foreach ($schoolStudentParentList as $k => $val) {
                if (in_array($val['id'], $tmp_arr)) {
                    unset($schoolStudentParentList[$k]);
                } else {
                    $tmp_arr[] = $val['id'];
                }
            }
            $data = array();
            
            $student = array();
           
            foreach ($schoolStudentParentList as $key => $studentParent){
                $parentList = $this->studentModel->getParentList($studentParent['id']);
                $schoolClass = $this->studentModel->getSchoolClass($studentParent['school_id'],$studentParent['class_id']);
                     
                $parents_list = array();
                foreach($parentList as $parent){
                    $parents_list[] = array(
                        'parent_id' => $parent['id'], //家长ID
                        'parent_name' => $parent['name'], //家长姓名
                        'parent_type' => $parent['relation_id'], 
                        'parent_title' => $parent['relation_title'], 
                        'parent_phone' => $parent['phone'], //家长手机号
                        'parent_rfid' => $parent['rfid'], //家长IC卡号
                    );
                    
                }
                $student[] = array(
                    'id' => $studentParent['id'], // 唯一ID
                    'class_id' => $studentParent['class_id'], //班级ID
                    'class_title' => $schoolClass['class_title'], //班级名称
                    'name' => $studentParent['name'],  //姓名
                    'gender' => $studentParent['gender'], //性别 1 - 男 2 - 女
                    'birthday' => $studentParent['birthday'], //生日
                    'is_registered' => $studentParent['device_registered'],
                    'parents_list' => $parents_list,//家长列表
                 );
                
                $data = array(
                    'school_id' => $schoo_id, //幼儿园ID
                    'school_title' => $schoolClass['title'], //幼儿园名称
                    'student' => $student,
                );
            }
            
            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
                'data' => $data,
            );
        } 
        else {
            $return = array(
                'code' => 2, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @author wei
     * @description  ✔  同步学校职工信息到刷卡终端
     * @param name=时间戳 var=timestamp type=string required=false remark=时间戳，用于增量更新，格式：YYYYMMDDHHMMSS，非必填
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              'school_id' => 111, //学校ID
              'school_title' => '幼儿园名称', //幼儿园名称
              'staff_list' => array(
                  array(
                      'id' => 999, // 职工ID
                      'type' => 32, // 职工类型：31-园长、32-老师、33-保健医生、34-后勤人员
                      'name' => '老师',  //姓名
                      'rfid' => '1111111', //RFID值
                      'phone' => '15188888888', //手机号
                  ),
                  array(
                      'id' => 999, // 职工ID
                      'type' => 32, // 职工类型：31-园长、32-老师、33-保健医生、34-后勤人员
                      'name' => '保健医生',  //姓名
                      'rfid' => '1111111', //RFID值
                      'phone' => '15188888888', //手机号
                  ),
              )
          )
      )
     */
    function getStaffListAction() {
        //$_SERVER['HTTP_DEVICE_NO'] = '0001';
        $device = $this->getAccessDevice();
        $request = $this->rawData2Arr();
        
        if($this->schoolRoleModel->validGetSchooID($device['school_id'], $errors)) {
            
            $timestamp = '';
            $school_id = $device['school_id'];
            if($request['timestamp']) {
                $timestamp = date('Y-m-d H:i:s', strtotime($request['timestamp']));
            }
            $school = $this->schoolRoleModel->getSchool($school_id);
            $schoolStaffList = $this->schoolRoleModel->getSchoolStaffList($school_id, $timestamp); //查询学校下面所有职工
            
            $data = array();
            $staff_list = array();
            foreach($schoolStaffList as $val){
                $staff_list[] = array(
                    'id' => $val['id'], // 唯一ID
                    'type' => $val['type'], // 角色类型：32-老师、33-保健医生、34-后勤人员
                    'name' => $val['name'],  //姓名
                    'rfid' => $val['rfid'], //RFID值
                    'phone' => $val['phone'], //手机号
                
                );
            }
            
            $data = array(
                'school_id' => $school_id, //幼儿园ID
                'school_title' => $school['title'], //幼儿园名称
                'staff_list' => $staff_list,
            );
            
            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
                'data' => $data,
            );
        } 
        else {
            $return = array(
                'code' => 2, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @author   wei
     * @description ✔  获取学校KEY
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              'school_id' => 111, //学校ID
              'school_title' => '幼儿园名称', //学校名称
              'key' => 'abcd1234', //学校KEY值
              'key_time' => '2016-05-05 12:05:08', //学校KEY值生效时间
          )
      )
     */
    function getSchoolKeyAction() {
        $device = $this->getAccessDevice();
        
        if($this->studentModel->validSchoolKey($device['school_id'], $errors)) {
            $schoolKey = $this->studentModel->getSchoolKey($device['school_id']);
            $data = array(
                'school_id' => $schoolKey['id'], //学校ID
                'school_title' => $schoolKey['title'], //学校名称
                'key' => $schoolKey['key'], //学校KEY值
                'key_time' => $schoolKey['key_active_time'], //学校KEY值生效时间
            );
            $return = array(
                      'code' => 0, //  0 - 成功
                      'message' => '', // code 不为 0 时的错误信息
                      'data' => $data,
                );
            
        }
         else {
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @author   wei
     * @description ✔  提交学生已被接走信息
     * @param name=学生ID var=student_id type=int required=true remark=学生ID
     * @param name=家长ID var=parent_id type=int required=true remark=家长ID
     * @param name=图像 var=img[main] type=file required=true remark=图像
     * @param name=辅助图像 var=img[sub] type=file required=false remark=辅助图像
     * @param name=接走时间 var=create_time type=string required=false remark=格式：YYYYMMDDHHMMSS
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array( // code=88时返回
            'parent_id' => 111,
            'time' => '2016-05-05 05:05:05', //接走时间
          ),
       )
     */
    function submitTakeAway2Action() {
        
        $device = $this->getAccessDevice();
        $request = $this->post();
        
        if($this->studentModel->validSubmitTakeAway($request, $errors)) {
            
            //判断今天是否已经接走
            if ($away_info = $this->studentModel->isTodayTakeAway($request['student_id'])) {
                $return = array(
                    'code' => 88,
                    'message' => '孩子已经被接走',
                    'data' => array(
                        'parent_id' => $away_info['parent_id'],
                        'time' => $away_info['created'],
                    ),
                );
            
                echo $this->json_encode($return);
                exit();
            }
            
            $dir = 'studentAway/';
           
            $keys = array_keys($_FILES['img']);
            foreach($keys as $k) {
                $_FILES['img'][$k] = array('file' => $_FILES['img'][$k]);
            }
            $_FILES['img']['file'] = $_FILES['img'];

            $uploadConfig = $this->code('upload.img');
            $configKey = 'img[file]';
            $postKey = 'img.file';
          // new2
            $fileNameArray = explode('.',$_FILES['dream']['name']['file']);
            $object = $postKey . '/' . $uploadConfig[$configKey]['dir'] . '/' . md5($fileNameArray['0'] . time()) . '.' . $fileNameArray['1'];
            $uploadFile = $this->ossClient->uploadFile($this->bucket, $object, $_FILES['dream']['tmp_name']['file']);
          // org1
            // $uploadConfig[$configKey]['dir'] = $dir . date('Ymd',time());
            // $upload = $this->load(EXTEND, 'BraveUpload');
            // $upload->upload($uploadConfig);
            // $uploadFile = $this->post($postKey);


            // new
                $file_name = explode('.', $uploadFile['main_info']['file']);
                $file_name2 = $file_name[0] . "_thumb." . 'jpg';
                $file_path = explode('.', $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['main_info']['save']);
                $file_path2 = $file_path[0] . "_thumb." . 'jpg';
                if ($this->fileModel->validStudentAwayImage($uploadFile, $errors)) {
                    $file = array(
                        'file_name' => $file_name2,
                        'file_path' => $file_path2,
                        'file_mime' => $uploadFile['main_info']['type'],
                        'file_size' => $uploadFile['main_info']['size'],
                        'usage_id' => $request['student_id'],
                        'usage_type' => FILE_USAGE_TYPE_STUDENT_AWAY,
                    );
                    $file_url = APP_RESOURCE_ROOT . DS . $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['main_info']['save'];
                    $this->gdImgCompress($file_url);
                    $file_id = $this->fileModel->saveFile($file);
                } else {
                    $return = array(
                        'code' => 1, //  0 - 成功 1 - 失败
                        'message' => implode(',', $errors), // code 不为 0 时的错误信息
                    );
                    
                    echo $this->json_encode($return);
                    exit();
                }
              // new
              if($uploadFile['sub_info']){
                $file_name3 = explode('.', $uploadFile['sub_info']['file']);
                $file_name4 = $file_name3[0] . "_thumb." . 'jpg';
                $file_path3 = explode('.', $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['sub_info']['save']);
                $file_path4 = $file_path3[0] . "_thumb." . 'jpg';
                if ($this->fileModel->validStudentAwayImage($uploadFile, $errors)) {
                    $file_sub = array(
                        'file_name' => $file_name4,
                        'file_path' => $file_path4,
                        'file_mime' => $uploadFile['sub_info']['type'],
                        'file_size' => $uploadFile['sub_info']['size'],
                        'usage_id' => $request['student_id'],
                        'usage_type' => FILE_USAGE_TYPE_STUDENT_AWAY,
                    );
                    $file_sub_url = APP_RESOURCE_ROOT . DS . $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['sub_info']['save'];
                    $this->gdImgCompress($file_sub_url);
                    $file_sub_id = $this->fileModel->saveFile($file_sub);
                } else {
                    $return = array(
                        'code' => 1, //  0 - 成功 1 - 失败
                        'message' => implode(',', $errors), // code 不为 0 时的错误信息
                    );
                    
                    echo $this->json_encode($return);
                    exit();
                }
              }

            // org
            if ($this->fileModel->validStudentAwayImage($uploadFile, $errors)) {
                $data = array(
                    'student_id' => $request['student_id'],
                    'parent_id' => $request['parent_id'],
                    'img' => $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['main'],
                    'file_img_id' => $file_id,
                    'sub_img' => $uploadFile['sub'] ? $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['sub'] : '',
                    'file_sub_img_id' => $file_sub_id ? $file_sub_id : '',
                    'create_time' => $request['create_time'],
                  );
                $studentTakeAway_id = $this->studentModel->saveStudentTakeAwayFile($data);
                //保存推送信息
                $parent_rel = $this->studentModel->getParentRel($data['student_id'], $data['parent_id']);
                $student = $this->studentModel->getStudent($device['school_id'], $data['student_id']);
                $parentList = $this->studentModel->getStudentParentList($data['student_id']);
                
                foreach($parentList as $parent) {
                    if($parent['openid']) {
                        $form = array(
                            'open_id' => $parent['openid'],
                            'day_report_id' => $studentTakeAway_id,
                            'parent_title' => $parent_rel['relation_title'],
                            'device' => $device,
                            'student' => $student,
                            'send_time' => NOW,
                        );
                        
                        $form['message'] = $this->weichatPushMessageModel->genStudentTakeAwayMessage($form);
                        $this->weichatPushMessageModel->saveMessage($form);
                    }
                    
                }

                $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '', // code 不为 0 时的错误信息
                );
            } else {
                $return = array(
                    'code' => 1, //  0 - 成功
                    'message' => implode(',', $errors), // code 不为 0 时的错误信息
                );
            }
        }
        else {
            $return = array(
                'code' => 2, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
        
    }


    /**
     * @author   wei
     * @description ✔  提交学生已被接走信息
     * @param name=学生ID var=student_id type=int required=true remark=学生ID
     * @param name=家长ID var=parent_id type=int required=true remark=家长ID
     * @param name=图像 var=img type=file required=true remark=图像
     * @param name=接走时间 var=create_time type=string required=false remark=格式：YYYYMMDDHHMMSS
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array( // code=88时返回
            'parent_id' => 111,
            'time' => '2016-05-05 05:05:05', //接走时间
          ),
       )
     */
   
    function submitTakeAwayAction() {
        
        $device = $this->getAccessDevice();
        $request = $this->post();
        
        if($this->studentModel->validSubmitTakeAway($request, $errors)) {
            
            //判断今天是否已经接走
            if ($away_info = $this->studentModel->isTodayTakeAway($request['student_id'])) {
                $return = array(
                    'code' => 88,
                    'message' => '孩子已经被接走',
                    'data' => array(
                        'parent_id' => $away_info['parent_id'],
                        'time' => $away_info['created'],
                    ),
                );
            
                echo $this->json_encode($return);
                exit();
            }
            
            $dir = 'studentAway/';
            
            $keys = array_keys($_FILES['img']);
            foreach($keys as $k) {
                $_FILES['img'][$k] = array('file' => $_FILES['img'][$k]);
            }

            $_FILES['img']['file'] = $_FILES['img'];

            $uploadConfig = $this->code('upload.img');

            $configKey = 'img[file]';
            $postKey = 'img';

            $uploadConfig[$configKey]['dir'] = $dir . date('Ymd',time());
            
            $upload = $this->load(EXTEND, 'BraveUpload');
            $upload->upload($uploadConfig);
            $uploadFile = $this->post($postKey);


            if ($this->fileModel->validFileUpload($uploadFile, $errors)) {
                $data = array(
                    'student_id' => $request['student_id'],
                    'parent_id' => $request['parent_id'],
                    'img' => $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save'],
                    'create_time' => $request['create_time'],
                  );
                 
                $studentTakeAway_id = $this->studentModel->saveStudentTakeAwayFile1($data);
                //保存推送信息
                $parent_rel = $this->studentModel->getParentRel($data['student_id'], $data['parent_id']);
                $student = $this->studentModel->getStudent($device['school_id'], $data['student_id']);
                $parentList = $this->studentModel->getStudentParentList($data['student_id']);
                
                foreach($parentList as $parent) {
                    if($parent['openid']) {
                        $form = array(
                            'open_id' => $parent['openid'],
                            'day_report_id' => $studentTakeAway_id,
                            'parent_title' => $parent_rel['relation_title'],
                            'device' => $device,
                            'student' => $student,
                            'send_time' => NOW,
                        );
                        
                        $form['message'] = $this->weichatPushMessageModel->genStudentTakeAwayMessage($form);
                        $this->weichatPushMessageModel->saveMessage($form);
                    }
                    
                }

                $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '', // code 不为 0 时的错误信息
                );
            } else {
                $return = array(
                    'code' => 1, //  0 - 成功
                    'message' => implode(',', $errors), // code 不为 0 时的错误信息
                );
            }
        }
        else {
            $return = array(
                'code' => 2, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
        
    }
    
    /**
     * @author   wei
     * @description  ✔  提交学校职工签到及签退信息
     * @param name=职工ID var=id type=int required=true remark=职工对应的唯一ID
     * @param name=现场图像 var=img type=file required=true remark=图像
     * @param name=刷卡时间 var=create_time type=string required=false remark=格式：YYYYMMDDHHMMSS
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
       )
     */
    function submitStaffSignAction() {
        //$_SERVER['HTTP_DEVICE_NO'] = '0001';
        $device = $this->getAccessDevice();
        $request = $this->post();
        
        if($this->schoolRoleModel->validSchoolRoleSign($request, $errors)){
            $dir = 'schoolStaffSign/';
            
            $keys = array_keys($_FILES['img']);
            foreach($keys as $k) {
                $_FILES['img'][$k] = array('file' => $_FILES['img'][$k]);
            }

            $_FILES['img']['file'] = $_FILES['img'];

            $uploadConfig = $this->code('upload.img');

            $configKey = 'img[file]';
            $postKey = 'img';

            $uploadConfig[$configKey]['dir'] = $dir . date('Ymd',time());
            
            $upload = $this->load(EXTEND, 'BraveUpload');
            $upload->upload($uploadConfig);
            $uploadFile = $this->post($postKey);
            
            if ($this->fileModel->validFileUpload($uploadFile, $errors)) {
                $sign_date = date('Y-m-d', time());
                
                $isSchooltStaffSignDate = $this->schoolRoleModel->getIsSchooltStaffSignDate($request['id'], strtotime($sign_date));
                
                $schoolStaff = $this->schoolRoleModel->getSchoolStaff($request['id']);
                $schoolSignrecord = $this->schoolRoleModel->getSchoolSignrecord($request['id'], strtotime($sign_date));
                
                $signrecord = array(
                    'staff_id' => $request['id'],
                    'sign_timestamp' => strtotime($sign_date),
                    'sign_date' => $sign_date,
                    'first' => $schoolSignrecord ? 0 : APP_UNIFIED_TRUE,
                    'lastest' => 0,
                    'img' => $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save'],
                );
                if($schoolStaff['type'] == ACT_SCHOOL_HEADMASTER){
                    $return = array(
                        'code' => 1, //  0 - 成功
                        'message' => '园长不需要签到。', // code 不为 0 时的错误信息
                    );
                    echo $this->json_encode($return);
                    exit(); 
                    
                }

                if($schoolStaff['sign_type_id'] <= 0){
                    $return = array(
                        'code' => 1, //  0 - 成功
                        'message' => '请先去后台设置默认签到时间。', // code 不为 0 时的错误信息
                    );
                    echo $this->json_encode($return);
                    exit(); 
                    
                }

                if($this->schoolRoleModel->saveStaffSignrecord($signrecord)){
                    $return = array(
                        'code' => 0, //  0 - 成功
                        'message' => '', // code 不为 0 时的错误信息
                    );
                }else{
                    $return = array(
                        'code' => 1, //  0 - 成功
                        'message' => '签到记录失败。', // code 不为 0 时的错误信息
                    );
                }
                
                if($signrecord['first'] == APP_UNIFIED_TRUE && $schoolStaff['sign_type_id'] > 0 && $schoolStaff['type'] != ACT_SCHOOL_HEADMASTER){
                    
                    $sign_status = "";
                    if(date('H:i:s', time()) > $schoolStaff['in_time']){
                        
                        $sign_status = SIGN_STATUS_LATE_UNOUT;
                    }else{
                        
                        $sign_status = SIGN_STATUS_IN_UNOUT;
                    }
                    
                    $data = array(
                        'id' => $isSchooltStaffSignDate['id'] ? $isSchooltStaffSignDate['id'] : '',
                        'staff_id' => $isSchooltStaffSignDate['user_id'] ? $isSchooltStaffSignDate['user_id'] : $request['id'] ,
                        'sign_timestamp' => $isSchooltStaffSignDate['sign_timestamp'] ? $isSchooltStaffSignDate['sign_timestamp'] : strtotime($sign_date),
                        'sign_date' => $isSchooltStaffSignDate['sign_date'] ? $isSchooltStaffSignDate['sign_date'] : $sign_date,
                        'set_intime' => $isSchooltStaffSignDate['set_intime'] ? $isSchooltStaffSignDate['set_intime'] : $schoolStaff['in_time'],
                        'set_outtime' => $isSchooltStaffSignDate['set_outtime'] ? $isSchooltStaffSignDate['set_outtime'] : $schoolStaff['out_time'],
                        'sign_status' => $sign_status ,
                        'in_time' => date('H:i:s', time()),
                        'in_img' => $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save'],
                        'create_time' => $request['create_time'],
                    );
                    //修改签到状态
                    $this->schoolRoleModel->saveSchoolStaffSign($data);
                }
                    
            } else {
                $return = array(
                    'code' => 1, //  0 - 成功
                    'message' => implode(',', $errors), // code 不为 0 时的错误信息
                );
            }
            
        }
         else {
            $return = array(
                'code' => 1, //  0 - 成功
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();   
       
    }

    
}
?>
