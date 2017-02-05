<?php

/**
 * @description 学生接口
 */
class StudentController extends AppController {

    var $studentModel = null;
    var $fileModel = null;
    var $messageModel = null;
    var $weichatPushMessageModel = null;
    var $schoolRoleModel = null;
    var $ossClient = null;
    var $bucket = null;

    function StudentController() {
        $this->AppController();
        $this->studentModel = $this->getModel('Student');
        $this->fileModel = $this->getModel('File');
        $this->messageModel = $this->getModel('Message');
        $this->weichatPushMessageModel = $this->getModel('WeichatPushMessage');
        $this->schoolRoleModel = $this->getModel('SchoolRole');
        $this->ossClient = $this->getOssClient();
        $this->bucket = $this->code('oss.default.bucket');
    }

    /**
     * @description ✔ 获取学生信息列表
     * @param name=学校ID var=schoo_id type=int required=true remark=学校ID
     * @param name=时间戳 var=timestamp type=string required=true remark=时间戳，用于增量更新，格式：YYYYMMDDHHMMSS
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
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
                  ),
                  array(
                      'id' => 999, // 唯一ID
                      'class_id' => 67, //班级ID
                      'class_title' => '班级名称', //班级名称
                      'name' => '小诺',  //姓名
                      'gender' => 1, //性别 1 - 男 2 - 女
                      'birthday' => '2015-11-28', //生日
                      'is_registered' => 0, //是否已在终端注册：0-否、1-是
                  )
              )
          )
        )
     */
    function getStudentListAction() {

        $device = $this->getAccessDevice();

        $request = $this->rawData2Arr();

        if($this->studentModel->validGetStudentList($request, $errors)) {

            $timestamp = '';
            if($request['timestamp']) {
                $timestamp = date('Y-m-d H:i:s', strtotime($request['timestamp']));
            }

            $studentList = $this->studentModel->getStudentList($device['school_id'], $timestamp);
            foreach($studentList as $k => $v) {
                $studentList[$k] = array(
                    'id' => $v['id'], // 唯一ID
                    'class_id' => $v['class_id'],
                    'class_title' => $v['class_title'], //班级名称
                    'name' => $v['name'],  //姓名
                    'gender' => $v['gender'], //性别 1 - 男 2 - 女
                    'birthday' => $v['birthday'], //生日
                    'is_registered' => $v['device_registered'],
                );
            }

            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
                'data' => array(
                    'school_title' => $device['school_title'],
                    'student' => $studentList,
                )
            );
        } else {
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @description ✔ 上传学生检测信息（注：该接口通过 POST 传值，采用标准浏览器提交表单含文件域机制）
     * @param name=学生ID var=id type=int required=true remark=学生ID
     * @param name=身高 var=height type=int required=true remark=身高，单位：毫米
     * @param name=体重 var=weight type=int required=true remark=体重，单位：克
     * @param name=体温 var=temperature type=float required=true remark=体温，单位：度
     * @param name=检测信息状态 var=state_type type=int required=false remark=检测信息状态：1-红色、2-黄色、3-绿色
     * @param name=识别类型 var=recognition_type type=int required=false remark=识别类型：
     * @param name=环境温度 var=env_temperature type=float required=true remark=环境温度，单位：度
     * @param name=原始温度 var=raw_temperature type=float required=true remark=原始温度，单位：度
     * @param name=体温阈值 var=temperature_threshold type=float required=true remark=体温阈值，单位：度
     * @param name=图像ID var=img_id type=string required=true remark=图像标识ID
     * @param name=图像 var=img type=file required=true remark=学生检测图像
     * @param name=检测时间 var=create_time type=string required=false remark=格式：YYYYMMDDHHMMSS
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'id' => 11111, //服务器检测数据标识ID
          ),
        )
     */
    function putStudentDetectionAction() {

        $device = $this->getAccessDevice();

        $request = $this->post();

        if($student = $this->studentModel->getStudent($device['school_id'], $request['id'])) {

           if($this->studentModel->validPutStudentDetection($request, $errors)) {
       
                $dir = 'detection/';
      
                $keys = array_keys($_FILES['img']);
                foreach($keys as $k) {
                    $_FILES['img'][$k] = array('file' => $_FILES['img'][$k]);
                }

                $_FILES['img']['file'] = $_FILES['img'];

                $uploadConfig = $this->code('upload.img');

                $configKey = 'img[file]';
                $postKey = 'img';
				$uploadConfig [$configKey] ['dir'] = $dir . date ( 'Ymd', time () );
				
				$fileNameArray = explode ( '.', $_FILES ['img'] ['name'] ['file'] );
				$object = $uploadConfig [$configKey] ['dir'] . '/' . md5 ( $fileNameArray ['0'] . time () ) . '.' . $fileNameArray ['1'];
				$uploadFile = $this->ossClient->uploadFile ( $this->bucket, $object, $_FILES ['img'] ['tmp_name'] ['file'] );
				$file_name = md5 ( $fileNameArray ['0'] . time () ) . '_thumb.' . $fileNameArray ['1'];
				$thumburl = APP_OSS_URL . $object;
				$file_path = $object . $this->thumburl ( $thumburl );
				
				if ($uploadFile) {
                    $file = array(
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                        'file_mime' => $_FILES['img']['type']['file'],
                        'file_size' => $_FILES['img']['size']['file'],
                        'usage_id' => $request['id'],
                        'usage_type' => FILE_USAGE_TYPE_STUDENT_DETECTION,
                    );
                    
                    $file_id = $this->fileModel->saveFile($file);
                    
                } else {
                    $return = array(
                        'code' => 1, //  0 - 成功 1 - 失败
                        'message' => implode(',', $errors), // code 不为 0 时的错误信息
                    );
                    
                    echo $this->json_encode($return);
                    exit();
                }
   
                $data = array(
                    'student_id' => $request['id'],
                    'height' => $request['height'],
                    'weight' => $request['weight'],
                    'temperature' => $request['temperature'],
                    'state_type' => $request['state_type'],
                    'recognition_type' => $request['recognition_type'] ? $request['recognition_type'] : '',
                    'env_temperature' => $request['env_temperature'],
                    'raw_temperature' => $request['raw_temperature'],
                    'temperature_threshold' => $request['temperature_threshold'],
                    'terminal_img_id' => $request['img_id'],
                    'file_img_id' => $file_id,
                    'org_img_url' => $object,
                    'create_time' => $request['create_time'],
                );

                
//              	先查询，再更新，然后再插入（以前的顺序是先插入，再更新，效率很低）
// 	                先查询数量：
//         			如果数量=0，
//         			插入'lastest' => 1,'first' => 1,
//         			lastest     first
//         			1           1
        					
//         			数量等于1，插入，'lastest' => 1,'first' => 0,
//         			更新'lastest' => 0     更新条件lastest = 1
//         			执行结果
//         			lastest     first
//         			1 ->0        1     
//         			1           0
        	
//         			数量大于1，插入，'lastest' => 1,'first' => 0,
//         			更新'lastest' => 0     更新条件lastest = 1
//         			执行结果
//         			lastest     first
//         			1 ->0        1     
//         			1 ->0        0
//         			1            0
				$count = $this->studentModel->getStudentDetectionCount($request['id'], date('Y-m-d'));
				if($count==0){
          			$id = $this->studentModel->saveStudentDetectionWithFirst($data,1);
				}else{
					$this->studentModel->updateLastLastest($request['id'], date('Y-m-d'));
					$id = $this->studentModel->saveStudentDetectionWithFirst($data,0);
				}

                //保存推送信息
                $parentList = $this->studentModel->getStudentParentList($request['id']);
                foreach($parentList as $parent) {
                    if($parent['openid']) {
                        $form = array(
                            'open_id' => $parent['openid'],
                            'day_report_id' => $id,
                            'detection' => $request,
                            'device' => $device,
                            'student' => $student,
                            'send_time' => NOW,
                        );

                        $form['message'] = $this->weichatPushMessageModel->genDayReportMessage($form);

                        $this->weichatPushMessageModel->saveMessage($form);
                    }
                    
                }

                $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '', // code 不为 0 时的错误信息
                    'data' => array(
                        'id' => $id,
                    ),
                );
            } else {
                $return = array(
                    'code' => 1, //  0 - 失败
                    'message' => implode(';', $errors), // code 不为 0 时的错误信息
                );
            }
        } else {
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => '学生ID不存在：' . $device['school_id'] . ' - ' .  $request['id'],
            );
        }
        
        echo $this->json_encode($return);
        exit();
        
    }
    
    /**
     * @description ✔ 上传未认出学生检测信息到认领库（注：该接口通过 POST 传值，采用标准浏览器提交表单含文件域机制）
     * @param name=身高 var=height type=int required=true remark=身高，单位：毫米
     * @param name=体重 var=weight type=int required=true remark=体重，单位：克
     * @param name=体温 var=temperature type=float required=true remark=体温，单位：度
     * @param name=检测信息状态 var=state_type type=int required=false remark=检测信息状态：1-红色、2-黄色、3-绿色
     * @param name=识别类型 var=recognition_type type=int required=false remark=识别类型：
     * @param name=环境温度 var=env_temperature type=float required=true remark=环境温度，单位：度
     * @param name=原始温度 var=raw_temperature type=float required=true remark=原始温度，单位：度
     * @param name=体温阈值 var=temperature_threshold type=float required=true remark=体温阈值，单位：度
     * @param name=图像ID var=img_id type=string required=true remark=图像标识ID
     * @param name=图像 var=img type=file required=true remark=学生检测图像
     * @param name=检测时间 var=create_time type=string required=false remark=格式：YYYYMMDDHHMMSS
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'id' => 11111, //服务器检测数据标识ID
          ),
        )
     */
    function putDetectionClaimAction() {
        //$_SERVER['HTTP_DEVICE_NO'] = '0001';
        $device = $this->getAccessDevice();
        
        $request = $this->post();
        if($this->studentModel->validPutDetectionClaim($request, $errors)) {
           
            $dir = 'detection/';
            $keys = array_keys($_FILES['img']);
            foreach($keys as $k) {
                $_FILES['img'][$k] = array('file' => $_FILES['img'][$k]);
            }

            $_FILES['img']['file'] = $_FILES['img'];

            $uploadConfig = $this->code('upload.img');

            $configKey = 'img[file]';
            $postKey = 'img';

            $uploadConfig[$configKey]['dir'] = $dir . date('Ymd', time());
            
            // $upload = $this->load(EXTEND, 'BraveUpload');
            // $upload->upload($uploadConfig);
            // $uploadFile = $this->post($postKey);
           $fileNameArray = explode('.',$_FILES['img']['name']['file']);
            $object = $uploadConfig[$configKey]['dir'] . '/' . md5($fileNameArray['0'] . time()) . '.' . $fileNameArray['1'];
            $uploadFile = $this->ossClient->uploadFile($this->bucket, $object, $_FILES['img']['tmp_name']['file']);
            $file_name = md5($fileNameArray['0'] . time()) . '_thumb.' . $fileNameArray['1'];
            $thumburl = APP_OSS_URL . $object;
            $file_path = $object . $this->thumburl($thumburl);
            // $file_name = explode('.', $uploadFile['file_info']['file']);
            // $file_name2 = $file_name[0] . "_thumb." . 'jpg';
            // $file_path = explode('.', $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save']);
            // $file_path2 = $file_path[0] . "_thumb." . 'jpg';

            if ($uploadFile) {
                $file = array(
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'file_mime' => $_FILES['img']['type']['file'],
                    'file_size' => $_FILES['img']['size']['file'],
                    'usage_id' => '0',
                    'usage_type' => FILE_USAGE_TYPE_STUDENT_DETECTION,
                );
                
                // $this->gdImgCompress(APP_RESOURCE_ROOT . DS . $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save']);
                
                $file_id = $this->fileModel->saveFile($file);
                
            } else {
                $return = array(
                    'code' => 1, //  0 - 成功 1 - 失败
                    'message' => implode(',', $errors), // code 不为 0 时的错误信息
                );
                
                echo $this->json_encode($return);
                exit();
            }
            
            $data = array(
                'student_id' => $request['id'],
                'height' => $request['height'],
                'weight' => $request['weight'],
                'temperature' => $request['temperature'],
                'state_type' => $request['state_type'],
                'recognition_type' => $request['recognition_type'] ? $request['recognition_type'] : '',
                'env_temperature' => $request['env_temperature'],
                'raw_temperature' => $request['raw_temperature'],
                'temperature_threshold' => $request['temperature_threshold'],
                'terminal_img_id' => $request['img_id'],
                'file_img_id' => $file_id,
                'org_img_url' => $object,
                'create_time' => $request['create_time'],
            );
            $id = $this->studentModel->saveStudentDetectionClaim($data);
            
            $claim = array(
                'detection_id' => $id,
                'terminal_img_id' => $data['terminal_img_id'],
                'school_id' => $device['school_id'],
            );
            $this->studentModel->saveDetectionClaim($claim);

            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
                'data' => array(
                    'id' => $id,
                ),
            );
        } else {
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
    
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @author 
     * @description ✔ 获取认领库数据
     * @param name=学校ID var=school_id type=int required=true remark=学校ID
     * @param name=时间戳 var=timestamp type=string required=false remark=时间戳，用于增量更新，格式：YYYYMMDDHHMMSS，非必填
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              array(
                'student_id' => 111,      //学生ID
                'img_id' => 111,          //图片ID
                'match' => 0,             //不匹配
                'created' => 12222222222, //退回时间戳（单位为秒）
              ),
              array(
                'student_id' => 111,      //学生ID
                'img_id' => 111,          //图片ID
                'match' => 1,             //匹配
                'created' => 12333333333, //匹配时间戳（单位为秒）
              ),
          )
      )
     */
    function getStudentClaimDataAction() {
        //$_SERVER['HTTP_DEVICE_NO'] = '0001';
        //$device = $this->getAccessDevice();

        $request = $this->rawData2Arr();
        
        if($this->schoolRoleModel->validGetSchooID($request, $errors)){
            if($this->schoolRoleModel->getSchool($request['school_id'])){
                $studentClaimList = $this->studentModel->getStudentClaimList($request['school_id'], $request['timestamp']);
                $data = array();
                foreach($studentClaimList as $val){
                    
                    $this->studentModel->getDetectionClaimWhether($val['id']);
                    $data[] = array(
                        'student_id' => $val['student_id'],      //学生ID
                        'img_id' => $val['terminal_img_id'],          //图片ID
                        'match' => $val['type'],             //匹配
                        'created' => strtotime($val['created']), //匹配时间戳（单位为秒）
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
                    'code' => 1, //  0 - 失败
                    'message' => '学校ID不存在。' . $device['school_id'] . ' - ' .  $request['id'],
                );
            }
            
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
     * @author 
     * @description  ✔ 上传红黄绿检测结果
     * @param name=检测数据ID var=detection_id type=int required=true remark=服务器检测数据标识ID
     * @param name=检测结果 var=result type=int required=true remark=检测结果：1-红色、2-黄色、3-绿色
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
      )
     */
    function putHandheldResultAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->studentModel->validStudentDetection($request, $errors)){
            $this->studentModel->updateDetectionStateType($request['detection_id'], $request['result']);
            
            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
            );
            
            echo $this->json_encode($return);
            exit();
        }else{
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
            
            echo $this->json_encode($return);
            exit();
        }
        
    }
    
    /**
     * @author 
     * @description  ✔ 上传红黄绿检测图像（注：该接口通过 POST 传值，采用标准浏览器提交表单含文件域机制）
     * @param name=检测数据ID var=detection_id type=int required=true remark=服务器检测数据标识ID
     * @param name=图像 var=img type=file required=true remark=检测图像
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
      )
     */
    function putHandheldImgAction() {
        
        $request = $this->post();
        
        if($this->studentModel->validDetection($request, $errors)){
            $dir = 'detection/';
            $keys = array_keys($_FILES['img']);
            foreach($keys as $k) {
                $_FILES['img'][$k] = array('file' => $_FILES['img'][$k]);
            }
            
            $_FILES['img']['file'] = $_FILES['img'];
            
            $uploadConfig = $this->code('upload.img');
            
            $configKey = 'img[file]';
            $postKey = 'img';
            
            $uploadConfig[$configKey]['dir'] = $dir . date('Ymd', time());
            
            $upload = $this->load(EXTEND, 'BraveUpload');
            $upload->upload($uploadConfig);
            $uploadFile = $this->post($postKey);
             
            $file_name = explode('.', $uploadFile['file_info']['file']);
            $file_name2 = $file_name[0] . "_thumb." . 'jpg';
            $file_path = explode('.', $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save']);
            $file_path2 = $file_path[0] . "_thumb." . 'jpg';
            
            if ($this->fileModel->validFileUpload($uploadFile, $errors)) {
                $file = array(
                    'file_name' => $file_name2,
                    'file_path' => $file_path2,
                    'file_mime' => $uploadFile['file_info']['type'],
                    'file_size' => $uploadFile['file_info']['size'],
                    'usage_id' => $request['detection_id'],
                    'usage_type' => FILE_USAGE_TYPE_STUDENT_HANDHELD,
                );
                
                $this->gdImgCompress(APP_RESOURCE_ROOT . DS . $uploadConfig[$configKey]['uri'] . '/' . $uploadFile['file_info']['save']);
            
                $file_id = $this->fileModel->saveFile($file);
                
                $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '', // code 不为 0 时的错误信息
                );
                 
                echo $this->json_encode($return);
                exit();
            }else{
                $return = array(
                    'code' => 1, //  0 - 失败
                    'message' => implode(';', $errors), // code 不为 0 时的错误信息
                );
                
                echo $this->json_encode($return);
                exit();
            }
        }else{
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
            
            echo $this->json_encode($return);
            exit();
        }
    }
    
    /**
     * @author 
     * @description ✔ 上传学生已在终端注册结果
     * @param name=学生ID var=student_id type=int required=true remark=学生标识ID
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
      )
     */
    function putStudentRegisteredAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->studentModel->validPutStudentRegistered($request, $errors)){
            $student = $this->studentModel->getStudentOne($request['student_id']);
            if($student){
                
                $this->studentModel->updateStudentRegistered($request['student_id']);
                $return = array(
                    'code' => 0, 
                    'message' => '', 
                );
            }else{
                $return = array(
                    'code' => 1, 
                    'message' => '上传ID错误，没有找到相对应的学生信息。', 
                );
                
            }
            
        }else{
            $return = array(
                'code' => 1, 
                'message' => implode(';', $errors), 
            );
            
        }
        
        echo $this->json_encode($return);
        exit();
    }
}
?>
