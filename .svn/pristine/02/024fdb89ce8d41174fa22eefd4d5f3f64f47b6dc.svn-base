<?php

class VideoController extends AppController{

	function indexAction(){
		$user = $this->getSession(SESSION_USER);
		$school_id = $user['school_id'];
		$this->view->assign('school_id', $school_id);
		$this->view->layout();
	}

	function inputAction(){
		$this->view->layout();
	}

	function updataAction(){
		$this->view->layout();
	}

}
?>