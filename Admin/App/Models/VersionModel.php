<?php

class VersionModel extends AppModel {

    // 群 列表
    function getBranch($sh, $all = false){
        
         $this->setSearch($sh);
        $sql = "
            SELECT * FROM `tb_branch` WHERE deleted = 0  
            ";

        if ($all) {
            $this->order($sql, 'order.dream');
            $rs = $this->getAll($sql);
        }
        else {
            $countSql = "
                SELECT COUNT(*) FROM tb_branch
                WHERE deleted = 0
            ";
            $this->paging('paging.default');
            $this->order($sql, 'order.branch');
            $rs = $this->paginate($sql,$countSql);
        }

        return $rs;
    }
    function getBranch2($sh){
        $this->setSearch($sh);
         $sql = "
            SELECT id,branch_name FROM `tb_branch` WHERE deleted = 0
            ";
        return $this->getAll($sql);
    }
    function getBranch3($id){
         $sql = "
            SELECT * FROM `tb_branch` 
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
            ";
        return $this->getOne($sql);
    }
    // 群 增加&修改
    function saveBranch($form){
        $table = 'tb_branch';
        $data = array(
            'branch_name' => $form['branch_name'],
            'branch_no' => $form['branch_no'],
            'branch_des' => $form['branch_des'] ,
        );

        if ($form['id'] > 0) {
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
    // 删除群信息
    function deteleBranch($id){
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_branch', $data, $where);
    }
    // 上传&修改版本文件
    function insertVerion($form){
        $table = 'tb_version';
        $data = array(
                "branch_id" => $form['branch_id'],
                "main_version" => $form['main_version'],
                "branch_version" => $form['branch_version'],
                "version_url" => $form['version_url'],
                "model_no" => $form['model_no'],
                "version_des" => $form['version_des'],
                "device_type" => $form['device_type'],
                );

        if ($form['id'] > 0) {
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
    // 删除版本信息
    function deteleVersion($id){
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_version', $data, $where);
    }
    // 版本信息展示
    function getVersionList($sh, $all = false){
        $this->setSearch($sh);
        $sql = "
            SELECT tb_version.*, tb_branch.branch_name AS branch_name
            FROM tb_version
            LEFT JOIN tb_branch ON tb_version.branch_id = tb_branch.id
            WHERE tb_version.deleted = 0 AND tb_branch.deleted = 0
            ";
        
        $this->where($sql, 'tb_branch.branch_name', 'branch_name', 'lk');
        $this->where($sql, 'tb_version.id', 'id', 'lk');
        
        if ($all) {
            $this->order($sql, 'order.version');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.version');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getVersion($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_version
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
}