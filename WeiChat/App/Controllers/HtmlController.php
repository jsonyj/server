<?php

class HtmlController extends AppController {

    function HtmlController() {
        $this->AppController();
    }

    /**
     * @desc 视频监控管理
     * @author ly
     */
    function monitorAction(){
        $this->view->layout();
    }
    
    /**
     * @desc 摄像头管理
     * @author ly
     */
    function cameraAction(){
        $this->view->layout();
    }
    
    /**
     * @desc 家长电话
     * @author ly
     */
    function parentPhoneAction(){
        $this->view->layout();
    }
    
}

?>
