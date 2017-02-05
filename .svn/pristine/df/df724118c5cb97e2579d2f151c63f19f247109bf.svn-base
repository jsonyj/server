<?php

class TestController extends AppController {

    function TestController() {
        $this->AppController();
    }
    
    function testConfigAction() {
        echo phpinfo();
    }

    function bindDeviceGetStudentListAction() {
        
        $url= 'http://test.didano.cn/api/student/getStudentList';
        
        $data_list = array(
            'school_id' => 4,
            'timestamp' => '20160405121212',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'DEVICE-NO:0001',
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean(); 
        //$return_content = curl_exec($ch);
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    
    function bindDeviceGetCardDeviceStudentListAction() {
        
        $url= 'http://test.didano.cn/api/cardDevice/getStudentList';
        
        $data_list = array(
            'school_id' => 4,
            'timestamp' => '20160405121212',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'DEVICE-NO:0001',
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean(); 
        //$return_content = curl_exec($ch);
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    
    function bindDeviceBindParentRfidAction() {
        
        $url= 'http://www.xiaonuo.local/api/bindDevice/bindParentRfid';
        
        $data_list = array(
            'parent_id' => 313,
            'student_id' => 450,
            'rfid' => 'bbbb',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);        

        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    
    function getbindDeviceStudentListAction() {
        
        $url= 'http://test.didano.cn/api/bindDevice/getStudentList';
        
        $data_list = array(
            'school_id' => 4,
            'timestamp' => '20160405121212',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'DEVICE-NO:0001',
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    function cardDeviceGetSchoolKeyAction() {
        
        $url= 'http://www.xiaonuo.local/api/cardDevice/getSchoolKey';
        
        $data_list = array(
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'DEVICE-NO:0001',
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print$return_content;
    }

function studentPutStudentDetectionAction() {

        $url= 'http://www.xiaonuo.local/api/student/putStudentDetection';

        $data_list = array(
            'id' => 482,
            'height' => '55',
            'weight' => '23',
            'temperature' => '32',
            'env_temperature' => '23.0',
            'raw_temperature' => '22',
            'temperature_threshold' => '11',
            'img_id' => '62',
            'img' => '@D:/xampp/htdocs/xiaonuojiqi/server/Resource/system/1.jpg',
        );
        //;type=application/octet-stream
        $header_list = array(
            'DEVICE-NO:0001',
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_list);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch); 
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    function bindDeviceGetStaffListAction() {
        
        $url= 'http://www.xiaonuo.local/api/bindDevice/getStaffList';
        
        $data_list = array(
            'school_id' => 4,
            'timestamp' => '20160405121212',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean(); 
        //$return_content = curl_exec($ch);
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    
    function bindDeviceBindStaffRfidAction() {
        
        $url= 'http://www.xiaonuo.local/api/bindDevice/bindStaffRfid';
        
        $data_list = array(
            'id' => 4,
            'rfid' => 'AAAAAAA',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean(); 
        //$return_content = curl_exec($ch);
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    
    function cardDeviceGetStaffListAction() {
        
        $url= 'http://www.xiaonuo.local/api/cardDevice/getStaffList';
        
        $data_list = array(
            'timestamp' => '20160405121212',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'DEVICE-NO:0001',
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print$return_content;
    }
    
    function cardDeviceSubmitStaffSignAction() {
        
        $url= 'http://www.xiaonuo.local/api/cardDevice/submitStaffSign';
        
        $data_list = array(
            'id' => 1,
            'img' => "@D:/WORKP/mitan/sourceV2/Resource/upload/star/171/31fbe94f36d26d4312df4fad9eacb55e.gif",
        );
        
        $header_list = array(
            'DEVICE-NO:0001',
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_list);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print$return_content;
    }
    
    function bindDeviceGetStudentClaimDataAction() {
        
        $url= 'http://www.xiaonuo.local/api/student/getStudentClaimData';
        
        $data_list = array(
            'school_id' => 4,
            'timestamp' => '20160405121212',
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean(); 
        //$return_content = curl_exec($ch);
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    
    function bindSubmitStudentMessageAction() {
        
        $url= 'http://www.xiaonuo.local/api/service/submitStudentMessage';
        
        $data_list = array(
            'app_id' => '1',
            'key' => 'MVPL7iPF4vlz/VRiIsS9wA==',
            'student_id' => '439',
            'content' => 'yayayayayayaya',
            'url' => 'www.baidu.com', 
        );
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  
        ob_start();  
        curl_exec($ch);
        $return_content = ob_get_contents();  
        ob_end_clean(); 
        //$return_content = curl_exec($ch);
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');        
        print $return_content;
    }
    function putDetectionClaimAction() {

        $url= 'http://test.didano.cn/api/student/putDetectionClaim';

        $data_list = array(
            'height' => '187',
            'weight' => '60',
            'temperature' => '22.2',
            'env_temperature' => '22.2',
            'raw_temperature' => '22.2',
            'temperature_threshold' => '22',
            'img_id' => '222',
            'img' => '@D:/xampp/htdocs/xiaonuojiqi/server/Resource/system/1.jpg',
        );
        $header_list = array(
            'DEVICE-NO:0001',
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_list);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    function frontDevicegetClassListAction() {

        $url= 'http://www.xiaonuo.local/api/frontDevice/getClassList';

        $data_list = array(
            'school_id' => 4,
        );
        $header_list = array(
        );
        
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    
    function frontDeviceAddStudentAction() {

        $url= 'http://www.xiaonuo.local/api/frontDevice/addStudent';

        $data_list = array(
            'school_id' => 4,
            'class_id' => 18,
            'name' => '测试员2',
            'gender' => 1,
            'birthday' => '20160105',
        );
        $header_list = array(
        );
        
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    
    function frontDeviceAddStudentParentsAction() {

        $url= 'http://www.xiaonuo.local/api/frontDevice/addStudentParents';

        $data_list = array(
            'student_id' => 498,
            'parent_list' => '1,15208330276|3,15454458558',
        );
        $header_list = array(
        );
        
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    function putHandheldImgAction() {

        $url= 'http://www.didano.cn/api/student/putHandheldImg';

        $data_list = array(
            'detection_id' => '187',
            'img' => '@/projects/xiaonuo/Resource/system/99.png',
        );
        $header_list = array(
            'DEVICE-NO:0001',
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_list);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print$return_content;
    }
    
    function putStudentRegisteredAction() {

        $url= 'http://www.didano.cn/api/student/putStudentRegistered';

        $data_list = array(
            'student_id' => '853',
        );
        $header_list = array(
        );
        
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    function addParentAction() {

        $url= 'http://www.xiaonuo.local/api/frontDevice/addParent';

        $data_list = array(
            'student_id' => '344',
            'phone' => '18628137635',
            'relation' => '99',
            'relation_title' => '',
        );
        $header_list = array(
        );
        
        $data_json = json_encode($data_list);
        
        $header_list = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_json)
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }
    
    function cardDeviceSubmitTakeAwayAction() {

        $url= 'http://www.xiaonuo.local/api/cardDevice/submitTakeAway';

        $data_list = array(
            'student_id' => '526',
            'parent_id' => '515',
            'img[main]' => '@C:\Users\Public\Pictures\Sample Pictures\test\1.jpg',
            //'img[sub]' => '@C:\Users\Public\Pictures\Sample Pictures\test\2.jpg',
            'create_time' => '20161021090909',
        );
        $header_list = array(
            'DEVICE-NO:0001',
        );
        
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_list);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list); 
        
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();
        curl_close($ch);
        
        header('content-type:application/json; charset=utf-8');  
        print $return_content;
    }    
}

?>
