<?php

class StaffController extends AppController {

    var $staffModel = null;
    var $fileModel = null;
    var $schoolParentModel = null;
    var $studentModel = null;
    var $userMessageModel = null;
    var $voiceModel = null;
    var $studentDetectionModel = null;
    var $ossClient = null;
    var $bucket = null;
    
    function StaffController() {
        $this->AppController();
        $this->staffModel = $this->getModel('Staff');
        $this->fileModel = $this->getModel("File");
        $this->schoolParentModel = $this->getModel('SchoolParent');
        $this->studentModel = $this->getModel('Student');
        $this->userMessageModel = $this->getModel('UserMessage');
        $this->voiceModel = $this->getModel('Voice');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
        $this->ossClient = $this->getOssClient();
        $this->bucket = $this->code('oss.default.bucket');
    }

    /**
     * @desc 职工签到二维码
     * @desc 界面 - wzl
     */
    function signQrcodeAction() {
        $this->validAccessLoginUser('?c=index&a=bind');
        
        //强制页面不缓存
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
        
        $staff_id = $this->get('staff_id', 0);

        $staff = $this->staffModel->getStaff($staff_id);
        
        $this->view->assign('random_num', time());
        $this->view->assign('staff', $staff);
        $this->view->layout();
    }
    
    /**
     * @desc 职工发布留言通知
     * @desc - wei
     */
    function deliverNoticeAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        $signPackage = $this->getWeichatSignPackage();
        $this->view->assign('signPackage', $signPackage);
        
        $staff = $this->staffModel->getStaff($user['id']);
        $studentList = $this->staffModel->getTeacherStudentList($staff['class_id']);
        
        $this->view->assign('studentList', $studentList);
        $this->view->layout();
    }
    /**
     * @desc ajax提交老师发布数据
     * @desc - wei
     */
    function ajaxDeliverNoticeCommentAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        $staff = $this->staffModel->getStaff($user['id']);
        
        $form = $this->post('form');
        $student_ids = implode(',',$form['student_id']);
        $this->log('form:'.print_r($form, true), 'comment');
        if($this->isComplete()){
            switch($form['type']){
                case 1: // 老师发布公告消息
                    if ($this->staffModel->validSaveClassNotice($form, $errors)) {
                        
                        $data = array(
                            'school_id' => $user['school_id'],
                            'class_id' => $staff['class_id'],
                            'staff_id' => $user['id'],
                            'type_c' => $form['comment_type'],
                            'notice_type' => CLASS_NOTICE_TYPE,
                            'student_ids' => $student_ids,
                            'content' => $form['content'],
                            'voice_id' => $form['voice_id'] ? $form['voice_id'] : '', 
                            'can_reply' => APP_UNIFIED_TRUE,
                        );
                        $class_notice_id = $this->staffModel->saveClassNotice($data);
                        foreach($form['student_id'] as $id){
                            $parentList = $this->schoolParentModel->getStudentParentListByStudentId($id);
                            
                            $studentName = $this->studentModel->getStudent($id);
                            foreach($parentList as  $val){
                                //生成系统通知
                                $system_notice = array(
                                    'class_notice_id' => $class_notice_id,
                                    'receive_uid' => $val['parent_id'],
                                    'receive_utype' => ACT_PARENT_ROLE,
                                    'send_uid' => $user['id'],
                                    'send_utype' => ACT_SCHOOL_TEACHER,
                                    'send_user_avatar' => './system/99.png',
                                    'send_user_name' => $user['name'],
                                    'is_read' => APP_UNIFIED_FALSE,
                                    'is_url' => APP_UNIFIED_TRUE,
                                    'url' => '?c=public&a=noticeDetailed&class_notice_id='.$class_notice_id . '&studentId='.$id,
                                    'content' => '<span class="al-tongzhi-color">班级通知：</span>'.$user['name'].'老师给你发送了<span class="tongzhi-color">'. $studentName['name'] .'小朋友的班级通知</span>，请点击查看详情。',
                                    // 'img_notice' => './img/notice01.png',
                                );
                                $this->staffModel->saveSystemNotice($system_notice);
                            }
                            
                        }
                        $return = array(
                            'code' => 0,
                            "message" => '发送成功',
                        );
                        
                    }else{
                        $return = array(
                            'code' => 1,
                            "message" => implode(';', $errors),
                        );
                    }
                    break;

                case 2:
                    if ($this->staffModel->validSaveStudentPraise($form, $errors)) {
                        
                        $data = array(
                            'student_ids' => $student_ids,
                            'staff_id' => $form['teacher_id'],
                            'content' => $form['content'],
                        );
                        $student_praise_id = $this->staffModel->saveStudentPraise($data);
                        if($student_praise_id){
                            foreach($form['student_id'] as $id){
                                $parentList = $this->schoolParentModel->getStudentParentListByStudentId($id);
                                $studentName = $this->studentModel->getStudent($id);
                                foreach($parentList as  $val){
                                    //生成系统通知
                                    $system_notice = array(
                                        'receive_uid' => $val['parent_id'],
                                        'receive_utype' => ACT_PARENT_ROLE,
                                        'send_uid' => $user['id'],
                                        'send_utype' => ACT_SCHOOL_TEACHER,
                                        'send_user_avatar' => './system/99.png',
                                        'send_user_name' => $user['name'],
                                        'is_read' => APP_UNIFIED_FALSE,
                                        'is_url' => APP_UNIFIED_TRUE,
                                        'url' => '?c=public&a=noticeDetailed&student_praise_id='.$student_praise_id . '&studentId='.$id,
                                        'content' => '<span class="al-tongzhi-color">收到红花：</span>'.$user['name'].'老师给<span class="tongzhi-color">'. $studentName['name'] .'小朋友奖励了小红花</span>，请点击查看详情。',
                                        // 'img_notice' => './img/notice02.png',
                                    );
                                    $this->staffModel->saveSystemNotice($system_notice);
                                }
                                
                            }
                        }
                        $return = array(
                            'code' => 0,
                            "message" => '发送成功',
                        );
                        
                    }else{
                        $return = array(
                            'code' => 1,
                            "message" => implode(';', $errors),
                        );
                    }
                    break;
                    
                case 3:
                    if($this->staffModel->validSaveClassNotice($form, $errors)){
                        $data = array(
                            'school_id' => $user['school_id'],
                            'class_id' => $staff['class_id'],
                            'staff_id' => $user['id'],
                            'type_c' => $form['comment_type'],
                            'notice_type' => EVALUATE_NOTICE_TYPE,
                            'student_ids' => $student_ids,
                            'content' => $form['content'],
                            'voice_id' => $form['voice_id'] ? $form['voice_id'] : '', 
                            'can_reply' => APP_UNIFIED_TRUE,
                        );
                        $class_notice_id = $this->staffModel->saveClassNotice($data);
                        foreach($form['student_id'] as $id){
                            $parentList = $this->schoolParentModel->getStudentParentListByStudentId($id);
                            $studentName = $this->studentModel->getStudent($id);
                            foreach($parentList as  $val){
                                //生成系统通知
                                $system_notice = array(
                                    'receive_uid' => $val['parent_id'],
                                    'receive_utype' => ACT_PARENT_ROLE,
                                    'send_uid' => $user['id'],
                                    'send_utype' => ACT_SCHOOL_TEACHER,
                                    'send_user_avatar' => './system/99.png',
                                    'send_user_name' => $user['name'],
                                    'is_read' => APP_UNIFIED_FALSE,
                                    'is_url' => APP_UNIFIED_TRUE,
                                    'url' => '?c=public&a=noticeDetailed&class_notice_id='.$class_notice_id . '&studentId='.$id,
                                    'content' => '<span class="al-tongzhi-color">宝贝评价：</span>'.$user['name'].'老师给<span class="evaluate-color">'. $studentName['name'] .'小朋友进行了宝贝点评</span>，请点击查看详情。',
                                    // 'img_notice' => './img/notice03.png',
                                );
                                $this->staffModel->saveSystemNotice($system_notice);
                            }
                            
                        }
                        $return = array(
                            'code' => 0,
                            "message" => '发送成功',
                        );
                        
                    }else{
                        $return = array(
                            'code' => 1,
                            "message" => implode(';', $errors),
                        );
                    }
                    break;
                    
                case 4:
                    if ($this->staffModel->validStudentPic($form, $errors)) {
                        
                        $data = array(
                            'student_id' => $student_ids,
                            'staff_id' => $user['id'],
                            'type' => ACT_SCHOOL_TEACHER,
                            'pic_ids' => $form['img_ids'],
                        );
                        $pic_id = $this->staffModel->saveStudentPic($data);
                        foreach($form['student_id'] as $id){
                            $parentList = $this->schoolParentModel->getStudentParentListByStudentId($id);
                            $studentName = $this->studentModel->getStudent($id);
                            foreach($parentList as  $val){
                                //生成系统通知
                                $system_notice = array(
                                    'receive_uid' => $val['parent_id'],
                                    'receive_utype' => ACT_PARENT_ROLE,
                                    'send_uid' => $user['id'],
                                    'send_utype' => ACT_SCHOOL_TEACHER,
                                    'send_user_avatar' => './system/99.png',
                                    'send_user_name' => $user['name'],
                                    'is_read' => APP_UNIFIED_FALSE,
                                    'is_url' => APP_UNIFIED_TRUE,
                                    'url' => '?c=public&a=noticeDetailed&pic_id='.$pic_id,
                                    'content' => '<span class="al-tongzhi-color">宝贝照片：</span>'.$user['name'].'老师给你发送了<span class="evaluate-color">' . $studentName['name'] . '的宝贝今日照片</span>，请点击查看详情。',
                                    // 'img_notice' => './img/notice04.png',
                                );
                                $this->staffModel->saveSystemNotice($system_notice);
                            }
                            
                        }
                        $return = array(
                            'code' => 0,
                            "message" => '发送成功。',
                        );
                        
                    }else{
                        $return = array(
                            'code' => 1,
                            "message" => implode(';', $errors),
                        );
                    }
                    break;
                    
                default:
                    $return = array(
                        'code' => 1,
                        "message" => '上传数据类型出错。',
                    );
                    break;
            }
            
        }else{
           $return = array(
                'code' => 1,
                "message" => '上传数据错误。',
            ); 
        }
        
        
        print json_encode($return);
        exit();
    }
    /**
     * @desc 职工留言通知
     * @desc - wei
     */
    function noticeAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $teacherNoticeList = $this->staffModel->getTeacherNoticeList($user['id']);
        // $userSystemNoticeList = $this->staffModel->getUserSystemNoticeList($user['id']);
        $this->view->assign('user', $user);
        // $this->view->assign('userSystemNoticeList', $userSystemNoticeList);
        $this->view->assign('teacherNoticeList',$teacherNoticeList);

        $this->view->layout();
    }
    /**
     * @desc 职工留言详情
     * @desc - wei
     */
    function noticeDetailedAction(){
        /*标志是否为园长阅读*/
        $type = $this->get('type');
        if(!$type){
            $type = -1;
        }
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $signPackage = $this->getWeichatSignPackage();
        $this->view->assign('signPackage', $signPackage);
        
        
        $systemNotice_id = $this->get('systemNotice_id');
        $class_Notice_id = $this->get('class_notice_id');

        $studentId = $this->get('studentId');
        $studentDeleteion = $this->studentDetectionModel->getDetection($studentId);
        $this->view->assign('studentDeleteion', $studentDeleteion);
        $this->view->assign('studentId', $studentId);
        
        //修改消息已读
        // $this->userMessageModel->updateIsRead($systemNotice_id);
        if($type == -1){
            $this->userMessageModel->updateIsReadByCNId($class_Notice_id,$user['id']);
        }else if($type == 32){
            $this->view->assign('type',$type);
        }
        
        $userSystemNotice = $this->staffModel->getUserSystemNotice($systemNotice_id);
        
        //查询通知消息详情
        $userClassNotice = $this->userMessageModel->getUserClassNotice($class_Notice_id);
        //如果是语音
        if($userClassNotice['type_c'] == 2){
            $voice = $this->voiceModel->getVoice($userClassNotice['voice_id']);
            $userClassNotice['voice'] = $voice;
        }
        
        $classNoticeReplyParentList = $this->staffModel->getClassNoticeReplyParentList($class_Notice_id);
        foreach($classNoticeReplyParentList as $key => $val){
            $classNoticeReplyList = $this->staffModel->getClassNoticeReplyList($class_Notice_id, $val['id']);
            $classNoticeReplyParentList[$key]['classNoticeReplyList'] = $classNoticeReplyList;
        }
        
        $userClassNotice['classNoticeReplyParentList'] = $classNoticeReplyParentList;
        
        $this->view->assign('userSystemNotice', $userSystemNotice);
        $this->view->assign('userClassNotice', $userClassNotice);
        $this->view->layout();
        
    }
    
     /**
     * @desc 老师回复家长信息
     * @desc - wei
     */   
    function ajaxClassNoticeReplyCommentAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        $form = $this->post('form');
        if($this->isComplete()){
            if ($this->staffModel->validSaveClassNoticeReply($form, $errors)) {
                $isParentClassNoticeReply = $this->staffModel->getIsParentClassNoticeReply($form['class_notice_id'], $form['parent_id'], $form['studentId']);
                $branchClassNoticeReply = $this->staffModel->getBranchClassNoticeReply($form['class_notice_id'], $form['parent_id'], $form['studentId']);
                
                $data = array(
                    'class_notice_id' => $form['class_notice_id'],
                    'student_id' => $form['studentId'],
                    'parent_id' => $isParentClassNoticeReply ? $isParentClassNoticeReply['id'] : "0",
                    'branch_id' => $branchClassNoticeReply ? $branchClassNoticeReply['id'] : '0',
                    'reply_uid' => $form['reply_uid'],
                    'reply_utype' => ACT_SCHOOL_TEACHER,
                    'reply_user_avatar' => './system/99.png',
                    'reply_user_name' => $user['name'],
                    'comment_type' => $form['comment_type'],
                    'content' => $form['content'],
                    'voice_id' => $form['voice_id'] ? $form['voice_id'] :'', 
                
                );
                $classNoticeReply_id = $this->userMessageModel->saveClassNoticeReply($data);
                
                //生成系统通知
                $system_notice = array(
                    'receive_uid' => $form['parent_id'],
                    'receive_utype' => ACT_PARENT_ROLE,
                    'send_uid' => $form['reply_uid'],
                    'send_utype' => ACT_SCHOOL_TEACHER,
                    'send_user_avatar' => './system/99.png',
                    'send_user_name' => $user['name'],
                    'is_read' => APP_UNIFIED_FALSE,
                    'is_url' => APP_UNIFIED_TRUE,
                    'url' => '?c=public&a=noticeDetailed&class_notice_id='. $form['class_notice_id'] .'&classNoticeReply_id='.$classNoticeReply_id."&studentId=".$form['studentId'],
                    'content' => '<span class="color-red">'. $user['name'] .'</span>给你回复了消息，请注意查看。',
                );
                $this->staffModel->saveSystemNotice($system_notice);
                $return = array(
                    'code' => 0,
                    "message" => '发送成功。',
                );
            }else{
                $return = array(
                    'code' => 1,
                    "message" => implode(';', $errors),
                );
            }
            
        }else{
           $return = array(
                'code' => 1,
                "message" => '上传数据错误。',
            ); 
        }
        
        print json_encode($return);
        exit();
        
    }
     /**
     * desc 响应AJAX请求删除上传图片
     */
    function deleteDeliverNoticeAction() {
        $id = $this->post('id');
        
        if ($id) {
            $this->fileModel->deleteFile($id);
            print json_encode(array('code' => 0, 'message' => '删除成功。'));
            exit();
        }
        else {
            print json_encode(array('code' => 1, 'message' => '删除失败。'));
            exit();
        }
    }
    
    /**
     * @desc ajax消息是否已读提示
     * @author wei
     */
    function ajaxMessageTishiAction(){
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        if($user){
            $userMessageTishi = $this->userMessageModel->getUserMessageTishi($user['id'],$role);
            if($userMessageTishi){
                $return = array(
                    'code' => 0,
                    "message" => '',
                );  
            }else{
                $return = array(
                    'code' => 1,
                    "message" => '暂无未查看消息。',
                );  
                
            }
        }else{
           $return = array(
                'code' => 1,
                "message" => '家长ID错误。',
            );  
        }
        print json_encode($return);
        exit();
    }
    /**
     * @desc 微信上传图片
     * @desc - wei
     */
    function weichatDownloadStaffImage($serverId) {
        $systemModel = $this->getModel('System');

        $accessToken = $systemModel->getAccessToken();
        $resp = array();
        for($i = 0; $i < 3; $i++) {//重试机制

            // 如果是企业号用以下 URL 获取 ticket
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$accessToken['access_token']}&type=jsapi";
            $resp = json_decode($this->httpsGet($url), true);

            if(!isset($resp['errcode']) || !in_array($resp['errcode'], array('41001', '40001', '42001'))) {
                break;
            } else {
                $accessToken['access_token'] = $this->refreshAccessToken();
            }
        }

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$accessToken['access_token']}&media_id={$serverId}";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0); //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        
        $image = array_merge(array('header' => $httpinfo), array('body' => $package));
        
        if(strpos($image['header']['content_type'], 'image') === false) {
            return false;
        } else {
            $name = md5(date('His', time()) . uniqid(rand(1000,9999), true));
            $dir = (string) date('Ymd');
            $path = 'deliverNotice/' . $dir . '/' . $name . '.jpg';
            $path2 = 'deliverNotice/' . $dir . '/' . $name . '_2.jpg';
            // $path = APP_RESOURCE_ROOT . 'upload'. DS . 'deliverNotice' . DS . $dir . DS . $name . '.jpg';
            // $path2 = APP_RESOURCE_ROOT . 'upload'. DS . 'deliverNotice' . DS . $dir . DS . $name . '_2.jpg';

            // $system = $this->load(EXTEND, 'BraveSystem');
            // $system->mkdirs(dirname($path));
            
            // $local_file = fopen($path, 'w');
            // if (false !== $local_file){
                // if (false !== fwrite($local_file, $image['body'])) {
                if($this->ossClient->putObject($this->bucket, $path, $image['body'])){
                    // fclose($local_file);
                    
                    // @copy($path, $path2);
                    $this->ossClient->copyObject($this->bucket, $path, $this->bucket, $path2);
                    return array(
                          // 'name' => $name . '.jpg',
                          // 'path' => '/upload/deliverNotice/' . $dir . '/' . $name . '.jpg',
                          // 'size' => $image['header']['size_download'],
                          // 'mime' => $image['header']['content_type'],
                          'name' => $name . '.jpg',
                          'path' => $path,
                          'size' => $image['header']['size_download'],
                          'mime' => $image['header']['content_type'],
                      );
                }
            // }

            return false;
        }
    }
    /**
     * @desc 保存微信上传图片
     * @desc - wei
     */
    function weichatDownloadStaffImageAction() {
        $serverId = $this->get('serverId');
        $image = $this->weichatDownloadStaffImage($serverId);
        $file_name = explode('.', $image['name']);
        $file_name2 = $file_name[0] . "_2." . $file_name[1];
        $file_path = explode('.', $image['path']);
        $file_path2 = $file_path[0] . "_2." . $file_path[1];
        $this->log('image:'. print_r($image, true), 'text');
        if($image) {
            $file_data2 = array(
                'file_name' => $file_name2,
                'file_path' => $file_path2,
                'file_mime' => $image['mime'],
                'file_size' => $image['size'],
                'usage_id' => 0,
                'usage_type' => FILE_STAFF_TYPE_NOTICE_IMG,
            );
            //$fid2 = $this->fileModel->saveFile($file_data2);
            sleep(1);

            $file = array(
                'file_name' => $image['name'],
                'file_path' => $image['path'],
                'file_mime' => $image['mime'],
                'file_size' => $image['size'],
                'usage_id' => 0,
                'usage_type' => FILE_STAFF_TYPE_NOTICE_IMG,
            );
            
            $this->gdImgCompressStaff(APP_RESOURCE_ROOT . DS . $file['file_path']);
            
            $file_id = $this->fileModel->saveFile($file);
            $file['id'] = $file_id;
            $this->log('file:'. print_r($file, true), 'text');
            print json_encode(array('code' => 0, 'file' => $file));
            exit();
        } else {
            print json_encode(array('code' => 1, 'message' => '系统繁忙，请稍后再试。'));
            exit();
        }
    }

    /**
     * @desc 保存上传语音
     * @desc - wei
     */  
    function downloadVoiceAction() {
        $user = $this->getSession(SESSION_USER);

        $serverId = $this->get('serverId');
        $teacher_id = $this->get('teacher_id');
        
        $voice = $this->weichatDownloadStaffMedia($serverId, $user['id']);
        $this->log('voice:'.print_r($voice, true), 'voice');
        if($voice) {
            $this->log($voice);

            $form = array(
                'voice_name' => $voice['name'],
                'voice_path' => $voice['path'],
                'usage_id' => $user['id'],
                'voice_type' => MESSAGE_TYPE_VOICE,
                'data' => array(
                  'serverId' => $serverId,
                )
            );
            
            $voice_id = $this->voiceModel->saveVoice($form);
            $voice = $this->voiceModel->getVoice($voice_id);
            
            print json_encode(array('code' => 0, 'voice_id' => $voice_id, 'voice' => $voice));
            exit();
        } else {
            print json_encode(array('code' => 1, 'message' => '系统繁忙，请稍后再试。'));
            exit();
        }
    }
    
    /**
     * @desc 微信获取上传语音
     * @desc - wei
     */ 
    function weichatDownloadStaffMedia($serverId, $userId) {
        $systemModel = $this->getModel('System');

        $accessToken = $systemModel->getAccessToken();

        $resp = array();
        for($i = 0; $i < 3; $i++) {//重试机制

            $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$accessToken['access_token']}&media_id={$serverId}";

            $this->log($url);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOBODY, 0); //只取body头
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $package = curl_exec($ch);
            $httpinfo = curl_getinfo($ch);
            curl_close($ch);

            $media = array_merge(array('header' => $httpinfo), array('body' => $package));

            $contentType = substr($media['header']['content_type'], strpos($media['header']['content_type'], '/') + 1);

            $this->log($media['header']);
            if($contentType == 'plain') {
                $this->log($media['body']);

                $resp = json_decode($media['body'], true);
                if(!isset($resp['errcode']) || !in_array($resp['errcode'], array('41001', '40001', '42001'))) {
                    return false;
                } else {
                    $accessToken['access_token'] = $this->refreshWXAccessToken();
                }
            }
        }

        $name = md5(date('His', time()) . uniqid(rand(1000,9999), true));

        $path = APP_RESOURCE_ROOT . 'upload'. DS . 'staffVoice' . DS . $userId . DS . $name . '.' . $contentType;

        $system = $this->load(EXTEND, 'BraveSystem');
        $system->mkdirs(dirname($path));

        $local_file = fopen($path, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $media['body'])) {
                fclose($local_file);

                return array(
                      'name' => $name . '.' . $contentType,
                      'path' => '/upload/staffVoice/' . $userId . '/' . $name . '.' . $contentType,
                      'size' => $media['header']['size_download'],
                      'mime' => $media['header']['content_type'],
                  );
            }
        }

        return false;
    }
}
?>
