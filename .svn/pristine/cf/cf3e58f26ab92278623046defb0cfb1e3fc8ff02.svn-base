<?php

// cli mode
define('CLI_MODE', true);

// directory separator
define('DS', DIRECTORY_SEPARATOR);

// set web root path
define('APP_WEBROOT', dirname(dirname(__FILE__)) . DS . 'Webroot' . DS);

// set app path
define('APP', dirname(dirname(__FILE__)) . DS);

// include framework
require(dirname(APP) . DS . 'Brave' . DS . 'init.php');

// dispatch
$dispatcher = new BraveDispatcher;
$dispatcher->dispatch(array('c' => 'batch', 'a' => 'reportMonth'));

?>
