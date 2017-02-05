<?php

class AppModel extends BraveModel {

    function validFileUpload($image, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'file_info' => array(
                array('isUploadValid', '请上传图片'),
            ),
        );
        
        if (!$validator->valid($config, $image)) {
            $errors = $validator->getError();
            $errors = $this->langs($errors);
            return false;
        }

        return true;
    }
}

?>
