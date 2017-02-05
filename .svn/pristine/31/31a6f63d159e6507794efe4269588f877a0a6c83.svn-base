<?php

class ArticleModel extends AppModel {

    function getArticleList($schoolId, $classId, $gradeId, $reportType=0) {
        $this->escape($schoolId);
        $this->escape($classId);
        $this->escape($gradeId);

        $reportTypeSql = '';
        if($reportType) {
            $reportTypeSql = "
            AND id IN
            (
              SELECT article_id FROM tb_article_auth WHERE type = '{$reportType}'
            )
            ";
        }

        $sql = "
            SELECT * FROM tb_article
            WHERE deleted = 0 AND status = 1 AND NOW() >= start AND NOW() <= end
            AND id IN (
              SELECT article_id FROM tb_article_auth WHERE type = " . ARTICLE_AUTH_ALL_SCHOOL . "
              UNION 
              SELECT article_id FROM tb_article_auth WHERE type = " . ARTICLE_AUTH_SELECT_SCHOOL . " AND value = '{$schoolId}'
            )
            AND id IN
            (
              SELECT article_id FROM tb_article_auth WHERE type = " . ARTICLE_AUTH_ALL_CLASS . "
              UNION 
              SELECT article_id FROM tb_article_auth WHERE type = " . ARTICLE_AUTH_SELECT_CLASS . " AND value = '{$classId}'
              UNION
              SELECT article_id FROM tb_article_auth WHERE type = " . ARTICLE_AUTH_ALL_GRADE . "
              UNION 
              SELECT article_id FROM tb_article_auth WHERE type = " . ARTICLE_AUTH_SELECT_GRADE . " AND value = '{$gradeId}'
            )
            $reportTypeSql
            ORDER BY created";
        
        $rs = $this->getAll($sql);
        return $rs;
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

    function updateReadingNum($articleId){
        $this->escape($articleId);
        $sql = "SELECT * FROM tb_article_readingnum WHERE article_id = '{$articleId}'";
        $rs = $this->getOne($sql);
        $data = array('reading_num' => $rs['reading_num']+1);
        $where = "article_id = '{$articleId}'";
        return $this->Update('tb_article_readingnum', $data, $where);
    }
}

?>
