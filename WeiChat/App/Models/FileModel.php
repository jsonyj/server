<?php

class FileModel extends AppModel {

    /**
     * @desc 根据文件id获取
     * @author ly
     * @param $id
     * @return array
     */
    function getFile($id){
        $this->escape($id);
        
        $sql = "SELECT * FROM tb_file 
                WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    
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
    
    function deleteFile($file_id){
        $this->escape($file_id);
        $sql = "DELETE FROM tb_file WHERE tb_file.id = '{$file_id}'";
        return $this->getOne($sql);
    }
    /**
     * @desc 修改文件关联对象
     * @author ly
     * @param $id
     * @param $usage_id
     */
    function updateFileByUsageId($id,$usage_id){
        $this->escape($id);
        
        $sql = "UPDATE tb_file SET usage_id = '{$usage_id}' WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
}

?>
