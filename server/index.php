<?php
/**
 * php index.php request_uri="/swoole/server"
 * php index.php -cswoole -aserver
 */
ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("DEBUG_TOOLS",true);

if (DEBUG_TOOLS){
    require_once(APPLICATION_PATH.'/src/library/DebugTools.php');
    #DebugTools::startTimeMemory();
}

require_once(APPLICATION_PATH."/src/tools/hprose-php/Hprose.php");
require_once(APPLICATION_PATH."/src/tools/xapian/xapian.php");

$app = new Yaf_Application(APPLICATION_PATH."/conf/application.ini");
$request = new Yaf_Request_Simple();
$opt = getopt("c:a:");
if (isset($opt['c']) && isset($opt['a'])){
    $request->setRequestUri($opt['c']."/".$opt['a']);
}
$app->getDispatcher()->dispatch($request);

if (DEBUG_TOOLS){
    #DebugTools::useTimeMemory();
}

?>
