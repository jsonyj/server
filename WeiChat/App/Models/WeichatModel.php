<?php

class WeichatModel extends AppModel {

    function getWeichat($openId) {
        $this->escape($openId);
        $sql = "
            SELECT * FROM tb_weichat
            WHERE openid = '{$openId}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }

    function saveWeichat($user) {
        $table = 'tb_weichat';
        $data = array(
            'openid' => $user['openid'],
            'nickname' => $user['nickname'],
            'sex' => $user['sex'],
            'province' => $user['province'],
            'city' => $user['city'],
            'country' => $user['country'],
            'headimgurl' => $user['headimgurl'],
            'unionid' => $user['unionid'],
        );

        if ($user['id'] > 0) {
            $user_id = $user['id'];
            $this->escape($user_id);
            
            $where = "id = '{$user_id}'";
            $this->Update($table, $data, $where);
            return $user['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
}

?>
