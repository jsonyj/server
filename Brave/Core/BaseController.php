<?php

class BaseController extends BraveController {

    //HTTP 请求相关方法

    //发送的请求封装
    function httpPost($url, $data = NULL){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    function selectProvinceAction() {
        $baseModel = new BaseModel();

        $province = $this->get('province');

        $cities = $baseModel->getCities($province);

        $html = '';
        foreach($cities as $city) {
            $html .= "<option value=\"{$city['value']}\">{$city['name']}</option>";
        }

        echo $html;
        exit();
    }

    function selectCityAction() {
        $baseModel = new BaseModel();

        $province = $this->get('province');
        $city = $this->get('city');

        $districts = $baseModel->getDistricts($province, $city);

        $html = '';
        foreach($districts as $district) {
            $html .= "<option value=\"{$district['value']}\">{$district['name']}</option>";
        }

        echo $html;
        exit();
    }

    //微信相关方法

    /**
     * @desc 获取微信授权 Access Token
     */
    function getWXAccessToken($appID = '', $appsecret = '', $code = '') {
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appID}&secret={$appsecret}&code={$code}&grant_type=authorization_code";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        ob_start();
        curl_exec($ch);
        $token_data = ob_get_contents();
        curl_close($ch);
        ob_end_clean();

        return json_decode($token_data, true);
    }

    /**
     * 刷新 Access Token
     */
    function refreshWXAccessToken() {
        //get
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WX_APP_ID .'&secret=' . WX_APP_SECRET;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        
        $jsoninfo = json_decode($output, true);
        $access_token = $jsoninfo['access_token'];
        $expires_in = $jsoninfo['expires_in'];

        $systemModel = $this->getModel('System');

        $systemModel->saveAccessToken($jsoninfo);

        return $access_token;
    }

    //发送模板消息
    function pushWXTemplateMessage($data){
         $systemModel = $this->getModel("System");
         $accessToken = $systemModel->getAccessToken();
        
         for($i = 0; $i < 3; $i++) {//重试机制
             $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $accessToken['access_token'];
             $res = $this->httpPost($url, $data);
             $result = json_decode($res, TRUE);
             if(!isset($result['errcode']) || !in_array($result['errcode'], array('41001', '40001', '42001'))) {
                 return $result;
             } else {
                 $accessToken['access_token'] = $this->refreshWXAccessToken();
             }
         }

         return false;
    }

    function calculateGrade($start) {
        $startYear = date('Y', strtotime($start));
        $startMonth = date('n', strtotime($start));
        $startDay = date('j', strtotime($start));

        $nowYear = date('Y');
        $nowMonth = date('n');
        $nowDay = date('j');

        $grade = $nowYear - $startYear - 1;
        if($nowMonth > $startMonth && $nowDay > $startDay) {
            $grade += 1;
        }

        return $grade;
    }
}

?>
