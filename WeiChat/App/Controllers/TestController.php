<?php

class TestController extends AppController {


    function TestController() {
        $this->AppController();
    }

    function loginAction() {
        $user_new = array(
            'id' => 21,
            'openid' => 'oVnnvv5crllW2q630J4nqULKriDs',
            'phone' => '15184447721',
        );
        
        $this->setSession(SESSION_WX_USER, $user_new);
    }
    
    function userAction() {
        $wx_user = $this->getSession(SESSION_WX_USER);
        $user = $this->getSession(SESSION_USER);
        $role = $this->getSession(SESSION_ROLE);
        
        pr($wx_user);
        pr($user);
        pr($role);
        pr($_SESSION);
    }
    
    function setMessageAction() {
        $this->setSession(SESSION_MESSAGE, array(
            'type' => 'false',
            'body' => "您的绑定申请已经发给，申请通过后，将完成绑定。",
            'url' => '?c=index',
        ));
    }

}

?>
