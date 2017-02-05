<?php

class RelationModel extends AppModel {

    function getRelationOptionList() {
        $this->escape($schoolId);
        $sql = "
            SELECT id AS value, title AS name FROM tb_relation
            WHERE deleted = 0 ORDER BY id ASC";

        $rs = $this->getAll($sql);

        $relationList = array();
        foreach($rs as $v) {
            $relationList[$v['value']] = $v;
        }

        return $relationList;
    }

}

?>
