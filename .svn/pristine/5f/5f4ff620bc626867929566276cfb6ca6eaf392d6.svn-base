<?php

class HobbyModel extends AppModel {

    function getHobbyList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_hobby
            WHERE deleted = 0
        ";

        $this->where($sql, 'tb_hobby.title', 'title', 'lk');

        if ($all) {
            $this->order($sql, 'order.hobby');
            $rs = $this->getAll($sql);
        }
        else {
            $countSql = "
                SELECT COUNT(*) FROM tb_hobby
                WHERE deleted = 0
            ";
            $this->where($countSql, 'tb_hobby.title', 'title', 'lk');
            $this->paging('paging.default');
            $this->order($sql, 'order.hobby');
            $rs = $this->paginate($sql,$countSql);
        }

        return $rs;
    }

    function validHobby($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入爱好'),
                ),
        );

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
        return true;

    }

    function saveHobby($form) {
        $table = 'tb_hobby';
        $data = array(
            'title' => $form['title'],
        );

        if (($form['id'] > 0)) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }

    function getHobbyById($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM `tb_hobby` WHERE deleted = 0 AND id = '{$id}'
            ";
        $rs = $this->getOne($sql);
        return $rs;
    }

    function deleteHobby($id) {
        $this->escape($id);
        $table = "tb_hobby";
        $where = "id = '{$id}'";
        $data = array(
            'deleted' => 1
        );
        return $this->Update($table, $data, $where);
    }
}

?>
