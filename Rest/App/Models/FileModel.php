<?php

class FileModel extends AppModel {

    function saveFile($data){
        $table = 'tb_file';
        
        $record = array(
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'file_mime' => $data['file_mime'],
            'file_size' => $data['file_size'],
            'usage_id' => $data['usage_id'],
            'usage_type' => $data['usage_type'],
        );
        
        if ($data['id'] > 0) {
            $file_id = $data['id'];
            $this->escape($file_id);
            
            $where = "id = '{$file_id}'";
            $this->Update($table, $record, $where);
            return $file_id;
        }
        else {
            $record['created'] = NOW;
            return $this->Insert($table, $record);
        }
        
    }

    function getFile($usage_id, $usage_type){
        $this->escape($usage_id);
        $this->escape($usage_type);
        $sql = "SELECT tb_file.* FROM tb_file WHERE tb_file.deleted = 0 AND tb_file.usage_type = '{$usage_type}' AND tb_file.usage_id= '{$usage_id}' LIMIT 1";
        return $this->getOne($sql);
    }

    function getFileList($usage_id, $usage_type){
        $this->escape($usage_id);
        $this->escape($usage_type);
        $sql = "SELECT tb_file.* FROM tb_file WHERE tb_file.deleted = 0 AND tb_file.usage_type = '{$usage_type}' AND tb_file.usage_id= '{$usage_id}' ORDER BY created ASC";
        return $this->getALL($sql);
    }

    function deleteFile($file_id){
        $this->escape($file_id);
        $sql = "DELETE FROM tb_file WHERE tb_file.id = '{$file_id}'";
        return $this->getOne($sql);
    }
    
    function validStudentAwayImage($image, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'main_info' => array(
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
