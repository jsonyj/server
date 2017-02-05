<?php

class BraveValidator extends BraveDB {

    var $error = null;
    var $data = null;

    function getError($field = null) {
        if (is_null($field)) {
            return $this->error;
        }
        else {
            return $this->getValue($this->error, $field);
        }
    }
    
    function setError($field, $error) {
        $this->error[$field] = $error;
    }

    function unsetError($field = null) {
        if (is_null($field)) {
            $this->error = null;
        }
        else {
            $this->unsetValue($this->error, $field);
        }
    }

    function valid($config, $data, $validAll = true) {
        $this->data = $data;

        if (empty($config)) {
            return true;
        }

        foreach ($config as $field => $rules) {
            if (!is_array($rules)) {
                continue;
            }
            
            // regex
            if (preg_match('/^\/\^(.*?)\$\/([is]*)$/i', $field)) {
                foreach ($this->data as $k => $v) {
                    if (preg_match($field, $k)) {
                        $this->handle($k, $rules);
                    }
                }
                
                continue;
            }
            
            // normal
            if (!isset($this->data[$field])) {
                $this->data[$field] = null;
            }

            $this->handle($field, $rules);
            
            // check
            if (!$validAll && $this->error) {
                return false;
            }
        }
        
        return $this->error? false: true;
    }
    
    function handle($field, $rules) {
        if (empty($rules)) {
            return false;
        }
        
        foreach ($rules as $v) {
            // func & msg
            if (count($v) < 2) {
                continue;
            }

            $func = $v[0];
            $error = $v[1];

            // vars
            $vars = null;

            if (isset($v[2]))
                $vars = $v[2];

            if (method_exists($this, $func)) {
                if (!$this->$func($field, $vars)) {
                    $this->setError($field, $error);
                    break;
                }
            }
        }
        
        return true;
    }

    function isNotNull($field, $vars) {
        $data = $this->data[$field];

        if (is_array($data))
            return empty($data)? false: true;
        else
            return strlen($data)? true: false;
    }

    function isNumber($field, $vars) {
        $regex = '/^[0-9]+$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isMail($field, $vars) {
        $regex = "/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\._\-]*\.[a-zA-Z0-9\._\-]*$/is";
        return preg_match($regex, $this->data[$field]);
    }
    
    function isNumeric($field, $vars) {
        $regex = '/^[1-9]+[0-9]*$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isFloat($field, $vars) {
        $regex = '/^([1-9]+[0-9]*)|([0-9]{1}\.[0-9]+)|([1-9]+[0-9]*\.[0-9]+)$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isDigital($field, $vars) {
        $regex = '/^([1-9]+[0-9]*)|([0-9]{1}\.[0-9]+)|([1-9]+[0-9]*\.[0-9]+)|([0-9]+)$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isAlpha($field, $vars) {
        
    }
    
    function commonCompare($field, $vars, $regex) {
        $value = $this->data[$field];
        $notnull = $vars['notnull'];
        $e = $vars['msg'];

        if(is_array($value)) {
            $empty = array_count_values($value);
            if($empty[null] == count($value)) {
                $value = '';
            } else {
                $value = implode("-",$value);
            }
        }
        
        if($notnull && !$value) {
            return false;
        }
        
        if ($value && !preg_match($regex,$value)) {
            return false;
        }
        
        return true;
    }
    
    function isPostCode($field, $vars) {
        $regex = "/^[0-9]{3}\-[0-9]{4}$/";
        //return preg_match($regex, $this->data[$field]);
        return $this->commonCompare($field,$vars,$regex);
    }
    
    function isTelCode($field, $vars) {
        $regex = "/^[0-9]{2,4}\-[0-9]{2,4}\-[0-9]{2,4}$/";
        //return preg_match($regex, $this->data[$field]);
        return $this->commonCompare($field,$vars,$regex);
    }

    function isPersonCard($field, $vars) {//身份证判断
        $regex = "/^[1-9][0-7]\d{4}((19\d{2}(0[13-9]|1[012])(0[1-9]|[12]\d|30))|(19\d{2}(0[13578]|1[02])31)|(19\d{2}02(0[1-9]|1\d|2[0-8]))|(19([13579][26]|[2468][048]|0[48])0229))\d{3}(\d|X|x)?$/";
        return $this->commonCompare($field,$vars,$regex);
    }
    
    function isCardNo($field, $vars) {
        $regex = "/^[0-9]{4}\-[0-9]{4}\-[0-9]{4}\-[0-9]{4}$/";
        //return preg_match($regex, $this->data[$field]);
        return $this->commonCompare($field,$vars,$regex);
    }

    function isMonth($field, $vars) {
        $month = $this->data[$field];
        
        if ($this->isNumber($field, $vars)) {
            return ($month <= 12 && $month >= 1)? true: false;
        }
        
        return false;
    }
    
    function isYear($field, $vars) {
        $year = $this->data[$field];
        
        if ($this->isNumber($field, $vars)) {
            return ($year < 2100 && $year > 1970)? true: false;
        }
        
        return false;
    }
    
    function isFaxCode($field, $vars) {
        
    }
    
    function isDate($field, $vars) {
        $date = $this->data[$field];
        $regex = "/^(19|20)[0-9]{2}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}$/";
        
        if (!preg_match($regex, $date)) {
            return false;
        }
        
        $time = strtotime($date);
        return (date('Y-m-d', $time) !== $date)? false: true;
    }

    function isDate2($field, $vars) {
        $date = $this->data[$field];
        $regex = "/^(19|20)[0-9]{2}[0-1]{1}[0-9]{1}[0-3]{1}[0-9]{1}$/";
        
        if (!preg_match($regex, $date)) {
            return false;
        }
        
        $time = strtotime($date);
        return (date('Ymd', $time) !== $date)? false: true;
    }

    function isPhoneNo($field, $vars) {
        $data = $this->data[$field];
        $regex = "/^1[0-9]\d{9}$/";
        if(!preg_match($regex,$data)) {
            return false;
        }
        else {
            return true;
        }
    }

    function isMobile($field, $vars) {
        $data = $this->data[$field];
        $regex = "/1[0-9]\d{9}$/";
        if(preg_match($regex, $data)) {
            return true;
        }
        else {
            return false;
        }
    }

    function isDateTime($field, $vars) {
        $date = $this->data[$field];
        $regex = "/^(19|20)[0-9]{2}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}\s+[0-2]{1}[0-9]{1}:[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$/";
        
        if (!preg_match($regex, $date)) {
            return false;
        }
        
        $time = strtotime($date);
        return (date('Y-m-d H:i:s', $time) !== $date)? false: true;
    }
    
    function isDateTimeTwo($field, $vars) {
        $date = $this->data[$field];
        $regex = "/^(19|20)[0-9]{2}[0-1]{1}[0-9]{1}[0-3]{1}[0-9]{1}[0-2]{1}[0-9]{1}[0-5]{1}[0-9]{1}[0-5]{1}[0-9]{1}$/";
        
        if (!preg_match($regex, $date)) {
            return false;
        }
        
        $time = strtotime($date);
        return (date('YmdHis', $time) !== $date)? false: true;
    }
    
    function isHour($field, $vars) {
        $hour = $this->data[$field];
        $regex = "/^[0-9]{1,2}$/";
        
        if (!preg_match($regex, $date)) {
            return false;
        }
        else {
            return ($hour >= 0 && $hour < 24)? true: false;
        }
    }
    
    function isFile($field, $vars) {
        
    }
    
    function isKana($field, $vars) {
        $value = $this->data[$field];
        $value = str_replace("　","",$value);
        $value = str_replace(" ","",$value);
        $regex = "/^(?:\xE3\x82[\xA1-\xBF]|\xE3\x83[\x80-\xB6]|ー|\-)+$/";
        return preg_match($regex, $value);
    }
    
    function isAccount($field, $vars) {
        $regex = '/^[0-9a-z\_\-]{6,255}$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isPassword($field, $vars) {
        $regex = '/^[0-9a-z]{6,12}$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isSame($field, $vars) {
        if (!isset($vars['compare']) || !$this->data[$vars['compare']]) {
            return false;
        }
        else {
            return ($this->data[$field] === $this->data[$vars['compare']])? true: false;
        }
    }
    
    function isNumberArray($field, $vars) {
        $data = $this->data[$field];
        $regex = '/^[0-9]+$/';

        foreach ($data as $k => $v) {
            if (!preg_match($regex, $v)) {
                return false;
            }
        }
        
        return true;
    }
    
    function isNumericArray($field, $vars) {
        $data = $this->data[$field];
        $regex = '/^[1-9]+[0-9]*$/';

        foreach ($data as $k => $v) {
            if (!preg_match($regex, $v)) {
                return false;
            }
        }
        
        return true;
    }
    
    function isBool($field, $vars) {
        $data = $this->data[$field];
        if(is_bool($data)) {
            return true;
        }
        else{
            return false;
        }
    }
    
    function isLaterDateTime($field, $vars) {
        $date = $this->data[$field];
        $compareWithDate = $this->data[$vars['compareWith']];
        return strtotime($date) > strtotime($compareWithDate);
    }
    
    function isLaterDate($field, $vars) {
        $date = $this->data[$field];
        $compareWithDate = $this->data[$vars['compareWith']];
        $compareModel = $vars['mode'];
        $date = date("Y-m-d",strtotime($date));
        $compareWithDate = date("Y-m-d",strtotime($compareWithDate));
        
        if ($compareModel == 'ge') {
            return strtotime($date) >= strtotime($compareWithDate);
        }
        
        return strtotime($date) > strtotime($compareWithDate);
    }
    
    function isEarlyDate($field, $vars) {
        $date = $this->data[$field];
        $compareWithDate = $this->data[$vars['compareWith']];
        $compareModel = $vars['mode'];
        $date = date("Y-m-d",strtotime($date));
        $compareWithDate = date("Y-m-d",strtotime($compareWithDate));
        
        if ($compareModel == 'le') {
            return strtotime($date) <= strtotime($compareWithDate);
        }
        
        return strtotime($date) < strtotime($compareWithDate);
    }

    function isGreaterThan($field, $vars) {
        return $this->data[$field] > $vars['value'];
    }

    function isMaxLength($field, $vars) {
        return mb_strlen($this->data[$field]) <= $vars['length'];
    }
    
    function isInArray($field, $vars) {
        if (!isset($vars['in_arr'])) {
            return false;
        }
        else {
            return in_array($this->data[$field], $vars['in_arr']) ? true: false;
        }
    }
    
    function isIdentity($field, $vars) {
        $regex = '/^(\d{18,18}|\d{15,15}|\d{17,17}x)$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isUserPhoneExist($field, $vars) {
        $id = $this->data['id'];
        $this->escape($id);
        
        $phone = $this->data[$field];
        $sql = "
            SELECT * FROM tb_user 
            WHERE phone = '{$phone}' AND id <> '{$id}' AND deleted = 0
        ";
        
        $rs = $this->getOne($sql);
        return $rs ? false: true;
    }

    function isUploadValid($field, $vars) {
        $data = $this->data[$field];
        return isset($data['save'])? true: false;
    }

    function isKeyExisted($field, $vars) {
        if (!isset($vars['keys']) || !$vars['keys']) {
            return false;
        }
        else {
            return in_array($this->data[$field], $vars['keys'])? true: false;
        }
    }

    function isKeyNotExisted($field, $vars) {
        if (!isset($vars['key']) || !$vars['key']) {
            return false;
        }
        else {
            
            return in_array($vars['key'], $vars['keys'])? false: true;
        }
    }
    
}

?>