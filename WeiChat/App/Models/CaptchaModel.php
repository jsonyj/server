<?php
class CaptchaModel extends AppModel {

    function validGetCaptcha($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        
        $config = array(
            'phone' => array(
                array('isNotNull', '请输入手机号码'),
                array('isMobile', '请输入正确的手机号码'),
            ),
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function genCaptcha() {
        $captcha = sprintf('%04d', rand(1,9999));
        //$captcha = '1111';
        return $captcha;
    }

    function saveCaptcha($form) {
        $table = 'tb_captcha';
        
        $data = array(
            'phone' => $form['phone'],
            'captcha' => $form['captcha'],
        );

        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }

    function getCaptcha($phone, $captcha) {
        $this->escape($phone);
        $this->escape($captcha);

        $sql = "SELECT * FROM tb_captcha WHERE deleted = 0 AND phone = '{$phone}' AND captcha = '{$captcha}'";
        return $this->getOne($sql);
    }

    function deleteCaptcha($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_captcha', $data, $where);
    }
}

?>
