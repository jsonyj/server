<?php

/**
 * @desc 帮助相关页面
 * @author
 */

class HelpController extends AppController {

    var $helpModel = null;

    function HelpController() {
        $this->AppController();
        $this->helpModel = $this->getModel('Help');
    }

    /**
     * @desc 帮助文档列表页
     * @author
     */
    function indexAction() {
        $help_list = $this->helpModel->getHelpList();
        $this->view->assign('help_list', $help_list);
        $this->view->layout();
    }

    /**
     * @desc 帮助文章详情页
     * @author
     */
    function detailAction() {
        
        $id = $this->get('id');
        $form = $this->post('form');

        $form = $this->helpModel->getHelpById($id);
        if($id && empty($form)) {
             $this->redirect("?c=help&a=index", true, $this->lang('ID不存在'));
             exit();
        }
        $this->view->assign('form', $form);
        $this->view->layout();
    }
}
