<?php

class ClassModel extends AppModel {

    /**
     * @desc 获取当前学校所有班级用于下拉框
     * @author ly
     * @param unknown $school_id
     * @return NULL|unknown|unknown[]
     */
    function getClassSelectList($school_id){
        $this->escape($school_id);
        
        $sql = "SELECT id AS value,title AS name FROM tb_class
                WHERE deleted = 0 AND school_id = '{$school_id}'";
        
        return $this->getAll($sql);
    }
    
}

?>
