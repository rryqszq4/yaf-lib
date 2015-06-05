<?php
/**
 * php index.php request_uri="/swoole/server"
 */
ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("DEBUG_TOOLS",true);

if (DEBUG_TOOLS){
    require_once(APPLICATION_PATH.'/application/library/DebugTools.php');
    #DebugTools::startTimeMemory();
}

require_once(APPLICATION_PATH."/application/tools/hprose-php/Hprose.php");
require_once(APPLICATION_PATH."/application/tools/xapian/xapian.php");

$app = new Yaf_Application(APPLICATION_PATH."/conf/application.ini");
$app->getDispatcher()->dispatch(new Yaf_Request_Simple());

if (DEBUG_TOOLS){
    #DebugTools::useTimeMemory();
}

?>
