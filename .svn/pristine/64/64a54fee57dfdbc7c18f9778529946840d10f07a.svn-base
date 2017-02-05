<?php

class ArticleAuthModel extends AppModel {

    function validSaveArticleAuth($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
        );

        if(!$form['report_type_day'] && !$form['report_type_week'] && !$form['report_type_month']) {
            $config['report_type'] = array(
                array('isNotNull', '请选择可见报告'),
            );
        }
        
        if($form['school_type'] == ARTICLE_AUTH_SELECT_SCHOOL) {
            $config['school_id'] = array(
                array('isNotNull', '请选择可见学校'),
            );
        }

        switch($form['class_type']) {
            case ARTICLE_AUTH_SELECT_CLASS:
                $config['class_id'] = array(
                    array('isNotNull', '请选择可见班级'),
                );
                break;

            case ARTICLE_AUTH_SELECT_GRADE:
                $config['grade_id'] = array(
                    array('isNotNull', '请选择可见年级'),
                );
                break;
        }

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    function saveArticleAuth($form) {
        $articleId = $form['id'];
        $this->escape($articleId);
        $this->deleteAuth($articleId);

        $reportTypeList = array(
            'report_type_day' => ARTICLE_AUTH_REPORT_DAY,
            'report_type_week' => ARTICLE_AUTH_REPORT_WEEK,
            'report_type_month' => ARTICLE_AUTH_REPORT_MONTH
        );

        foreach($reportTypeList as $k => $v) {

            if($form[$k]) {
                $data = array(
                    'article_id' => $articleId,
                    'type' => $v,
                    'value' => 0,
                );

                $this->saveAuth($data);
            }
        }

        if($form['school_type'] == ARTICLE_AUTH_ALL_SCHOOL) {
            $data = array(
                'article_id' => $articleId,
                'type' => ARTICLE_AUTH_ALL_SCHOOL,
                'value' => 0,
            );

            $this->saveAuth($data);
        } else {
            foreach($form['school_id'] as $k => $v) {

                $data = array(
                    'article_id' => $articleId,
                    'type' => ARTICLE_AUTH_SELECT_SCHOOL,
                    'value' => $v,
                );

                $this->saveAuth($data);
            }
        }

        switch($form['class_type']) {
            case ARTICLE_AUTH_ALL_CLASS:
                $data = array(
                    'article_id' => $articleId,
                    'type' => ARTICLE_AUTH_ALL_CLASS,
                    'value' => 0,
                );

                $this->saveAuth($data);

                break;

            case ARTICLE_AUTH_ALL_GRADE:
                $data = array(
                    'article_id' => $articleId,
                    'type' => ARTICLE_AUTH_ALL_GRADE,
                    'value' => 0,
                );

                $this->saveAuth($data);

                break;

             case ARTICLE_AUTH_SELECT_CLASS:
                foreach($form['class_id'] as $k => $v) {

                    $data = array(
                        'article_id' => $articleId,
                        'type' => ARTICLE_AUTH_SELECT_CLASS,
                        'value' => $v,
                    );

                    $this->saveAuth($data);
                }

                break;

            case ARTICLE_AUTH_SELECT_GRADE:
                foreach($form['grade_id'] as $k => $v) {

                    $data = array(
                        'article_id' => $articleId,
                        'type' => ARTICLE_AUTH_SELECT_GRADE,
                        'value' => $v,
                    );

                    $this->saveAuth($data);
                }

                break;
        }

        return true;
    }

    function saveAuth($form) {
        $table = 'tb_article_auth';
        $data = array(
            'article_id' => $form['article_id'],
            'type' => $form['type'],
            'value' => $form['value'],
        );

        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }
    
    function deleteAuth($articleId) {
        $this->escape($articleId);
        $sql = "DELETE FROM tb_article_auth WHERE article_id = '{$articleId}'";
        return $this->Execute($sql);
    }

    function getAuth($articleId) {
        $this->escape($articleId);
        $sql = "
            SELECT * FROM tb_article_auth
            WHERE article_id = '{$articleId}' ORDER BY type ASC
        ";
        
        $rs = $this->getAll($sql);

        $form = array();
        $title = array();
        foreach($rs as $k => $v) {

            switch($v['type']) {
                case ARTICLE_AUTH_REPORT_DAY:
                    $form['report_type_day'] = $v['type'];
                    $title['report_type_day'] = '日报可见';
                    break;

                case ARTICLE_AUTH_REPORT_WEEK:
                    $form['report_type_week'] = $v['type'];
                    $title['report_type_week'] = '周报可见';
                    break;

                case ARTICLE_AUTH_REPORT_MONTH:
                    $form['report_type_month'] = $v['type'];
                    $title['report_type_month'] = '月报可见';
                    break;

                case ARTICLE_AUTH_ALL_SCHOOL:
                    $form['school_type'] = $v['type'];
                    $title['all_school'] = '所有学校可见';
                    break;

                case ARTICLE_AUTH_SELECT_SCHOOL:
                    $form['school_type'] = $v['type'];
                    $form['school_id'][] = $v['value'];
                    $title['select_school'] = '选择学校可见';
                    break;

                case ARTICLE_AUTH_ALL_CLASS:
                    $form['class_type'] = $v['type'];
                    $title['all_class'] = '所有班级可见';
                    break;

                case ARTICLE_AUTH_ALL_GRADE:
                    $form['class_type'] = $v['type'];
                    $title['all_grade'] = '所有年级可见';

                    break;

                 case ARTICLE_AUTH_SELECT_CLASS:
                    $form['class_type'] = $v['type'];
                    $form['class_id'][] = $v['value'];
                    $title['select_class'] = '选择班级可见';
                    break;

                case ARTICLE_AUTH_SELECT_GRADE:
                    $form['class_type'] = $v['type'];
                    $form['grade_id'][] = $v['value'];
                    $title['select_grade'] = '选择年级可见';
                    break;
            }
        }

        $form['type_title'] = $title;

        return $form;
    }

    function getArticleReadingNum($articleId){
        $this->escape($articleId);
        $sql = "SELECT reading_num FROM tb_article_readingnum WHERE article_id = '{$articleId}'";
        return $this->getOne($sql);
    }
}

?>
