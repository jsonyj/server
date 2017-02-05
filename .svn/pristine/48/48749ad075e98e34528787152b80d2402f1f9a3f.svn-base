<?php

/**
 * @desc 各角色通用功能页面
 * @author
 */

class PublicController extends AppController {
    
    var $userMessageModel = null;
    var $fileModel = null;
    var $studentModel = null;
    var $staffModel = null;
    var $voiceModel = null;
    var $studentDetectionModel = null;
    
    function PublicController() {
        $this->AppController();
        $this->userMessageModel = $this->getModel('UserMessage');
        $this->fileModel = $this->getModel("File");
        $this->studentModel = $this->getModel('Student');
        $this->staffModel = $this->getModel('Staff');
        $this->voiceModel = $this->getModel('Voice');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
    }

    /**
     * @desc 消息列表页面
     * @author wzl
     */
    function messageIndexAction() {
        $wxUser = $this->getAccessWxUser();
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        
        $parent = $this->userMessageModel->getParentId($user['phone']);
        $studentId = $this->get('studentId');
        $this->view->assign('studentId', $studentId);
        $userSystemNoticeList = $this->userMessageModel->getUserSystemNoticeList($parent['id']);
        $this->view->assign('userSystemNoticeList', $userSystemNoticeList);
        $this->view->layout();
        //退出时把消息置为已读
        foreach ($userSystemNoticeList as $k => $v) {
            if ($v['is_read'] == '0') {
                //$this->userMessageModel->updateIsRead($v['id']);
            }
        }
    }
    /**
     * @desc 消息详情页面
     * @author wei
     */
    function noticeDetailedAction(){
        $wxUser = $this->getAccessWxUser();
        $user = $this->getSession(SESSION_USER);
        if(!$user){
            $this->redirect("?c=index&a=bind");
        }
        $signPackage = $this->getWeichatSignPackage();
        $this->view->assign('signPackage', $signPackage);
        
        $parent = $this->userMessageModel->getParentId($user['phone']);
        
        $class_notice_id = $this->get('class_notice_id');
        $pic_id = $this->get('pic_id');
        $studentId = $this->get('studentId');
        $systemNotice_id = $this->get('systemNotice_id');
        $student_praise_id = $this->get('student_praise_id');
        $student = $this->studentModel->getStudent($studentId);
        $studentDeleteion = $this->studentDetectionModel->getDetection($studentId);
        $student['studentDeleteion'] = $studentDeleteion;
        $this->view->assign('student', $student);
        //修改消息已读
        $this->userMessageModel->updateIsRead($systemNotice_id);
        
        $userSystemNotice = $this->userMessageModel->getUserSystemNotice($systemNotice_id);
        //查询通知消息详情
        $userClassNotice = $this->userMessageModel->getUserClassNotice($class_notice_id);
        
        //如果是语音
        if($userClassNotice['type_c'] == 2){
            $voice = $this->voiceModel->getVoice($userClassNotice['voice_id']);
            $userClassNotice['voice'] = $voice;
        }
        
        //查询通知消息详情
        $classNoticeReplyParentList = $this->userMessageModel->getClassNoticeReplyParentList($class_notice_id, $parent['id'], $studentId);
        
        foreach($classNoticeReplyParentList as $key => $val){
            $classNoticeReplyList = $this->userMessageModel->getClassNoticeReplyList($class_notice_id, $val['id']);
            
            $classNoticeReplyParentList[$key]['classNoticeReplyList'] = $classNoticeReplyList;
        }
        
        $userClassNotice['classNoticeReplyParentList'] = $classNoticeReplyParentList;
        
        $userNoticeImg = $this->userMessageModel->getUserNoticeImg($pic_id);
        if($userNoticeImg){
            $noticeImgList = array();
            foreach(array_filter(explode(',', $userNoticeImg['pic_ids'])) as $img_id){
                $file = $this->fileModel->getFile($img_id);
                $file_path = explode('.', $file['file_path']);
                $file['org_img_url'] = $file_path[0].'_2.jpg';
                $noticeImgList[] = $file;
            }
            $userNoticeImg['noticeImgList'] = $noticeImgList;
        }
        
        //小红花显示
        if($student_praise_id){
            $dateTime = date('Y-m', time());
            $userStudentPraisList = $this->userMessageModel->getUserStudentPraise($studentId, $dateTime);
            $userStudentPrais['prais_count'] = count($userStudentPraisList);
            $userStudentPrais['dateTime'] = $dateTime;
            $userStudentPrais['userStudentPraisList'] = $userStudentPraisList;
        }
        
        $this->view->assign('parent', $parent);
        $this->view->assign('userStudentPrais', $userStudentPrais);
        $this->view->assign('userSystemNotice', $userSystemNotice);
        $this->view->assign('userClassNotice', $userClassNotice);
        $this->view->assign('userNoticeImg', $userNoticeImg);
        $this->view->layout();
    }
    /**
     * @desc ajax消息回复
     * @author wei
     */
    function ajaxClassNoticeReplyCommentAction(){
        $wxUser = $this->getAccessWxUser();
        $user = $this->getSession(SESSION_USER);
        
        $form = $this->post('form');
        if($this->isComplete()){
           
            if ($this->userMessageModel->validSaveClassNoticeReply($form, $errors)) {
                $isParentClassNoticeReply = $this->userMessageModel->getIsParentClassNoticeReply($form['class_notice_id'], $form['reply_uid'], $form['student_id']);
                $studentParentName = $this->userMessageModel->getStudentParentName($form['reply_uid']);
                $student = $this->studentModel->getStudent($form['student_id']);
                
                $parentName = $studentParentName['parent_name'] ? $studentParentName['parent_name'] : $studentParentName['relation_title']; 
                $branchClassNoticeReply = $this->staffModel->getBranchClassNoticeReply($form['class_notice_id'], $form['reply_uid'], $form['student_id']);
                $data = array(
                    'class_notice_id' => $form['class_notice_id'],
                    'student_id' => $form['student_id'],
                    'parent_id' => $isParentClassNoticeReply ? $isParentClassNoticeReply['id'] : "0",
                    'branch_id' => $branchClassNoticeReply ? $branchClassNoticeReply['id'] : '0',
                    'reply_uid' => $form['reply_uid'],
                    'reply_utype' => ACT_PARENT_ROLE,
                    'reply_user_avatar' => './system/'.$studentParentName['relation_id'] .'.png',
                    'reply_user_name' => $student['name'].'的'.$studentParentName['relation_title'],
                    'comment_type' => $form['comment_type'],
                    'content' => $form['content'],
                    'voice_id' => $form['voice_id'] ? $form['voice_id'] : '', 
                
                );
                $classNoticeReply_id = $this->userMessageModel->saveClassNoticeReply($data);
                $parentName_s =  $studentParentName['relation_title'].$studentParentName['parent_name'];
                //生成系统通知
                $system_notice = array(
                    'class_notice_id' => $form['class_notice_id'],
                    'receive_uid' => $form['teacher_id'],
                    'receive_utype' => ACT_SCHOOL_TEACHER,
                    'send_uid' => $form['reply_uid'],
                    'send_utype' => ACT_PARENT_ROLE,
                    'send_user_avatar' => './system/'.$studentParentName['relation_id'] .'.png',
                    'send_user_name' => $parentName,
                    'is_read' => APP_UNIFIED_FALSE,
                    'is_url' => APP_UNIFIED_TRUE,
                    'url' => '?c=staff&a=noticeDetailed&class_notice_id='. $form['class_notice_id'] .'&classNoticeReply_id='.$classNoticeReply_id.'&studentId='.$student['id'],
                    'content' => $student['name'].'的<span class="color-red">'. $parentName_s .'</span>给你回复了消息，请点击查看。',
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
     * @desc ajax消息提示
     * @author wei
     */
    function ajaxMessageTishiAction(){
        $wxUser = $this->getAccessWxUser();
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        $parent = $this->userMessageModel->getParentId($user['phone']);
        if($parent){
            $userMessageTishi = $this->userMessageModel->getUserMessageTishi($parent['id'],$role);
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

}
?>
