<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("APPLICATION_INDEX", __FILE__);
define("DEBUG",true);

$app = new Yaf_Application(APPLICATION_PATH."/conf/cli.ini");
$app->bootstrap()->run();
