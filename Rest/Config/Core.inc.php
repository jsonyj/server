<?php

/**
* @desc Debug & Error Handle
*/
define('LOG_MODE', true);
define('DEBUG_MODE', true);

/**
* @desc Debug & Error Handle
*/
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_ACTION', 'index');

/**
 * Define RBAC Basic roles
 */

// RBAC_EVERYONE any one
define('RBAC_EVERYONE',     -1);

// RBAC_HAS_ROLE  have any role
define('RBAC_HAS_ROLE',     -2);

// RBAC_NO_ROLE no role
define('RBAC_NO_ROLE',      -3);

// RBAC_NULL no value
define('RBAC_NULL',         null);

// ACTION_ALL all action in controller
define('ACTION_ALL',        -1);

/**
* @desc Session
*/
define('SESSION_START', true);
define('SESSION_LIFETIME', 1440);
define('SESSION_PATH', '/var/lib/php/session');
define('SESSION_ROLE', 'CZBX_ROLE_ADMIN');
define('SESSION_USER', 'CZBX_USER_ADMIN');

define('SERVICE_TYPE_DAILY', 1); //日报
define('SERVICE_TYPE_MONTH', 2); //月报
define('SERVICE_TYPE_SHUTTLE', 3); //接送报告
define('SERVICE_TYPE_STATURE', 4); //身高
define('SERVICE_TYPE_WEIGHT', 5); //体重
define('SERVICE_TYPE_TEMPERATURE', 6); //温度
define('SERVICE_TYPE_IMG', 7); //照片

define('REPORT_TYPE_DAY', '1');       //日报
define('REPORT_TYPE_MONTH', '2');       //月报
define('REPORT_TYPE_SHUTTLE', '3');       //接送报告

define('QUERY_TABLE_MONTH', '1');       //月表
define('QUERY_TABLE_YEAR', '2');       //年表
define('QUERY_TABLE_TOTAL', '3');       //总表

define('APP_SERVICE_TYPE_URL', 'http://test.didano.cn/wx/');

/**
 * @desc now
 */
define('NOW', date("Y-m-d H:i:s"));
?>