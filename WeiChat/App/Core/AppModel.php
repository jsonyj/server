<?php

class AppModel extends BaseModel {
    
    function validFileUpload($image, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'file_info' => array(
                array('isUploadValid', '请选择一张图片再添加'),
            ),
        );
        
        if (!$validator->valid($config, $image)) {
            $errors = $validator->getError();
            $errors = $this->langs($errors);
            return false;
        }
        return true;
    }

    /**
     * 保费计算，人数，天数
     */
    function calculatePrice($persons, $days) {
        $premium_ruls = $this->code('premium_ruls');
        return $persons * $premium_ruls['start_price'] + $persons * $premium_ruls['renew_price'] * ($days - $premium_ruls['start_days']);
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
