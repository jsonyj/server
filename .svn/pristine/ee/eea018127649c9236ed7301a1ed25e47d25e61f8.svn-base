<?php

class HelpModel extends AppModel {

    function getHelpList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_help
            WHERE status = 1
            AND deleted = 0
        ";

        $sql .= " ORDER BY weight ";
        $rs = $this->getAll($sql);

        return $rs;
    }

    function getHelpById($id) {
        $this->escape($id);
        $sql = "SELECT * FROM `tb_help` WHERE status = 1 AND deleted = 0 AND id = '{$id}'";
        $rs = $this->getOne($sql);
        return $rs;
    }

}

?>
