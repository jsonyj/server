<?php

class AppValidator extends BraveValidator {
    
    function isUserNotExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "
            SELECT * FROM tb_user 
            WHERE phone = '{$phone}' AND deleted = 0 
        ";
        
        $rs = $this->getOne($sql);
        return $rs ? false : true;
    }
    
    function isUserExists($field, $vars) {
        $phone = $this->data[$field];
        $this->escape($phone);
        
        $sql = "
            SELECT * FROM tb_user 
            WHERE phone = '{$phone}' AND deleted = 0 
        ";
        
        $rs = $this->getOne($sql);
        return $rs ? true : false;
    }
    
    function pwdLengthIsOK($field, $vars) {
        $password = $this->data[$field];
        $len = strlen($password);
        
        if (8 <= $len && $len <= 16) {
            return true;
        }
        
        return false;
    }
    
    function isNickname($field, $vars) {
        $regex = '/^[a-zA-z0-9_\x{4e00}-\x{9fa5}]{1,20}$/u';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isGroupUserNotExisted($field, $vars) {
        $identity = $this->data[$field];
        $group_id = $this->data['group_id'];
        $id = $this->data['id'];

        $this->escape($identity);
        
        $sql = "
            SELECT * FROM tb_group_user
            WHERE identity = '{$identity}' AND group_id = '{$group_id}' AND deleted = 0 
        ";

        if($id) {
            $sql .= " AND id <> '{$id}'";
        }

        $rs = $this->getOne($sql);
        return $rs ? false : true;
    }

    function isUserPersonalOrder($field, $vars) {
        $id = $this->data[$field];

        $user = $this->getSession(SESSION_USER);

        $this->escape($id);
        
        $sql = "
            SELECT * FROM tb_personal
            WHERE id = '{$id}' AND user_weichat_id = '" . $user['id'] . "' AND deleted = 0 
        ";

        $rs = $this->getOne($sql);
        return $rs ? true : false;
    }
    
    function isCaptcha($field, $vars){
        $captcha = $this->data[$field];
        
        if($captcha == $this->getSession(SESSION_CAPTCHA)){
            $this->unsetSession(SESSION_CAPTCHA);
            return true;
        }else {
            return false;
        }
    }

    function isPhoneExisted($field, $vars) {
        $phone = $this->data['phone'];
        $this->escape($phone);
        $sql = "
            SELECT * FROM tb_user
            WHERE phone = '{$phone}' AND deleted = 0 
        ";

        $rs = $this->getOne($sql);
        return $rs ? false : true;
    }
}

?>