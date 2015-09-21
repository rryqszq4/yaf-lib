<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("DEBUG",true);

$app = new Yaf_Application(APPLICATION_PATH."/conf/cli.ini");
#$request = new Yaf_Request_Simple();
/*$opt = getopt("c:a:");
if (isset($opt['c']) && isset($opt['a'])){
    $request->setRequestUri($opt['c']."/".$opt['a']);
}*/
#$request->setRequestUri("swoole/server");
$app->bootstrap()->run();
