<?php

$auth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';

if(WX_APP_DEBUG) {
  $auth_url = APP_WEICHAT_URL . '?c=authorize&';
}

$act['demo'] = array(
  'deny' => array(ACT_NO_ROLE),
  'direct' => $auth_url . 'appid=' . WX_APP_ID . '&redirect_uri=' . urlencode(WX_AUTHORIZE_CALLBACK_URI) . '&response_type=code&scope=snsapi_userinfo&state=' . WX_STATE . '#wechat_redirect',
);

$act['user'] = array(
  'deny' => array(ACT_NO_ROLE),
  'direct' => '?c=index&a=bind',
);