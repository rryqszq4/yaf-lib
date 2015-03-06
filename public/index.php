<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("DEBUG_TOOLS",true);

if (DEBUG_TOOLS){
    require_once(APPLICATION_PATH.'/application/library/DebugTools.php');
    //DebugTools::startTimeMemory();
}

$app = new Yaf_Application(APPLICATION_PATH."/conf/application.ini");
$app->bootstrap()->run();

if (DEBUG_TOOLS){
    //DebugTools::useTimeMemory();
}

?>
