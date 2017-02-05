<?php

class AuthorizeController extends AppController {

  var $weichatModel = null;

  function AuthorizeController() {
    $this->AppController();
    $this->weichatModel = $this->getModel('Weichat');
  }

  function indexAction() {
    $redirectUri = $this->get('redirect_uri');
    $this->redirect($redirectUri);
  }

  function callbackAction() {
    $refererUrl = $this->getSession('act_referer');

    if(WX_APP_DEBUG) {

        $user_new = array(
            'openid' => md5(date('His', time()) . uniqid(rand(1000,9999), true)),
            'nickname' => '未认证用户',
            'sex' => '未知',
            'province' => '未知',
            'city' => '未知',
            'country' => '未知',
            'headimgurl' => '未知',
        );

    } else {

        $wx_code_cb = $this->get('code');
        $wx_state = $this->get('state');
        
        $app_id = WX_APP_ID;
        $app_secret = WX_APP_SECRET;

        $user_info = array();
        if($wx_code_cb && $wx_state) {
            //获取微信授权access_token
            $access_token_info = $this->getWXAccessToken($app_id, $app_secret, $wx_code_cb);
            
            //获取授权后的微信用户信息
            $user_info = $this->getWXUserInfo($access_token_info['access_token'], $access_token_info['openid']);
            
            if(isset($user_info['errcode'])) {
                $this->log('User Info: ' . print_r($user_info, true));
                $this->log('Access Token: ' . print_r($access_token_info, true));
                $this->log('SERVER: ' . print_r($_SERVER, true));
                echo '系统错误，请稍后再试';
                exit();
            }
        } else {
            $this->log('微信认证失败：' . print_r($_SERVER, true));
            echo '系统错误，请稍后再试';
            exit();
        }

        $user_new = array(
            'openid' => $user_info['openid'],
            'unionid' => $user_info['unionid'],
            'nickname' => $user_info['nickname'],
            'sex' => $user_info['sex'],
            'province' => $user_info['province'],
            'city' => $user_info['city'],
            'country' => $user_info['country'],
            'headimgurl' => $user_info['headimgurl'],
        );

    }

    include(LIBRARY . 'mutex.php');

    $mutex = new Mutex("user_weichat_save_mutex");
    while(!$mutex->getLock()){
        sleep(.5);
    }

    $userWeichat = array();
    if($user_new['unionid']) {
        $userWeichat = $this->weichatModel->getUserByUnionId($user_new['unionid']);
    }

    if(empty($userWeichat)) {
        $userWeichat = $this->weichatModel->getWeichat($user_new['openid']);
    }

    if($userWeichat) {
        $user_new['id'] = $userWeichat['id'];
    }

    $userWeichatId = $this->weichatModel->saveWeichat($user_new);

    $user_new['id'] = $userWeichatId;

    $mutex->releaseLock();

    $this->setSession(SESSION_WX_USER, $user_new);

    //referer url
    $this->redirect($refererUrl);
  }
}

?>
