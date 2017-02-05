<?php

class AppController extends BaseController {

    function AppController() {
        $this->view = new AppView();
        $studentModel = $this->getModel('Student');
        $this->setCode();
        $this->referer = $this->server('HTTP_REFERER');
        $user = $this->getSession(SESSION_USER);
        $user = $this->getSession(SESSION_USER);
        $student_list = $studentModel->getStudentListByParentPhone($user['phone']);
        $parentMessageIS = $studentModel->getUserMessageIS($user['id']);
        $is_read = "";
        foreach($student_list as  $val){
            $studentMessageIS = $studentModel->getUserMessageIS($val['id']);
            if($studentMessageIS || $parentMessageIS){
                $is_read = 1;
            }
        }
        $this->view->assign('is_read', $is_read);
        $this->view->assign('referer', $this->referer);
        $this->view->assign('user', $user);
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

    function httpsGet($url) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_TIMEOUT, 500);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_URL, $url);

      $res = curl_exec($curl);
      curl_close($curl);

      return $res;
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

    /**
     * @desc 获取授权后的微信用户信息
     */
    function getWXUserInfo($access_token = '', $open_id = '') {
        $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
        
        $info_data = $this->getRequest($info_url);
        
        return json_decode($info_data, true);
    }

    function encSharedKey($user_id, $group_id, $time = 0) {
        if($time == 0) {
            $time = time();
        }
        $shared_key = $time . "|{$user_id}|{$group_id}";
        $shared_key = base64_encode($this->aes128Encrypt($shared_key, $this->code('shared_key.key'), base64_decode($this->code('shared_key.iv'))));
        return $shared_key;
    }

    function decSharedKey($shared_key) {
        $dec = $this->aes128Decrypt(base64_decode($shared_key), $this->code('shared_key.key'), base64_decode($this->code('shared_key.iv')));

        $dec = explode('|', $dec);

        if(count($dec) != 3) {
            return false;
        } else {
            return array(
                'time' => $dec[0],
                'user_id' => $dec[1],
                'group_id' => $dec[2],
              );
        }
    }

    function getWeichatUserInfo($accessToken, $openId) {
        //get
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accessToken}&openid={$openId}&lang=zh_CN";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $resp = json_decode($output, true);
        return $resp;
    }

    function isWeichatUserSubscribe($openId) {
        $systemModel = $this->getModel('System');

        $accessToken = $systemModel->getAccessToken();

        $resp = array();
        for($i = 0; $i < 3; $i++) {//重试机制
            $resp = $this->getWeichatUserInfo($accessToken['access_token'], $openId);
            if(!isset($resp['errcode']) || !in_array($resp['errcode'], array('41001', '40001', '42001'))) {
                break;
            } else {
                $accessToken['access_token'] = $this->refreshWXAccessToken();
            }
        }

        return $resp['subscribe'];
    }

    public function getWeichatSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        $jsapiTicket = $jsapiTicket['ticket'];

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
          "appId"     => WX_APP_ID,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );

        return $signPackage; 
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    function getJsApiTicket() {
        $systemModel = $this->getModel('System');

        // jsapi_ticket 应该全局存储与更新，写入数据库
        $data = $systemModel->getJsapiTicket();
        if($data && isset($data['expires_in']) && time() < $data['expires_in']) {
            return $data;
        } else {
            $ticket = $this->refreshJsApiTicket();
            $ticket['expires_in'] = time() + $ticket['expires_in'];
            $systemModel->saveJsapiTicket($ticket);
            return $ticket;
        }
    }

    function refreshJsApiTicket() {

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
                $accessToken['access_token'] = $this->refreshWXAccessToken();
            }
        }

        return $resp;
    }

    function weichatDownloadMedia($serverId, $userId) {
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

        $path = APP_RESOURCE_ROOT . 'upload'. DS . 'user' . DS . $userId . DS . $name . '.' . $contentType;

        $system = $this->load(EXTEND, 'BraveSystem');
        $system->mkdirs(dirname($path));

        $local_file = fopen($path, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $media['body'])) {
                fclose($local_file);

                return array(
                      'name' => $name . '.' . $contentType,
                      'path' => '/upload/user/' . $userId . '/' . $name . '.' . $contentType,
                      'size' => $media['header']['size_download'],
                      'mime' => $media['header']['content_type'],
                  );
            }
        }

        return false;
    }

    function wxPay($open_id, $order_title, $out_trade_no, $total_fee) {
        include_once(LIBRARY . 'WxPay/WxPayPubHelper.php');

        //返回微信支付数据
        $wx_app_id = WX_APP_ID;
        $wx_app_secret = WX_APP_SECRET;
        $wx_mchid = WX_MCHID;
        $wx_key = WX_KEY;
        $wx_notify_url = WX_NOTIFY_URL;

        $total_fee = $total_fee * 100;

        //设置统一支付接口参数
        $unifiedOrder = new UnifiedOrder_pub($wx_app_id, $wx_app_secret, $wx_mchid, $wx_key, WXPAY_CERT, WXPAY_KEY_CERT);
        $unifiedOrder->setParameter("openid", $open_id);
        $unifiedOrder->setParameter("body", $order_title);//商品描述
        $unifiedOrder->setParameter("notify_url", $wx_notify_url);//通知地址 
        $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no);
        $unifiedOrder->setParameter("total_fee", $total_fee);//总金额

        //获取欲支付ID
        $prepay_id = $unifiedOrder->getPrepayId();

        //使用jsapi接口
        $jsApi = new JsApi_pub($wx_app_id, $wx_app_secret, $wx_mchid, $wx_key, WXPAY_CERT, WXPAY_KEY_CERT);

        //设置prepay_id
        $jsApi->setPrepayId($prepay_id);

        //返回参数json数据
        $jsApi->getParameters();
        $jsApiParameters = $jsApi->getJsApiObj();

        return $jsApiParameters;
    }

    function checkUserAgent($type=NULL) {
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        if ( $type == 'bot' ) {
                // matches popular bots
                if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
                        return true;
                        // watchmouse|pingdom\.com are "uptime services"
                }
        } else if ( $type == 'browser' ) {
                // matches core browser types
                if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
                        return true;
                }
        } else if ( $type == 'mobile' ) {
                // matches popular mobile devices that have small screens and/or touch inputs
                // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
                // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
                if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
                        // these are the most common
                        return true;
                } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
                        // these are less common, and might not be worth checking
                        return true;
                }
        }
        return false;
    }

    function g2bAction() {
        $lat = $this->get('lat');
        $lng = $this->get('lng');

        print json_encode($this->Convert_GCJ02_To_BD09($lat, $lng));
        exit();
    }

    /**
    * 中国正常GCJ02坐标---->百度地图BD09坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    */
    function Convert_GCJ02_To_BD09($lat, $lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
    }

    /**
    * 百度地图BD09坐标---->中国正常GCJ02坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    * @return array();
    */
    function Convert_BD09_To_GCJ02($lat, $lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
    }

    function getWxAuthorizeUrl() {
        return WX_APP_DEBUG ?
                WX_APP_URL . '?c=authorize&appid=' . WX_APP_ID . '&redirect_uri=' . urlencode(WX_AUTHORIZE_CALLBACK_URI) . '&response_type=code&scope=snsapi_userinfo&state=' . WX_STATE . '#wechat_redirect' : 
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APP_ID . '&redirect_uri=' . urlencode(WX_AUTHORIZE_CALLBACK_URI) . '&response_type=code&scope=snsapi_userinfo&state=' . WX_STATE . '#wechat_redirect';
    }

    function getAccessWxUser($needAuth=true) {
        $wxUser = $this->getSession(SESSION_WX_USER);
        if($wxUser) {//需要先进行微信认证
            return $wxUser;
        } else if($needAuth) {
            $uri = $this->server('REQUEST_URI');
            $this->setSession('act_referer', $uri);
            $this->redirect($this->getWxAuthorizeUrl());
            exit();
        } else {
            return array();
        }
    }
    
    function validAccessLoginUser($url) {
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        if ($user && $role) {
            switch($role) {
                case ACT_SCHOOL_HEADMASTER:
                    //no break
                case ACT_SCHOOL_TEACHER:
                    //no break
                case ACT_SCHOOL_DOCTOR:
                    //no break
                case ACT_SCHOOL_SUPPORTER:
                    
                    $staffModel = $this->getModel('Staff');
                    if (!$staff = $staffModel->getStaff($user['id'], $role)) {
                        $this->unsetSession(SESSION_USER);
                        $this->unsetSession(SESSION_ROLE);
                        $this->redirect($url);
                        exit();
                    }
                    break;
                
                case ACT_PARENT_ROLE:
                    $parentModel = $this->getModel('Parent');
                    if (!$parent = $parentModel->getParent($user['id'])) {
                        $this->unsetSession(SESSION_USER);
                        $this->unsetSession(SESSION_ROLE);
                        $this->redirect($url);
                        exit();
                    }
                    break;
                    
                default:
                    break;
                    
            }
        }
        else {
            if ($user) $this->unsetSession(SESSION_USER);
            if ($role) $this->unsetSession(SESSION_ROLE);
            $this->redirect($url);
            exit();
        }
        
    }
    
    /** 
    *  压缩图片 desription
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
    /** 
    * desription 通知压缩图片 
    * @ sting $imgsrc 图片路径 
    */
    function gdImgCompressStaff(){
        if(file_exists($imgsrc)){
            list($width,$height,$type)=getimagesize($imgsrc); 
            $imgSize= ceil(filesize($imgsrc)/1024);
            if($imgSize > 200){// 小于200k 的都不处理
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
                if($type != 2){
                    $filename = substr($imgsrc,0,strripos($imgsrc,'.'));
                    $src = $filename . '.jpg';
                }else{
                    $src = $imgsrc;
                }

                $image_wp=imagecreatetruecolor($new_width, $new_height);

                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
                imagejpeg($image_wp, $src,80); 

                imagedestroy($image_wp);
                imagedestroy($src);
                if($type != 2){
                  unlink($imgsrc);
                }
            }
              
        }else{
            return json_encode(array('errorno' => 2, 'message' => '图片地址出错！'));
        }
    }
    function isMobile(){ 
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return 'phone';
        } 
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? 'phone' : 'pc';
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
                ); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return 'phone';
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return 'phone';
            } 
        } 
        return 'pc';
    } 

}

?>
