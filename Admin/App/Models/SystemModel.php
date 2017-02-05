<?php

class SystemModel extends AppModel {

    function saveAccessToken($accessToken) {
        $this->escape($accessToken);

        $sql = "INSERT INTO tb_system (k, v, updated) VALUES ('" . WX_ACCESS_TOKEN . "', '" . serialize($accessToken) . "', '" . NOW . "')
                ON DUPLICATE KEY UPDATE v = '" . serialize($accessToken) . "', updated = '" . NOW . "'";
        return $this->Execute($sql);
    }

    function getAccessToken() {
        $sql = "SELECT v FROM tb_system WHERE k = '" . WX_ACCESS_TOKEN . "'";
        $rs = $this->getOne($sql);
        return unserialize($rs['v']);
    }

    function saveJsapiTicket($jsapiTicket) {
        $this->escape($jsapiTicket);

        $sql = "INSERT INTO tb_system (k, v, updated) VALUES ('" . WX_JSAPI_TICKET . "', '" . serialize($jsapiTicket) . "', '" . NOW . "')
                ON DUPLICATE KEY UPDATE v = '" . serialize($jsapiTicket) . "', updated = '" . NOW . "'";
        return $this->Execute($sql);
    }

    function getJsapiTicket() {
        $sql = "SELECT v FROM tb_system WHERE k = '" . WX_JSAPI_TICKET . "'";
        $rs = $this->getOne($sql);
        return unserialize($rs['v']);
    }
}

?>
