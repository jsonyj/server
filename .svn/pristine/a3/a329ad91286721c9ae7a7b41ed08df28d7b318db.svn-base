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
    
    function updateSMS($id, $form) {
        $table = 'tb_sms';
        $data = array(
            'status' => $form['status'],
        );

        if(isset($form['result'])) {
            $data['result'] = $form['result'];
        }

        if(isset($form['data'])) {
            $data['data'] = $form['data'];
        }

        $this->escape($id);
        $where = "id = '{$id}'";
        $this->Update($table, $data, $where);
        
    }
    
    function sendMessage($sms) {

        $config = $this->code('sms');

        $url = "{$config['url']}?method=Submit&account={$config['account']}&password={$config['password']}&mobile={$sms['phone']}&content={$sms['message']}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        ob_start();
        curl_exec($ch);
        $rs = ob_get_contents();
        curl_close($ch);
        ob_end_clean();

        $xml = simplexml_load_string($rs);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        $array['xml'] = $rs;

        return $array;
    }
    
    function getSmsList($limit=20){
        $this->escape($status);
        $sql = "SELECT * FROM tb_sms WHERE deleted = 0 AND status = 0 ORDER BY created ASC LIMIT {$limit} ";
        
        $rs = $this->getAll($sql);
        
        return $rs;
    }
    
    function deleteSms($phone){
        $this->escape($phone);
        
        $table = 'tb_sms';
        $sql = "UPDATE {$table} SET deleted = 1 WHERE deleted = 0 AND phone = '{$phone}'";
        
        return $this->Execute($sql);
    }
    
}


?>