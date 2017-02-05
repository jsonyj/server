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

}

?>
