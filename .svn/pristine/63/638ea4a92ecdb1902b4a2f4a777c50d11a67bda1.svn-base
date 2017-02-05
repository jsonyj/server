<?php
if (is_file(LIBRARY . 'AliyunOss' . DS . 'autoload.php')) {
    require_once LIBRARY . 'AliyunOss' . DS . 'autoload.php';
}
// require_once __DIR__ . '/Config.php';

use OSS\OssClient;
use OSS\Core\OssException;

class Brave {
    
    var $logType = 'customer';
    var $controllerPostfix = 'Controller';
    var $modelPostfix = 'Model';
    var $classPostfix = '.php';
    // 连接OSS，创建实例
    function getOssClient()
    {
        try {
            $ossClient = new OssClient(ACCESSKEYID, ACCESSKEYSECRET, ENDPOINT, false);
        } catch (OssException $e) {
            printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
            printf($e->getMessage() . "\n");
            return null;
        }
        return $ossClient;
    }
    // 打印变量字符串
    function println($message)
    {
        if (!empty($message)) {
            echo strval($message) . "\n";
        }
    }
    function debug($data, $level = E_USER_NOTICE) {
        $name = get_class($this);
        $msg = print_r($data, true);
        $msg = "[CLASS: {$name}] " . $msg;
        trigger_error($msg, $level);
    }
    // 写日志
    function log($data, $type = null) {
        if (LOG_MODE) {
            $logger = $this->load(CORE, 'BraveLogger');     // 定义BraveLogger.php全局变量并赋值
            
            if (is_array($data))                            // 判断$data是否为数组
                $msg = print_r($data, true);                // 是数组：打印该数组并赋值
            else
                $msg = $data;                               // 不是数组：赋值

            if ($this->isEmpty($type))                      // 判断$type是否为空
                $type = $this->logType;                     // 为空:赋值

            $logger->logger($type, $msg);                   // 写进LOG文件
        }
    }
    // 判断数据是否为空
    function isEmpty(&$data) {
        if (is_array($data)) {
            return empty($data);
        }
        elseif (is_null($data) || strlen($data) == 0) {
            return true;
        }
        else {
            return false;
        }
    }
    // 返回数组
    function unique($data, $key, $val = null) {
        $result = null;

        if (empty($data)) {
            return $result;         // 返回空数组
        }

        if (!isset($data[0][$key])) {   // 判断是否为二维数组
            $this->debug('unique key is not found.', E_USER_WARNING);
            return $data;   // 返回一维数组
        }
        
        foreach ($data as $k => $v) {
            if ($val) {
                $result[$v[$key]] = $v[$val];   // 返回三维数组
            }
            else {
                $result[$v[$key]] = $v;     // 返回二维数组
            }
        }
        
        return $result;
    }
    
    function group(&$data, $key, $val = null, $isArray = true) {
        $result = array();
        
        if (empty($data)) {
            return $result;     // 返回空数组
        }
        
        foreach ($data as $k => $v) {
            if ($val) {
                if ($isArray) {
                    $result[$v[$key]][] = $v[$val];
                }
                else {
                    $result[$v[$key]] = $v[$val];
                }
            }
            else {
                if ($isArray) {
                    $result[$v[$key]][] = $v;
                }
                else {
                    $result[$v[$key]] = $v;
                }
                
            }
        }
        
        return $result;
    }
    
    function setProperty($key, $val) {
        $this->{$key} = $val;
    }

/* --------------------------------------------------- */
    function getValue(&$data, $key) {
        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return null;
        }

        // var1
        if (isset($data[$key])) {
            return $data[$key];
        }

        // var1.var2....
        $tmp = explode('.', $key);
        
        if (count($tmp) > 1) {
            $key = array_shift($tmp);

            if (isset($data[$key])) {
                $remain = implode('.', $tmp);
                return $this->getValue($data[$key], $remain);
            }
        }

        return null;
    }

    function setValue(&$data, $key, $value) {
        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return false;
        }

        // var
        if (isset($data[$key])) {
            $data[$key] = $value;
            return true;
        }
        
        // var1.var2....
        $tmp = explode('.', $key);
        
        if (count($tmp) > 1) {
            $key = array_shift($tmp);

            if (isset($data[$key])) {
                $remain = implode('.', $tmp);
                return $this->setValue($data[$key], $remain, $value);
            }
        }
        else {
            $data[$key] = $value;
            return true;
        }
        
        return false;
    }

    function issetValue(&$data, $key) {
        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return false;
        }

        // var1
        if (isset($data[$key])) {
            return true;
        }
        
        // var1.var2....
        $tmp = explode('.', $key);
        
        if (count($tmp) > 1) {
            $key = array_shift($tmp);

            if (isset($data[$key])) {
                $remain = implode('.', $tmp);
                return $this->issetValue($data[$key], $remain);
            }
        }
        
        return false;
    }

    function unsetValue(&$data, $key) {
        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return false;
        }

        // var
        if (isset($data[$key])) {
            unset($data[$key]);
            return true;
        }
        
        // var1.var2....
        $tmp = explode('.', $key);
        
        if (count($tmp) > 1) {
            $key = array_shift($tmp);

            if (isset($data[$key])) {
                $remain = implode('.', $tmp);
                return $this->unsetValue($data[$key], $remain);
            }
        }
        
        return false;
    }
/* --------------------------------------------------- */

/* --------------------------------------------------- */
    function code($key = null) {
        global $code;

        if (is_null($key)) {
            return $code;
        }
        
        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return null;
        }

        return $this->getValue($code, $key);
    }
    
    function lang($key, $name = null) {
        global $lang;
        
        if (is_null($name)) {
            $name = get_class($this);
        }

        if (isset($lang[$name][$key])) {
            return $lang[$name][$key];
        }
        else {
            return $key;
            $error = "{$name} {$key} is not found.";
            $this->debug($error, E_USER_ERROR);
            return null;
        }
    }
    
    function langs($data) {
        $name = get_class($this);
        
        foreach ($data as $k => $v) {
            $data[$k] = $this->lang($v, $name);
        }
        
        return $data;
    }

    function getGlobal($key) {
        if ($this->isEmpty($key)) {
            $this->debug('key is null.');
            return null;
        }

        return $this->getValue($GLOBALS, $key);
    }

    function setGlobal($key, $value) {
        return $this->setValue($GLOBALS, $key, $value);
    }

    function issetGlobal($key) {
        return $this->issetValue($GLOBALS, $key);
    }

    function unsetGlobal($key = null) {
        if (is_null($key)) {
            $GLOBALS = array();
            return true;
        }

        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return false;
        }
                    
        return $this->unsetValue($GLOBALS, $key);
    }
/* --------------------------------------------------- */

/* --------------------------------------------------- */
    function getSession($key, $unset = false) {
        if (is_null($key)) {
            return $_SESSION;
        }
        
        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return null;
        }

        $value = $this->getValue($_SESSION, $key);

        if ($unset) {
            $this->unsetValue($_SESSION, $key);
        }

        return $value;
    }

    function setSession($key, $value) {
        return $this->setValue($_SESSION, $key, $value);
    }

    function unsetSession($key = null) {
        if (is_null($key)) {
            $_SESSION = array();
            session_destroy();
            return true;
        }

        if (strlen($key) == 0) {
            $this->debug('key is null.');
            return false;
        }
                    
        return $this->unsetValue($_SESSION, $key);
    }

    function get($key = null, $default = null) {
        if (is_null($key)) {
            return $_GET;
        } 
        
        $value = $this->getValue($_GET, $key);
        
        if (is_null($value)) {
            return $default;
        }
        else {
            return $value;
        }
    }

    function post($key = null, $default = null) {
        if (is_null($key)) {
            return $_POST;
        } 
        
        $value = $this->getValue($_POST, $key);
        
        if (is_null($value)) {
            return $default;
        }
        else {
            return $value;
        }
    }

    function request($key = null, $default = null) {
        if (is_null($key)) {
            return $_REQUEST;
        } 
        
        $value = $this->getValue($_REQUEST, $key);
        
        if (is_null($value)) {
            return $default;
        }
        else {
            return $value;
        }
    }

    function files($key = null) {
        if (empty($_FILES)) {
            return null;
        }

        $files = null;

        foreach ($_FILES as $k => $v) {
            if (!is_array($v['name'])) {
                $files[$k] = $v;
                continue;
            }

            foreach ($v['name'] as $k1 => $v1) {
                if (!is_array($v1)) {
                    $files[$k][$k1]['name'] = $v1;
                    $files[$k][$k1]['type'] = $v['type'][$k1];
                    $files[$k][$k1]['tmp_name'] = $v['tmp_name'][$k1];
                    $files[$k][$k1]['error'] = $v['error'][$k1];
                    $files[$k][$k1]['size'] = $v['size'][$k1];
                    continue;
                }
                
                foreach ($v1 as $k2 => $v2) {
                    $files[$k][$k1][$k2]['name'] = $v2;
                    $files[$k][$k1][$k2]['type'] = $_FILES[$k]['type'][$k1][$k2];
                    $files[$k][$k1][$k2]['tmp_name'] = $_FILES[$k]['tmp_name'][$k1][$k2];
                    $files[$k][$k1][$k2]['error'] = $_FILES[$k]['error'][$k1][$k2];
                    $files[$k][$k1][$k2]['size'] = $_FILES[$k]['size'][$k1][$k2];
                }
            }
        }

        if (is_null($key)) {
            return $files;
        }  

        return $this->getValue($files, $key);
    }
    
    function server($key = null) {
        if (is_null($key)) {
            return $_SERVER;
        }  

        return $this->getValue($_SERVER, $key);
    }

    function isGet($key, $value = null) {
        if (is_null($value)) {
            if (isset($_GET[$key])) {
                return true;
            }
            elseif (isset($_GET["{$key}_x"])) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            if (isset($_GET[$key]) && $_GET[$key] === $value) {
                return true;
            }
            else {
                return false;
            }
        }
    }
    
    function isPost($key, $value = null) {
        if (is_null($value)) {
            if (isset($_POST[$key])) {
                return true;
            }
            elseif (isset($_POST["{$key}_x"])) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            if (isset($_POST[$key]) && $_POST[$key] === $value) {
                return true;
            }
            else {
                return false;
            }
        }
    }
/* --------------------------------------------------- */ 

/* --------------------------------------------------- */
    // 包含一次该文件并避免致命错误
    function requireOnce($file) {
        if (!file_exists($file)) {
            $this->debug("File is not found: {$file}", E_USER_ERROR);
            return false;
        }

        $rs = require_once($file);
        return $rs;
    }

    // 包含一次该文件并避免致命错误
    function includeOnce($file) {           
        if (!file_exists($file)) {      //  检查文件或目录是否存在
            $this->debug("File is not found: {$file}", E_USER_ERROR);       
            return false;           // 不存在返回false
        }
        else {
            return include_once($file);         // 存在 返回包含一次该文件
        }
    }
    // 添加类（文件）为全局变量
    function load($dir, $name) {
        $file = $dir . $name . $this->classPostfix;     // eg：$file = /Brave/init.php;
        return $this->newObject($file, $name);          // 返回类（文件）为全局变量
    }
    // 定义类（文件）为全局变量
    function newObject($file, $className) {
        $this->includeOnce($file);      // 包含一次该文件并避免致命错误

        if (class_exists($className)) {         // 检查类是否已定义
            $object = new $className;           // 如果已定义，那么实例化该类
            $this->setGlobal($className, $object);          // 设置该类为全局变量
            return $object;                     // 返回该全局变量
        }
        else {
            return null;            // 如果未定义，那么返回空
        }
    }
/* --------------------------------------------------- */

/* --------------------------------------------------- */
    function dispatcher() {
        $name = 'BraveDispatcher';
        
        if ($this->issetGlobal($name)) {
            return $this->getGlobal($name);
        }
        else {
            $this->debug("Dispatcher is not found", E_USER_ERROR);
            return null;
        }
    }

    function getController($name) {
        $name.= $this->controllerPostfix;

        if ($controller = $this->getGlobal($name)) {
            return $controller;
        }

        if ($controller = $this->load(APP_CONTROLLER, $name)) {
            $controller->name = $name;
            $this->setGlobal($name, $controller);
            return $controller;
        }
        else {
            $this->debug("Controller is not found: {$name}", E_USER_ERROR);
            return null;
        }
    }

    function getModel($name) {        
        $name.= $this->modelPostfix;
        
        if ($model = $this->getGlobal($name)) {
            return $model;
        }
        
        if ($model  = $this->load(APP_MODEL, $name)) {
            $this->setGlobal($name, $model);
            return $model;
        }
        else {
            $this->debug("Model is not found: {$name}", E_USER_ERROR);
            return null;
        }
    }

    function getView($name = 'BraveView', $data = null) {
        // global
        if ($view = $this->getGlobal($name)) {
            return $view;
        }

        if ($view = $this->load(CORE, $name)) {
            $view->autoAssign($data);
            $this->setGlobal($name, $view);
            return $view;
        }
        else {
            $this->debug("View is not found", E_USER_ERROR);
            return null;
        }
    }

    function getDBO($id = 1, $new = false) {
        // dsn
        global $dsn;
        
        if (!$id || !isset($dsn[$id])) {
            $this->debug("DSN is not found: {$id}", E_USER_ERROR);
            return null;
        }

        $thisDsn = $dsn[$id];
        $name = "{$id}@dsn";
        
        if (!$dbo = $this->getGlobal($name)) {
            $file =   LIBRARY.'Adodb' . DS . 'adodb.inc.php';
            $this->includeOnce($file);
            $dbo = ADONewConnection($thisDsn['driver']);
            $dbo->SetFetchMode(ADODB_FETCH_ASSOC);
            $this->setGlobal($name, $dbo);
            register_shutdown_function(array(&$dbo, "Close"));
        }

        if (!$dbo->IsConnected() || $new) {
            // connect
            if (!$dbo->Connect($thisDsn['host'], $thisDsn['login'], $thisDsn['password'], $thisDsn['database'])) {
                $this->debug("Database connect failure: {$name}", E_USER_ERROR);
                return null;
            }
            
            // init db
            if ($thisDsn['driver'] == 'mysql' || $thisDsn['driver'] == 'mysqli') {
                if (isset($thisDsn['charset'])) {
                    $dbo->Execute("SET NAMES {$thisDsn['charset']};");
                }
            }
        }

        return $dbo;
    }
    
/* --------------------------------------------------- */
    function isHttps() {
        $port = $this->server('SERVER_PORT');
        return ($port == '443')? true: false;
    }

    function message($key, $name = null) {
        global $msg;

        if (is_null($name)) {
            $name = get_class($this);
        }

        if (isset($msg[$name][$key])) {
            return $msg[$name][$key];
        } else {
            $error = "{$name} {$key} is not found.";
            $this->debug($error, E_USER_ERROR);
            return null;
        }
    }
    
  function redirect($url, $js = false, $msg=null,$delay = 0) {
        // js
        if ($js) {
            $output = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            $output.= '<script type="text/javascript">';
            $output.= "alert('{$msg}');";
            $output.= "document.location='{$url}';";
            $output.= '</script>';
            $output.= '</head></html>';
            exit($output);
        } else if (headers_sent()) {
            $output = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            $output.= "<meta http-equiv=\"refresh\" content=\"{$delay};URL={$url}\" />";
            $output.= '</head></html>';
            exit($output);
        }
        // header
        else {
            header("Location: {$url}");
            exit();
        }
    }
    
    function ip() {
        if(getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } 
        elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } 
        elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } 
        else {
            $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        }
        
        return $ip;
    }
    
    function isValidDate($date) {
        $date = str_replace('/', '-', $date);
        $regex = "/^(19|20)[0-9]{2}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}$/";
        
        if (!preg_match($regex, $date)) {
            return false;
        }
        
        $time = strtotime($date);
        return (date('Y-m-d', $time) !== $date)? false: true;
    }
    
    function isValidDatePair($startDate,$enddate,$compareModel = 'ge') {
        $enddate = date("Y-m-d",strtotime($enddate));
        $startDate = date("Y-m-d",strtotime($startDate));
        
        if ($compareModel == 'ge') {
            return strtotime($enddate) >= strtotime($startDate);
        }
        
        return strtotime($enddate) > strtotime($startDate);
    }
    
    function isValidHi($time) {
        if (!preg_match('/^([0-9]{2}):([0-9]{2})$/', $time, $m)) {
            return false;
        }
        
        if ($time !== '24:00') {
            if ((int)$m[1] > 23 || (int)$m[2] > 59) {
                return false;
            }
        }
        
        return true;
    }
    
    function getUploadDir() {
        if (!defined('SYNC_MODE') || !SYNC_MODE) {
            return 'upload';
        }
        
        if (is_file(APP_UPLOAD . 'check.txt')) {
            return 'upload';
        } else {
            return 'upload_standby';
        }
    }

    function httpRequest($url, $method = 'get', $data = array()) {
        // null?
        if (!strlen($url)) {
            return null;
        }
        
        // get
        if ($method == 'get') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $output = curl_exec($ch);
        }
        
        // post
        if ($method == 'post') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count(data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $data));
            ob_start();
            curl_exec($ch);
            $output = ob_get_contents() ;
            curl_close($ch);
            ob_end_clean();
        }

        // result
        return $output;
    }
    
    function httpPostJson($url, $data) {
        // null?
        if (!strlen($url)) {
            return null;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: application/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0');

        $output = curl_exec($ch);

        curl_close($ch);
        
        // result
        return $output;
    }

    function objArray($array)
    {
        if(is_object($array))
        {
            $array = (array)$array;
        }
        if(is_array($array))
        {
            foreach($array as $key=>$value)
            {
                $array[$key] = objArray($value);
            }
       }
       return $array;
    }

    function makeParameter($data) {
        $parameter = '?';
        
        foreach ($data as $k => $v) {
            $parameter.= "{$k}=$v&";
        }
        
        return substr($parameter, 0, -1);
    }

    function dateFormat($date, $format='Y-m-d') {
        if($date == '0000-00-00') {
            return '';
        } else {
            return date($format, strtotime($date));
        }
    }
    
    function mkdirs($dir, $mode = 0777) {
        if (!is_dir($dir)) {
            $this->mkdirs(dirname($dir), $mode);

            if (mkdir($dir, $mode)) {
                chmod($dir, $mode);
                return true;
            }
            else {
                return false;
            }
        }

        return true;
    }
}

?>
