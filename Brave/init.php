<?php

/**
* @desc TimeZone
*/
date_default_timezone_set("Asia/Chongqing");

/**
* @desc System Define
*/
define('ROOT', dirname(__FILE__));

/**
* @desc Act Define
*/
define('ACT_NO_ROLE', 0);
define('ACT_HAS_ROLE', 1);
define('ACT_ADMIN_ROLE', 2);          //超级管理员
define('ACT_SCHOOL_ADMIN_ROLE', 11);  //学校管理员
define('ACT_PARENT_ROLE', 21);        //家长
define('ACT_SCHOOL_GENERAL', 30);     //学校职工统称
define('ACT_SCHOOL_HEADMASTER', 31);  //园长
define('ACT_SCHOOL_TEACHER', 32);     //老师
define('ACT_SCHOOL_DOCTOR', 33);      //医生
define('ACT_SCHOOL_SUPPORTER', 34);   //勤务
define('ACT_STUDENT_ROLE', 40);       //学生
define('ACT_EVERYONE', 99);

define('ACT_API', 80);                //API
define('ACT_APPLICATION', 90);        //应用

/**
* @desc Brave Define
*/
define('CORE', ROOT . DS . 'Core' .DS);
define('EXTEND', ROOT . DS . 'Extend' .DS);
define('LIBRARY', ROOT . DS . 'Library' .DS);
define('TEMPLATE', ROOT . DS . 'Template' .DS);

/**
* @desc App Define
*/
define('APP_ROOT', APP . 'App' . DS);
define('APP_CACHE', APP . 'Cache' . DS);
define('APP_CONFIG', APP . 'Config' . DS);
define('APP_LOG', APP . 'Log' . DS);

define('APP_CORE', APP_ROOT . 'Core' . DS);
define('APP_EXTEND', APP_ROOT . 'Extend' . DS);
define('APP_CONTROLLER', APP_ROOT . 'Controllers' . DS);
define('APP_MODEL', APP_ROOT . 'Models' . DS);
define('APP_TEMPLATE', APP_ROOT . 'Templates' . DS);

/**
 * 自定义变量定义
 */

define('APP_UPLOAD', APP_WEBROOT . 'upload' . DS);
define('APP_UPLOAD_TEMP', 'upload/temp/');

define('PROJECT_ROOT', dirname(APP) . DS);
define('APP_RESOURCE_ROOT', PROJECT_ROOT . 'Resource' . DS);
define('APP_RESOURCE_URL', ' http://localhost/xiaonuojiqi/server/resource/');
define('APP_WEICHAT_URL', 'http://www.xiaonuo.local/wx/');
define('APP_SERVICE_URL', 'http://www.xiaonuo.local/service/');
define('APP_API_URL', 'http://www.xiaonuo.local/api/');

//是和否统一定义
define('APP_UNIFIED_FALSE', 0);  //否
define('APP_UNIFIED_TRUE', 1);   //是

//用户性别
define('GENDER_MEN', 1); //男
define('GENDER_WOMEN', 2);//女

//家长账户类型
define('PARENT_TYPE_MAJOR', 1);//主账号

//文件类型
define('FILE_USAGE_TYPE_STUDENT_RECOGNITION', 101); //学生识别相片
define('FILE_USAGE_TYPE_STUDENT_DETECTION', 102);   //学生检测相片
define('FILE_USAGE_TYPE_STUDENT_AWAY', 103);        //学生被接走相片
define('FILE_USAGE_TYPE_STUDENT_HANDHELD', 201);    //学生手持设备红黄绿照片
define('FILE_STAFF_TYPE_NOTICE_IMG', 900);       //老师发送消息图片

//消息类型
define('MESSAGE_TYPE_TEXT', 1); //文本消息
define('MESSAGE_TYPE_VOICE', 2); //语音消息

//通知类型
define('CLASS_NOTICE_TYPE', 1); //班级通知
define('EVALUATE_NOTICE_TYPE', 2); //老师评价
define('STATISTIC_NOTICE_TYPE', 3); //班级通知

//消息发送结果
define('SEND_STATUS_SUCCESS', 3); //发送成功
define('SEND_STATUS_FAILURE', 4); //发送失败

//文章类型
define('ARTICLE_AUTH_REPORT_DAY', 1); //日报可见
define('ARTICLE_AUTH_REPORT_WEEK', 2); //周报可见
define('ARTICLE_AUTH_REPORT_MONTH', 3); //月报可见

define('ARTICLE_AUTH_ALL_SCHOOL', 101); //所有班级可见
define('ARTICLE_AUTH_SELECT_SCHOOL', 102); //选择班级可见

define('ARTICLE_AUTH_ALL_CLASS', 201); //所有班级可见
define('ARTICLE_AUTH_SELECT_CLASS', 202); //选择班级可见

define('ARTICLE_AUTH_ALL_GRADE', 301); //所有班级可见
define('ARTICLE_AUTH_SELECT_GRADE', 302); //选择班级可见

//家长关系定义（数据库中ID要与此匹配）
define('PARENT_TYPE_FATHER', 1);       //爸爸
define('PARENT_TYPE_MOTHER', 2);       //妈妈
define('PARENT_TYPE_GRANDPA', 3);      //爷爷
define('PARENT_TYPE_GRANDMA', 4);      //奶奶
define('PARENT_TYPE_GRANDFATHER', 5);  //外公
define('PARENT_TYPE_GRANDMOTHER', 6);  //外婆
define('PARENT_TYPE_OTHER', 99);       //其他

//公司代码
define('COMPANY_CODE', 'DIDANO');

//AES终端加密
define('AES_KEY', '0IDWCFA2H5O9KFYE');    //传输加密KEY
define('AES_ALL_IV', '1234123412341234'); //外层加密向量
define('AES_IN_IV', '4321432143214321');  //内层加密向量

//AES微信绑定加密
define('AES_BIND_KEY', 'CDFB50CAC9DE46F7'); //传输加密KEY
define('AES_BIND_IV', '1234567812345678');    //加密向量

//二维码用途
define('QRCODE_TYPE_TAKE_AWAY_STUDENT', 1); //接小孩二维码
define('QRCODE_TYPE_SCHOOL_STAFF', 2);      //学校职工二维码

//家长绑定申请状态
define('BIND_PARENT_APPLY_UNDO', '0');    //未处理
define('BIND_PARENT_APPLY_AGREE', '1');   //已同意
define('BIND_PARENT_APPLY_REFUSE', '2');  //已拒绝

//手持设备检测状态（红黄绿）
define('HANDHELD_STATUS_RED', 1);     //红
define('HANDHELD_STATUS_YELLOW', 2);  //黄
define('HANDHELD_STATUS_GREEN', 3);   //绿

//签到签退状态
define('SIGN_TYPE_IN', 1);    //签到
define('SIGN_TYPE_OUT', 2);   //签退

//考勤状态
define('SIGN_STATUS_UNIN_UNOUT', 1);   //缺勤
define('SIGN_STATUS_IN_UNOUT', 2);     //正常签到且未签退
define('SIGN_STATUS_LATE_UNOUT', 3);   //迟到且未签退
define('SIGN_STATUS_IN_OUT', 4);       //正常签到且正常签退
define('SIGN_STATUS_IN_EARLY', 5);     //正常签到且早退
define('SIGN_STATUS_LATE_OUT', 6);     //迟到且正常签退
define('SIGN_STATUS_LATE_EARLY', 7);   //迟到且早退

//体温偏高默认值
define('TEMPERATURE_HIGH_DEFAULT', 37);

//学生检测数据状态
define('DETECTION_STATUS_NORMAL', 1);    //正常/认领
define('DETECTION_STATUS_RETURN', 0);    //退回

//检测数据主动推送最多次数
define('DETECTION_WEICHAT_TOTAL', 1);

/**
 * 微信相关配置
 */
define('WX_APP_URL', 'http://www.xiaonuo.local/wx/');
define('WX_APP_DEBUG', true);
define('WX_APP_ID', 'wx09c715c9af09f5a3');
define('WX_APP_SECRET', '2762a606a823b7d62cf50d03d051b2c3');
define('WX_STATE', 'xiaonuo');
define('WX_AUTHORIZE_CALLBACK_URI', WX_APP_URL . '?c=authorize&a=callback');
define('WX_ACCESS_TOKEN', 'WX_ACCESS_TOKEN');
define('WX_JSAPI_TICKET', 'WX_JSAPI_TICKET');

define('WX_TMP_ID_DETECTION', 'EIn4aunWdmDDWO_BEEaCQCqJCuTu05d3fSTTjL7L6-Q'); //微信模板ID，检查消息 
define('WX_TMP_ID_BINDSUCCESS', 'uEnT-QET1zhAFa9uskoVRLC2D06svDNDRSpyCL9m18g'); //家长绑定成功给其他家长推送消息模板

/**
* @desc Brave Libs
*/
include_once(CORE . 'Brave.php');
include_once(CORE . 'BraveException.php');
include_once(CORE . 'BraveDispatcher.php');
include_once(CORE . 'BraveDB.php');
include_once(CORE . 'BraveController.php');
include_once(CORE . 'BraveModel.php');
include_once(CORE . 'BraveView.php');
include_once(CORE . 'BraveValidator.php');

/**
* @desc Base App Libs
*/
include_once(CORE . 'BaseModel.php');
include_once(CORE . 'BaseController.php');

/**
* @desc App Config
*/
include_once(APP_CONFIG . 'App.inc.php');
include_once(APP_CONFIG . 'Code.inc.php');
include_once(APP_CONFIG . 'Core.inc.php');
include_once(APP_CONFIG . 'Act.inc.php');
include_once(APP_CONFIG . 'Lang.inc.php');
include_once(APP_CONFIG . 'Mail.inc.php');
include_once(APP_CONFIG . 'Route.inc.php');

/**
* @desc App Libs
*/
include_once(APP_CORE . 'AppController.php');
include_once(APP_CORE . 'AppModel.php');
include_once(APP_CORE . 'AppView.php');

/**
* @desc Function
*/
function pr($data, $exit = false) {
    print_r('<pre>');
    print_r($data);
    print_r('</pre>');
    if ($exit) exit;
}

function errorHandler($errno = 0, $errstr = '', $errfile = null, $errline = null) {
    $error = array(
        'errno' => $errno,
        'errstr' => $errstr,
        'errfile' => $errfile,
        'errline' => $errline,
    );

    $exception = new BraveException;
    $exception->handle($error);
}

/**
* @desc init
*/
if (true) {
    set_error_handler('errorHandler');
}

if (defined('SESSION_START') && SESSION_START) {
    session_start();
}

/* Return TRUE if $needle is empty */
function startsWith($haystack, $needle) {
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
}

/* Return TRUE if $needle is empty */
function endsWith($haystack, $needle) {
  $length = strlen($needle);
  if ($length == 0) {
    return TRUE;
  }
  $start  = $length * -1;
  return (substr($haystack, $start) === $needle);
}

?>
