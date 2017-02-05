<?php

class AppController extends BaseController {

    function AppController() {
        $this->view = new AppView();
        $this->setCode();
        $this->view->assign('user',$this->getSession(SESSION_USER));
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
        
    function saveFiles($dir,$rid,$files,$utype,$stype){ 
    
        $upfile = $_FILES[$files];  
        $count = count($upfile);

        for($i=0;$i<=$count;$i++){
            $okType=false;
            
            $lookName = uniqid();
            $name = $upfile["name"][$i];
            $type = $upfile["type"][$i];
            $size = $upfile["size"][$i];
            $tmp_name = $upfile["tmp_name"][$i];
            $ext = substr(strrchr($name, '.'), 1);  
            $uploaddir = $dir.$rid.'/';
            if (!file_exists($uploaddir)) $this->mkdirs($uploaddir);  
            $destination = $uploaddir.$lookName.'.'.$ext;  
            switch ($type){ 
                case 'image/pjpeg':$okType=true; 
                break; 
                case 'image/jpeg':$okType=true; 
                break; 
                case 'image/gif':$okType=true; 
                break; 
                case 'image/png':$okType=true; 
                break; 
            } 
            
            if($okType){
                if(move_uploaded_file($tmp_name,$destination)){
                    $file=array();
                    $file['file_name'] = $lookName.".".$ext;
                    $file['file_path'] = $destination;
                    $file['file_mime'] = $type;
                    $file['file_size'] = $size;
                    $fid = $this->FileModel->saveFile($file);
                    $this->FileModel->insertFileUsage($fid,$rid,$utype,$stype);
                } 
            }
        }   
        return $fid;    
    }

    function getFileAction() {
        $id = $this->get('id');
        $type = $this->get('type');

        $fileList = $this->fileModel->getFileList($id, $type);

        $files = array();
        foreach($fileList as $file) {
          $files[] = array('id' => $file['id'], 'name' => '', 'size' => $file['file_size'], 'url' => APP_RESOURCE_URL . $file['file_path']);
        }

        print json_encode($files);
        exit();
    }

    function deleteFileAction() {
        $id = $this->get('id');

        $this->fileModel->deleteFile($id);

        print json_encode(array('success' => true));
        exit();
    }
    /**
    * @desc 微信二维码生成
    * @author wei
    **/
    
    function  getWeichatQRcodeAction($content, $qrcode_uri){
        
        $braveQRCode = $this->load(EXTEND,'BraveQRCode');
        $BraveSystem = $this->load(EXTEND,'BraveSystem');
        if($content && $qrcode_uri){
            $braveQRCode->gen($content, $qrcode_uri);
        }else{
            $error = array(
                'message' => '内容上传出错。',
            );
            return $error;
            exit();
        }
        
        //添加logo 
        $logo = APP_RESOURCE_ROOT . 'system/logo_2.jpg';
        $logo_p = imagecreatefromjpeg($logo);
        $qrcode_p = imagecreatefrompng($qrcode_uri);
        $QR_width = imagesx($qrcode_p);//二维码图片宽度 
        $QR_height = imagesy($qrcode_p);//二维码图片高度 
        $logo_width = imagesx($logo_p);//logo图片宽度 
        $logo_height = imagesy($logo_p);//logo图片高度 
        $logo_qr_width = $QR_width / 5; 
        $scale = $logo_width/$logo_qr_width; 
        $logo_qr_height = $logo_height/$scale; 
        $logo_qr_width = $logo_qr_width + 2; 
        $logo_qr_height = $logo_qr_height + 2; 
        $from_width = ($QR_width - $logo_qr_width) / 2; 
        
        //重新组合图片
        $image_p = imagecreatetruecolor($QR_width, $QR_height);
        imagecopyresampled($image_p, $qrcode_p, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);
        imagecopyresampled($image_p, $logo_p, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        imagepng($image_p, $qrcode_uri);
        imagedestroy($image_p);
        imagedestroy($qrcode_p);
        imagedestroy($logo_p);
        
        
        return true;
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
