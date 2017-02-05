<?php

class AppModel extends BaseModel {

    function validFileUpload($image, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'file_info' => array(
                array('isUploadValid', '图片上传错误，请按照要求上传（图片大小 1.5M 以内）'),
            ),
        );
        
        if (!$validator->valid($config, $image)) {
            $errors = $validator->getError();
            $errors = $this->langs($errors);
            return false;
        }

        return true;
     }
     
    function autoFetch($array,$key){
        
        $array_temp = array();
        
        if(empty($array)){
            return $array;
        }else{
            foreach ($array as $k => $v){
                if(in_array($k, $key)&&NULL!==$v){
                    $array_temp[$k] = $v;
                }
            }
        }
         
        return $array_temp;
         
     }
     
}

?>
