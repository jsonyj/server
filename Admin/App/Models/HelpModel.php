<?php

class HelpModel extends AppModel {

    function getHelpList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_help
            WHERE deleted = 0
        ";

        $this->where($sql, 'tb_help.title', 'title', 'lk');

        if ($all) {
            $this->order($sql, 'order.help');
            $rs = $this->getAll($sql);
        }
        else {
            $countSql = "
                SELECT COUNT(*) FROM tb_help
                WHERE deleted = 0
            ";
            $this->where($countSql, 'tb_help.title', 'title', 'lk');
            $this->paging('paging.default');
            $this->order($sql, 'order.help');
            $rs = $this->paginate($sql,$countSql);
        }

        return $rs;
    }

    function validHelp($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入标题'),
                ),
            'link' => array(
                array('isNotNull','请填写链接地址'),
                ),
        );

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
        return true;

    }

    function saveHelp($form) {
        $table = 'tb_help';
        $data = array(
            'title' => $form['title'],
            'link' => $form['link'],
            'weight' => $form['weight'],
            'status' => $form['status'] ? true : false,
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
    
    /**
     * @desc 修改图片地址
     * @author ly
     * @param $iconImg
     * @param $id
     */
    function saveHelpIconImg($iconImg,$id) {
        $this->escape($iconImg);
        $this->escape($id);
        
        $sql = "UPDATE tb_help SET icon_img = '{$iconImg}' WHERE deleted = 0 AND id = '{$id}'";
        // $sql = "UPDATE tb_help SET icon_img = '{$iconImg}' WHERE deleted = 0 AND id = '{$id}'";
        
        $this->getOne($sql);
    }

    function getHelpById($id) {
        $this->escape($id);
        $sql = "SELECT * FROM `tb_help` WHERE deleted = 0 AND id = '{$id}'";
        $rs = $this->getOne($sql);
        return $rs;
    }

    function deleteHelp($id) {
        $this->escape($id);
        $table = "tb_help";
        $where = "id = '{$id}'";
        $data = array(
            'deleted' => 1
        );
        return $this->Update($table, $data, $where);
    }
    
    /**
     * @desc 删除图片
     * @author ly
     * @param $id
     */
    function deleteHelpImg($id) {
        $this->escape($id);
        
        $sql = "UPDATE tb_help SET icon_img = '' WHERE deleted = 0 AND id = '{$id}'";
    
        $this->getOne($sql);
    }
    
    /**
     * @desc 取最大排序号
     * @author ly
     * @return NULL|unknown
     */
    function getHelpMaxWeight(){
        
        $sql = " SELECT weight FROM tb_help WHERE deleted = 0 ORDER BY weight DESC LIMIT 1 ";
        
        $rs = $this->getOne($sql);
        return $rs['weight'];
    }
    
    /**
     * @desc 检查是否存在排序号
     * @author ly
     * @param $weight
     * @return NULL|unknown
     */
    function getHelpByWeight($weight){
        $this->escape($weight);
        
        $sql = " SELECT * FROM tb_help WHERE deleted = 0 AND weight = '{$weight}' ";
        
        return $this->getOne($sql);
    }
    
    /**
     * @desc 更新排序号
     * @author ly
     * @param $id
     */
    function updateHelpWeight($id, $weight) {
        $this->escape($id);
        $this->escape($weight);
    
        $sql = "UPDATE tb_help SET weight = '{$weight}' WHERE deleted = 0 AND id = '{$id}'";
    
        $this->getOne($sql);
    }
}

?>
