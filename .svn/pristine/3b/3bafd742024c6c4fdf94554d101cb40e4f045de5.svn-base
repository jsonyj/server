<?php

class AppController extends BaseController {

    function AppController() {
      $this->view = new AppView();
      $this->setCode();
    }

    function createToken() {
      $rand = mt_rand();
      $code = strtoupper(md5(uniqid($rand, true)));
      
      $token = substr($code, 0, 8) . '-';
      $token.= substr($code, 8, 4) . '-';
      $token.= substr($code, 12, 4) . '-';
      $token.= substr($code, 16, 4) . '-';
      $token.= substr($code, 20, 12);
      return $token;
    }
    
    //拦截登录
    function getAccessUser($access_deny=true){
        $tokenModel = $this->getModel('Token');

        $user = $tokenModel->getAccessUser($this->getToken());

        if($access_deny && !$user){
             $return = array(
               'code' => 1,
               'message' => '无操作权限，请登录。'
             );
             echo $this->json_encode($return);
             exit;
        }

        return $user;
    }

    function getToken() {
        $token = isset($_SERVER['HTTP_TOKEN']) && $_SERVER['HTTP_TOKEN'] ? $_SERVER['HTTP_TOKEN'] : '';
        if(!$token && isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
            preg_match('/token:([a-z0-9\-]{36})/i', $_SERVER['HTTP_USER_AGENT'], $matchs);
            $token = isset($matchs[1]) ? $matchs[1] : '';
        }
        return $token;
    }

    function getAccessToken($access_deny=false, $user_type=0) {
        $token = isset($_SERVER['HTTP_TOKEN']) && $_SERVER['HTTP_TOKEN'] ? $_SERVER['HTTP_TOKEN'] : '';

        if(!$token && isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
            preg_match('/token:([a-z0-9\-]{36})/i', $_SERVER['HTTP_USER_AGENT'], $matchs);
        
            $token = isset($matchs[1]) ? $matchs[1] : '';
        }

        $user_token = array();
        if($token) {
            $user_token = $this->getModel('User')->getToken($token, $user_type);
        }

        if(empty($user_token)) {
            if($access_deny) {
                print $this->json_encode(array('success' => false, 'message' => 'Access deny', 'error_code' => 1));
                exit();
            } else {
                return array('uid' => 0, 'user_type' => 0);
            }
        } else {
            return $user_token;
        }
    }

    function setCode($key = null) {
        $code = $this->code($key);
        $lang = $this->lang();
        $this->view->assign('code', $code);
    }
    
    function headerRequest($url, $data = null) {
        $rs = '';
        
        if (preg_match('/^http:/i', $url)) {
            $header = get_headers($url);
            is_array($header) and $rs = implode(PHP_EOL, $header);
            return $rs;
        }
        else {
            $rs = parse_url($url);
            $url = "{$rs['scheme']}://{$rs['host']}{$rs['path']}";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $rs = curl_exec($ch);
            curl_close($ch);
            return $rs;
        }
    }
    
    function getRequest($url, $data = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        ob_start();
        curl_exec($ch);
        $rs = ob_get_contents();
        curl_close($ch);
        ob_end_clean();
        return $rs;
    }
    
    function postRequest($url, $data = null) {
        $search = array();
        
        foreach($data as $k => $v){
            $search[] = "{$k}=".urlencode($v);
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded; charset=UTF-8"));
        curl_setopt($ch, CURLOPT_COOKIEJAR, APP_WEBROOT . $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, APP_WEBROOT . $this->cookie);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$search));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    function makeParameter($data) {
        $parameter = '?';
        
        foreach ($data as $k => $v) {
            $parameter.= "{$k}={$v}&";
        }
        
        return substr($parameter, 0, -1);
    }
    
    function codeEncrypt($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->codeEncrypt($v);
            }
        }
        else if (strlen($data) >= 12) {
            $rs = '';
            $seed = ord($data[12]) % 10;
            
            for ($i=1; $i<12; $i++) {
                $c = $data[$i];
                
                if ($c >= 0 && $c <= 9) {
                    $rs.= chr(($c + $seed) % 10 + ord('0'));
                }
            }
            
            if (strlen($data) > 12) {
                $rs.= substr($data, 12);
            }
            
            $data = $rs;
        }
        
        return $data;
    }
    
    function codeDecrypt($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->codeDecrypt($v);
            }
        }
        else if (strlen($data) >= 12) {
            $rs = $this->codePrefix;
            $seed = ord($data[11]) % 10;
            
            for ($i=0; $i<11; $i++) {
                $c = $data[$i];
                
                if ($c >= 0 && $c <= 9) {
                    $rs.= chr((($c - $seed + 10) % 10) + ord('0'));
                }
            }
            
            if (strlen($data) > 11) {
                $rs.= substr($data, 11);
            }
            
            $data = $rs;
        }

        return $data;
    }
    
    function mbTrans($data, $encode = 'utf-8') {
        $codelist = 'utf-8,gbk';
        return mb_convert_encoding($data, $encode, $codelist);
    }
    
    function mbTransFile($file, $encode = 'utf-8') {
        $content = file_get_contents($file);
        $content = $this->mbTrans($content);
        
        if (!is_writable($file)) {
            $file.= $encode;
        }
        
        file_put_contents($file, $content);
        return $file;
    }
    
    function cleanContent($data) {
        $data = $this->mbTrans($data);
        $data = trim($data);
        $data = strip_tags($data);
        $data = preg_replace("/[\r\n]+/", ' ', $data);
        $data = preg_replace('/[\x00-\x08\x0b-\x0c\x0e-\x1f\x7f]/', '', $data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function json_encode($data) {

        $token = isset($_SERVER['HTTP_DEVICE_NO']) && $_SERVER['HTTP_DEVICE_NO'] ? $_SERVER['HTTP_DEVICE_NO'] : '';

        if(!$token && isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
            preg_match('/device_no:([a-z0-9\-]{36})/i', $_SERVER['HTTP_USER_AGENT'], $matchs);
        
            $token = isset($matchs[1]) ? $matchs[1] : '';
        }

        $user = array();
        if($token) {
            //$user = $this->getModel('User')->getToken($token);
        }

        $this->log("------------------------BEGIN:{$_SERVER['REQUEST_URI']}----------------------------------------");

        $this->log('HEADER:' . print_r($_SERVER, true));

        $this->log('DEVICE_NO:' . $token);

        $this->log('POST:' . print_r($_POST, true));

        $this->log('DATA:' . print_r($data, true));

        $json = json_encode($data);

        $this->log('JOSN:' . $json);

        $this->log("------------------------END:{$_SERVER['REQUEST_URI']}----------------------------------------");

        return $json;
    }

    function getPush() {
        $token = isset($_SERVER['HTTP_PUSH']) && $_SERVER['HTTP_PUSH'] ? $_SERVER['HTTP_PUSH'] : '';
        return $token;
  }

    function getSaleList($notEnda) {
        foreach($notEnda AS $v) {
            $can_apply = $v['limit_count']-$v['totalDraw'];
            if($can_apply > 0 AND $v['remain_count'] > $can_apply ) {
                $notEnd[] = array(
                    'id'=> $v['id'],
                    'title'=> $v['title'],
                    'description' => $v['description'],
                    'limit_count' => $v['limit_count'],
                    'remain_count' => $v['remain_count'],
                    'start' => $v['start'],
                    'end' => $v['end'],
                    'can_apply' => $can_apply, //TODO
                    'category' => array(
                        'id' => $v['lid'],
                        'title' => $v['name'],
                        'icon' => APP_RESOURCE_URL.$v['icon']
                    )
                );
            }
            elseif($can_apply > 0 AND $v['remain_count'] < $can_apply) {
                $notEnd[] = array(
                    'id'=> $v['id'],
                    'title'=> $v['title'],
                    'description' => $v['description'],
                    'limit_count' => $v['limit_count'],
                    'remain_count' => $v['remain_count'],
                    'start' => $v['start'],
                    'end' => $v['end'],
                    'can_apply' => $v['remain_count'], //TODO
                    'category' => array(
                        'id' => $v['lid'],
                        'title' => $v['name'],
                        'icon' => APP_RESOURCE_URL.$v['icon']
                    )
                );
            }
        }
        return $notEnd;
    }

    function hasNoApply($enda) {
        foreach($enda AS $v) {
            $end[] = array(
                'id'=> $v['id'],
                'title'=> $v['title'],
                'description' => $v['description'],
                'limit_count' => $v['limit_count'],
                'remain_count' => $v['remain_count'],
                'start' => $v['start'],
                'end' => $v['end'],
                'can_apply' => 0, //TODO
                'category' => array(
                    'id' => $v['lid'],
                    'title' => $v['name'],
                    'icon' => APP_RESOURCE_URL.$v['icon']
                )
            );
        }
        return $end;
    }

    function getAppType() {
        $app_type = isset($_SERVER['HTTP_APP_TYPE']) && $_SERVER['HTTP_APP_TYPE'] ? $_SERVER['HTTP_APP_TYPE'] : '';
        return $app_type;
    }

    function rawData2Arr($input = null) {
        if(!$input) {
            $input = file_get_contents("php://input");
        }
        
        if (preg_match('/^[\[|\{](.*?)[\]|\}]$/s', trim($input))) {
            $put = json_decode($input, true);
        } else {
            parse_str($input, $put);
        }

        return $put;
    }

    function point2point($longitudeFrom, $latitudeFrom, $longitudeTo, $latitudeTo, $earthRadius = 6371) {
      // convert from degrees to radians
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);

      $latDelta = $latTo - $latFrom;
      $lonDelta = $lonTo - $lonFrom;

      $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
      return $angle * $earthRadius;
    }

    function getAccessDevice($accessDeny=true) {
        $token = isset($_SERVER['HTTP_DEVICE_NO']) && $_SERVER['HTTP_DEVICE_NO'] ? $_SERVER['HTTP_DEVICE_NO'] : '';

        if(!$token && isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
            preg_match('/device_no:([a-z0-9\-]{36})/i', $_SERVER['HTTP_USER_AGENT'], $matchs);
        
            $token = isset($matchs[1]) ? $matchs[1] : '';
        }

        $device = array();
        if($token) {
            $device = $this->getModel('Device')->getDevice($token);
        }

        if(empty($device)) {
            if($accessDeny) {
                print $this->json_encode(array('code' => 1, 'message' => 'Access deny'));
                exit();
            } else {
                return array();
            }
        } else {
            return $device;
        }
    }
    /** 
    * desription 压缩图片 
    * @ sting $imgsrc 图片路径 
    */
    function gdImgCompress($imgsrc){ 
        if(file_exists($imgsrc)){
            list($width,$height,$type)=getimagesize($imgsrc); 
            $imgSize= ceil(filesize($imgsrc)/1024);
                if($type == 2){  //当是jpg 图片的时候
                    if($width > 1500){
                       $new_width =  $width/2; 
                       $new_height = $height/2; 
                    }else if($width > 1000){
                       $new_width =  $width*0.6; 
                       $new_height = $height*0.6; 
                    }else{
                       $new_width =  $width; 
                       $new_height = $height; 
                    }
                }else{
                    $new_width = $width;
                    $new_height = $height;
                }
                  switch ($type) {
                      case 1:
                          $image =imagecreatefromgif($imgsrc);
                          break;
                          
                      case 2:
                          $image =imagecreatefromjpeg($imgsrc);
                          break;
                          
                      case 3:
                          $image =imagecreatefrompng($imgsrc);
                          break;
                          
                      case 6:
                          $image =imagecreatefromwbmp($imgsrc);
                          break;
                          
                      default:
                          print json_encode(array('errorno' => 1, 'message' => '不支持的文件类型'));
                          exit;
                          
                  }
                  $file_path = explode('.', $imgsrc);
                  $file_path2 = $file_path[0] . "_thumb." . $file_path[1];
                  if($type != 2){
                      $filename = substr($file_path2,0,strripos($file_path2,'.'));
                      $src = $filename . '.jpg';
                  }else{
                      $src = $file_path2;
                  }

                  $image_wp=imagecreatetruecolor($new_width, $new_height);
                  
                  imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
                  imagejpeg($image_wp, $src,80); 

                  imagedestroy($image_wp);
                  imagedestroy($src);
                  if($type != 2){
                      //unlink($imgsrc);
                  }
              
              
         }else{
              return json_encode(array('errorno' => 2, 'message' => '图片地址出错！'));
         }

    } 
    
    function thumburl($imgsrc){
        list($width,$height,$type)=getimagesize($imgsrc); 
            $imgSize= ceil(filesize($imgsrc)/1024);
                if($type == 2){  //当是jpg 图片的时候
                    if($width > 1500){
                       $new_width =  $width/2; 
                       $new_height = $height/2; 
                    }else if($width > 1000){
                       $new_width =  $width*0.6; 
                       $new_height = $height*0.6; 
                    }else{
                       $new_width =  $width; 
                       $new_height = $height; 
                    }
                }else{
                    $new_width = $width;
                    $new_height = $height;
                }
        return '?x-oss-process=image/resize,m_lfit,h_' . $new_height . ',w_' . $new_width;
    }
    
}

?>
