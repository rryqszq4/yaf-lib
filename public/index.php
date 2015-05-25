<?php
#xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("DEBUG_TOOLS",true);

if (DEBUG_TOOLS){
    require_once(APPLICATION_PATH.'/application/library/DebugTools.php');
    #DebugTools::startTimeMemory();
}

require_once(APPLICATION_PATH."/application/tools/hprose-php/Hprose.php");

$app = new Yaf_Application(APPLICATION_PATH."/conf/application.ini");
$app->bootstrap()->run();

if (DEBUG_TOOLS){
    #DebugTools::useTimeMemory();
}

#$xhprof_data = xhprof_disable();

#$XHPROF_ROOT = APPLICATION_PATH."/application/tools";
#include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
#include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

#$xhprof_runs = new XHProfRuns_Default();
#$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_testing");

#echo "http://192.168.80.138/xhprof/xhprof_html/index.php?run={$run_id}&source=xhprof_testing\n";
?>
