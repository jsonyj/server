<?php

class DreamModel extends AppModel {

    function getDreamList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_dream
            WHERE deleted = 0
        ";

        if ($all) {
            $this->order($sql, 'order.dream');
            $rs = $this->getAll($sql);
        }
        else {
            $countSql = "
                SELECT COUNT(*) FROM tb_dream
                WHERE deleted = 0
            ";
            $this->paging('paging.default');
            $this->order($sql, 'order.dream');
            $rs = $this->paginate($sql,$countSql);
        }

        return $rs;
    }

    function getDreamById($id) {
        $this->escape($id);
        $sql = "SELECT * FROM `tb_dream` WHERE DELETED = 0 AND id='{$id}'";
        $rs = $this->getOne($sql);
        return $rs;
    }

    function validDream($form, &$errors) {
        $valid = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'name' => array(
                array('isNotNull','梦想名字不能为空')
            )
        );

        if (!$valid->valid($config, $form)) {
            $errors = $this->langs($valid->getError());
            return false;
        }
        return true;
     }

     function saveDream($form) {
         $this->escape($form);
         $table = "tb_dream";
         $data = array(
             'name' => $form['name'],
         );
         if ($form['id']>0) {
             $where = "id='{$form['id']}'";
             if (!$this->Update($table, $data, $where)) {
                 return false;
             }
             return $form['id'];
         }
         $data['created'] = NOW;
         return $this->Insert($table, $data);
     }

     function saveDreamImg($file, $id) {
         $table = "tb_dream";
         $data = $file;
         $where = "id= {$id}";

         return $this->Update($table, $data, $where);
     }

     function deleteDream($id) {
         $this->escape($id);
         $table = "tb_dream";
         $data = array(
             'deleted' =>APP_UNIFIED_TRUE
         );
         $where = "id = '{$id}'";
         return $this->Update($table, $data, $where);
     }

     function deleteDreamImg($id) {
         $this->escape($id);
         $table = "tb_dream";
         $data = array(
             'img_url' => ''
         );
         $where = "id = '{$id}'";
         $this->Update($table, $data, $where);

     }
}

?>
