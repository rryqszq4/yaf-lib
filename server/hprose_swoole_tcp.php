<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-5-26
 * Time: 下午5:16
 */

require_once('../application/tools/hprose-php/Hprose.php');
function hello($name) {
    echo "Hello $name!";
    return "Hello $name!";
}
/*function e() {
    throw new Exception("I am Exception");
}*/
/*function ee() {
    require("andot");
}*/
// swoole 1.7.16+
function asyncHello($name, $callback) {
    swoole_timer_after(3000, function() use ($name, $callback) {
        $callback("Hello async $name!");
    });
}

class TestModel {
    public function one(){
        return 'one';
    }
}

class B {
    public function aa(){
        return "ced";
    }
}

$server = new HproseSwooleServer("tcp://127.0.0.1:1504");
$server->setErrorTypes(E_ALL);
$server->setDebugEnabled();
#$server->addFunction('hello');
$server->add(new TestModel(),'','test');
#$server->add(new B(),'b');
#$server->addFunctions(array('e', 'ee'));
#$server->addAsyncFunction('asyncHello');
$server->start();
