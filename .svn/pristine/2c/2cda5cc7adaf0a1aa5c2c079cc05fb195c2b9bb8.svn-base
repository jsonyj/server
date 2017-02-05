<?php

class BaseModel extends BraveModel {
    
    function sendSMS($config, $sms){
        
        header("Content-Type: text/html; charset=UTF-8");

        $flag = 0; 
        $params='';
        
        $argv = array( 
            'sn' => $config['sn'], //注册序列号
            'pwd' => strtoupper(md5($config['sn'] . $config['pwd'])),
            'mobile' => $sms['phone_list'],
            'content'=> $sms['message'],
            'ext' => '',
            'stime' => '',
            'msgfmt' => '',
            'rrid' => ''
        ); 
        
        foreach ($argv as $key=>$value) { 
            if ($flag != 0) { 
                $params .= "&"; 
                $flag = 1; 
            } 
            $params .= $key . "="; 
            $params .= urlencode($value);
            $flag = 1; 
        } 
        $length = strlen($params); 
        //创建socket连接 
        $fp = fsockopen("sdk.entinfo.cn", 8061, $errno, $errstr, 10) or exit($errstr . "--->" . $errno); 
        //构造post请求的头 
        $header = "POST /webservice.asmx/mdsmssend HTTP/1.1\r\n"; 
        $header .= "Host:sdk.entinfo.cn\r\n"; 
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
        $header .= "Content-Length: ".$length."\r\n"; 
        $header .= "Connection: Close\r\n\r\n"; 
        //添加post的字符串 
        $header .= $params."\r\n"; 
        
        //发送post的数据
        fputs($fp, $header); 
        $inheader = 1; 
        while (!feof($fp)) { 
            $line = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据 
            if ($inheader && ($line == "\n" || $line == "\r\n")) { 
                $inheader = 0; 
            } 
            if ($inheader == 0) { 
                // echo $line; 
            } 
        } 
        
        $line_str = str_replace('<string xmlns="http://entinfo.cn/">', "", $line);
        $line_str = str_replace('</string>', "", $line_str);
        $line_arr = explode("-", $line_str);
        $result['data'] = $line;
        
        if (count($line_arr) > 1) {
            $result['status'] = SMS_SEND_FAIL;
            $this->saveSentUserSMS($sms, $result);
            return false;
        }
        else {
            $result['status'] = SMS_SEND_SUCCESS;
            $result['rrid'] = $line_str;
            $this->saveSentUserSMS($sms, $result);
            return true;  
        }
    }
    
    function saveSentUserSMS($sms, $result) {
        $table = 'tb_user_sms';
        
        $data = array(
            'phone_list' => $sms['phone_list'],
            'message' => $sms['message'],
            'status' => $result['status'],
            'rrid' => $result['rrid'] ? $result['rrid'] : '',
            'data' => serialize($result['data']),
        );

        if ($sms['id'] > 0) {
            $where = "id = '{$sms['id']}'";
            $this->Update($table, $data, $where);
            return $sms['id'];
        } else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    function makeParameter($data) {
        $parameter = '?';
        
        foreach ($data as $k => $v) {
            $parameter.= "{$k}={$v}&";
        }
        
        return substr($parameter, 0, -1);
    }
    
    function getProvinces() {
        $sql = "SELECT name AS value, name AS name FROM tb_province ORDER BY id ASC";
        return $this->getAll($sql);
    }

    function getCities($province_name) {
        $sql = "SELECT name AS value, name AS name FROM tb_city WHERE pid IN (SELECT id FROM tb_province WHERE name = '{$province_name}' ) ORDER BY id ASC";
        return $this->getAll($sql);
    }

    function getDistricts($province_name, $city_name) {
        $sql = "SELECT name AS value, name AS name FROM tb_district WHERE cid IN (SELECT id FROM tb_city WHERE name = '{$city_name}' ) AND pid = (SELECT id FROM tb_province WHERE name = '{$province_name}' ) ORDER BY id ASC";
        return $this->getAll($sql);
    }
}

?>
