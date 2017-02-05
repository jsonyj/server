<?php

class ClassModel extends AppModel {

    /**
     * @desc 根据学校id获取所有班级
     * @author ly
     * @param $schoolId
     * @return NULL|unknown|unknown[]
     */
    function getClassList($schoolId) {
        $this->escape($schoolId);
        
        $sql = "
            SELECT tb_class.id,tb_class.title  FROM tb_class
            WHERE tb_class.school_id = '{$schoolId}' AND tb_class.deleted = 0";
        
        return $this->getAll($sql);
    }
}

?>
