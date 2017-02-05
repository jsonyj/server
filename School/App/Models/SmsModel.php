<?php 
class SmsModel extends BaseModel {

    function saveSms($form) {
        $table = 'tb_sms';
        
        $data = array(
            'phone' => $form['phone'],
            'message' => $form['message'],
        );

        if(isset($form['status'])) {
            $data['status'] = $form['status'];
        }

        if(isset($form['status'])) {
            $data['result'] = $form['result'];
        }

        if(isset($form['data'])) {
            $data['data'] = $form['data'];
        }

        if ($form['id']) {
            $where = "id = '{$form['id']}'";
            $this->Update($table, $data, $where);
            return $form['id'];
        } else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    
}


?>