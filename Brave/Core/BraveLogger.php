<?php

class BraveLogger extends Brave {

    var $postfix = '.log';
    var $mode = 0777;
    var $break = PHP_EOL;

    function mkdir() {
        // System
        $system = $this->load(EXTEND, 'BraveSystem');
        
        // dir
        $today = date('Y-m-d');
        $dir = APP_LOG . $today . DS;
        
        if (!$system->mkdirs($dir, $this->mode)) {
            trigger_error("Can not mkdir: {$dir}", E_USER_WARNING);
        }
        $oldtime = date('Y-m-d', strtotime('-1 month'));
        $year=((int)substr($oldtime,0,4));//取得年份
        $month=((int)substr($oldtime,5,2));//取得月份
        $day=((int)substr($oldtime,8,2));//取得几号
        $old_unix = mktime(0,0,0,$month,$day,$year);
        $log_array = $this->traverse(APP_LOG);
        foreach ($log_array as $value) {
            $year=((int)substr($value,0,4));//取得年份
            $month=((int)substr($value,5,2));//取得月份
            $day=((int)substr($value,8,2));//取得几号
            $log_unix = mktime(0,0,0,$month,$day,$year);
            if($log_unix < $old_unix){
                $log_unix = date("Y-m-d",$log_unix); 
                $olddir = APP_LOG . $log_unix;
                $this->deldir($olddir);
            }
        }
        return $dir;
    }
    // 遍历文件夹
    function traverse($path = '.') {
        $array = array();
        $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
        while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
            if($file == '.' || $file == '..') {
                continue;
            } elseif(is_dir($sub_dir)) {  
                $array[] = $file ;
             }
         }
         return $array;
     }
    // 删除文件夹及文件
    function deldir($olddir){
        $dh=opendir($olddir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$olddir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($olddir)) {
            return true;
        } else {
            return false;
        }

    }

    function logger($type, $log) {
        // file
        $dir = $this->mkdir();
        $file = $dir . $type . $this->postfix;
        
        if (!is_file($file) && $fp = fopen($file, 'a')) {
            fclose($fp);
            chmod($file, $this->mode);
        }
        
        if (!$fp = fopen($file, 'a')) {
            trigger_error("Can not mkfile: {$file}", E_USER_WARNING);
            exit;
        }
        
        // prefix
        $prefix = '[' . date('Y-m-d H:i:s') . ']' . $this->break;
        
        // log
        flock($fp, LOCK_EX);
        
        fwrite($fp, $prefix);
        fwrite($fp, $log);
        fwrite($fp, $this->break . $this->break);
        
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

?>