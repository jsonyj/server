<?php

class ArticleModel extends AppModel {

    function getArticleList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_article
            WHERE deleted = 0";
        
        $this->where($sql, 'title', 'title', 'lk');
        $this->where($sql, 'body', 'body', 'lk');
        
        if ($all) {
            $this->order($sql, 'order.default');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.default');
            $this->order($sql, 'order.default');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function getArticleOptionList() {
        $this->setSearch($sh);
        $sql = "
            SELECT id AS value, title AS name FROM tb_article
            WHERE deleted = 0 AND status = 1";

        $this->order($sql, 'order.default');
        $rs = $this->getAll($sql);

        $schoolList = array();
        foreach($rs as $v) {
            $schoolList[$v['value']] = $v;
        }

        return $schoolList;
    }

    function getArticle($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_article
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validSaveArticle($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入标题'),
            ),
            'body' => array(
                array('isNotNull', '请输入内容'),
            ),
            'start' => array(
                array('isNotNull', '请输入结束时间'),
            ),
            'end' => array(
                array('isNotNull', '请输入开始时间'),
            )
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function saveArticle($form) {
        $table = 'tb_article';
        $data = array(
            'title' => $form['title'],
            'link' => trim($form['link']),
            'body' => $form['body'],
            'start' => $form['start'],
            'end' => $form['end'],
            'status' => $form['status'] ? true : false,
        );

        if(isset($form['type'])) {
            $data['type'] = $form['type'];
        }

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
    
    function deleteArticle($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_article', $data, $where);
    }

    function saveArticleNum($article_id){
        $this->escape($article_id);
        $table = 'tb_article_readingnum';
        $data = array(
            'article_id' => $article_id,
            'created' => NOW,
        );
        return $this->Insert($table, $data);
    }
}

?>
