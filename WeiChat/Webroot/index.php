<?php

error_reporting(E_ALL^E_NOTICE);
set_time_limit(6000);

// cli mode
define('CLI_MODE', false);

// directory separator
define('DS', DIRECTORY_SEPARATOR);

// set web root path
define('APP_WEBROOT', dirname(__FILE__) . DS);

// set app path
define('APP', dirname(dirname(__FILE__)) . DS);

// include framework
require(dirname(APP) . DS . 'Brave' . DS . 'init.php');

include(LIBRARY . 'FirePHPCore' . DS . 'fb.php');


// dispatch
$dispatcher = new BraveDispatcher;
$dispatcher->dispatch();

?>
