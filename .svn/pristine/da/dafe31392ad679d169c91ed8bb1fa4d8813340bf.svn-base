<?php

class VoiceModel extends AppModel {

    /**
     * @desc 根据文件id获取
     * @param $id
     * @return array
     */
    function getVoice($id){
        $this->escape($id);
        
        $sql = "SELECT * FROM tb_voice 
                WHERE deleted = 0 AND id = '{$id}'";
        
        return $this->getOne($sql);
    }
    
    
    function saveVoice($data){
        $table = 'tb_voice';
        
        $record = array(
            'voice_name' => $data['voice_name'],
            'voice_path' => $data['voice_path'],
            'data' => $data['data'],
            'usage_id' => $data['usage_id'],
            'voice_type' => $data['voice_type'],
        );
        
        if ($data['id'] > 0) {
            $voiceid = $data['id'];
            $this->escape($voiceid);
            
            $where = "id = '{$voiceid}'";
            $this->Update($table, $record, $where);
            return $voiceid;
        }
        else {
            $record['created'] = NOW;
            return $this->Insert($table, $record);
        }
        
    }
    
    function deleteVoice($file_id){
        $this->escape($file_id);
        $sql = "DELETE FROM tb_voice WHERE tb_voice.id = '{$file_id}'";
        return $this->getOne($sql);
    }

}

?>
