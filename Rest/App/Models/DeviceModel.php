<?php

class DeviceModel extends AppModel {

    function getDevice($no) {
        $this->escape($no);
        $sql = "
            SELECT tb_device.*, tb_school.id AS school_id, tb_school.title AS school_title
            FROM tb_device
            LEFT JOIN tb_school ON tb_school.id = tb_device.school_id
            WHERE tb_device.no = '{$no}' AND tb_device.deleted = 0 AND tb_device.status = 1 AND tb_school.deleted = 0 AND tb_school.status = 1
        ";
        
        return $this->getOne($sql);
    }
}

?>
