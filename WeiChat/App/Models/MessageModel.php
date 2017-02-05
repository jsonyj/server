<?php 
class MessageModel extends BaseModel {

    function saveMessage($form) {
        $table = 'tb_message';
        
        $data = array(
            'parent_id' => $form['parent_id'],
            'student_id' => $form['student_id'],
            'type' => $form['type'],
            'content' => $form['content'],
            'data' => serialize($form['data']),
        );

        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }

    function validSaveMessage($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'parent_id' => array(
                array('isNotNull', '请输入家长ID'),
            ),
            'student_id' => array(
                array('isNotNull', '请输入学生ID'),
            ),
            'type' => array(
                array('isNotNull', '请输入消息类型'),
            ),
            'content' => array(
                array('isNotNull', '请输入消息内容'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function getMessage($id) {
        $this->escape($id);
        $sql = "
            SELECT tb_message.*, tb_student_parent.relation_id FROM tb_message
            LEFT JOIN tb_student_parent ON tb_student_parent.parent_id = tb_message.parent_id AND tb_student_parent.deleted = 0
            WHERE tb_message.id = '{$id}'
        ";

        $rs = $this->getOne($sql);
        $rs['data'] = unserialize($rs['data']);
        return $rs;
    }

    function getMessageList($studentId) {
        $this->escape($studentId);
        $sql = "
            SELECT tb_message.*, tb_student_parent.relation_id, tb_student_parent.relation_title FROM tb_message
            LEFT JOIN tb_student_parent ON tb_student_parent.parent_id = tb_message.parent_id AND tb_student_parent.deleted = 0 AND tb_student_parent.student_id = '{$studentId}'
            WHERE tb_message.student_id = '{$studentId}' AND tb_message.deleted = 0
            ORDER BY tb_message.created DESC
        ";
        
        $rs = $this->getAll($sql);
        foreach($rs as $k => $v) {
            $v['data'] = unserialize($v['data']);
            $rs[$k] = $v;
        }

        return $rs;
    }

    function deleteMessage($studentId, $messageId) {
        $this->escape($messageId);
        $this->escape($studentId);
        $data = array('deleted' => 1);
        $where = "id = '{$messageId}' AND student_id = '{$studentId}'";
        return $this->Update('tb_message', $data, $where);
    }
}


?>