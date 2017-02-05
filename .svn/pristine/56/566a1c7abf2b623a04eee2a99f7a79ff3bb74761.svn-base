<?php

/**
 * @desc 宝贝留言类
 * @author
 */

class MessageController extends AppController {

    var $studentModel = null;
    var $studentDetectionModel = null;
    var $articleModel = null;
    var $messageModel = null;
    var $relationModel = null;

    function MessageController() {
        $this->AppController();
        $this->studentModel = $this->getModel('Student');
        $this->studentDetectionModel = $this->getModel('StudentDetection');
        $this->articleModel = $this->getModel('Article');
        $this->messageModel = $this->getModel('Message');
        $this->relationModel = $this->getModel('Relation');
    }

    function indexAction() {
        $user = $this->getSession(SESSION_USER);

        $studentId = $this->get('id');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {

            $student['studentDetection'] = $this->studentDetectionModel->getDetection($student['id']);
            $student['studentDetectionImg'] = $this->studentModel->getStudentLatestDetectionImg($student['id']);

            $messageList = $this->messageModel->getMessageList($student['id']);
            $this->view->assign('messageList', $messageList);

            $signPackage = $this->getWeichatSignPackage();
            $this->view->assign('signPackage', $signPackage);

            $this->view->assign('student', $student);
            $this->view->layout();

        } else {

            $this->redirect('?c=user');
            exit();
        }
    }

    function downloadVoiceAction() {
        $user = $this->getSession(SESSION_USER);

        $serverId = $this->get('serverId');
        $studentId = $this->get('studentId');

        $voice = $this->weichatDownloadMedia($serverId, $user['id']);
        
        if($voice) {
            $this->log($voice);

            $form = array(
                'parent_id' => $user['id'],
                'student_id' => $studentId,
                'type' => MESSAGE_TYPE_VOICE,
                'content' => $voice['path'],
                'data' => array(
                  'serverId' => $serverId,
                )
            );

            $messageId = $this->messageModel->saveMessage($form);
            $message = $this->messageModel->getMessage($messageId);
            $this->view->assign('message', $message);

            $relationList = $this->relationModel->getRelationOptionList();
            $this->view->assign('relationList', $relationList);

            $html = $this->view->fetch('voice_item');

            print json_encode(array('code' => 0, 'html' => $html));
            exit();
        } else {
            print json_encode(array('code' => 1, 'message' => '系统繁忙，请稍后再试。'));
            exit();
        }
    }

    function saveMessageAction() {
        $user = $this->getSession(SESSION_USER);

        $form = $this->post('form');

        $form['parent_id'] = $user['id'];
        $form['type'] = MESSAGE_TYPE_TEXT;

        if ($this->messageModel->validSaveMessage($form, $errors)) {

            $messageId = $this->messageModel->saveMessage($form);
            $message = $this->messageModel->getMessage($messageId);

            $this->view->assign('message', $message);

            $relationList = $this->relationModel->getRelationOptionList();
            $this->view->assign('relationList', $relationList);

            $html = $this->view->fetch('text_item');

            print json_encode(array('code' => 0, 'html' => $html));
            exit();
        } else {
            print json_encode(array('code' => 1, 'message' => implode(';', $errors)));
            exit();
        }
    }

    function deleteAction() {
        $user = $this->getSession(SESSION_USER);

        $messageId = $this->post('messageId');
        $studentId = $this->post('studentId');

        $student = $this->studentModel->getStudentByParentId($user['id'], $studentId);

        if($student) {
            $this->messageModel->deleteMessage($studentId, $messageId);

            $return = array(
                'code' => 0,
                'message' => '',
            );
        } else {
            $return = array(
                'code' => 1,
                'message' => '系统繁忙，请稍后再试。',
            );
        }

        print json_encode($return);
        exit();
    }
}
?>
