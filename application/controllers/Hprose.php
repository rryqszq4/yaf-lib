<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-5-25
 * Time: 下午5:33
 */

class HproseController extends Controller {

    public $_layout = null;

    public function indexAction(){

    }

    public function httpserverAction(){
        function hello($name) {
            echo "Hello $name!";
            return "Hello $name!";
        }
        function e() {
            throw new Exception("I am Exception");
        }
        function ee() {
            require("andot");
        }
        function asyncHello($name, $callback) {
            sleep(3);
            $callback("Hello async $name!");
        }
        $server = new HproseHttpServer();
        $server->setErrorTypes(E_ALL);
        $server->setDebugEnabled();
        $server->addFunction('hello');
        $server->addFunctions(array('e', 'ee'));
        $server->addAsyncFunction('asyncHello');
        $server->start();

        return false;
    }

    public function httpclientAction(){

        $client = new HproseHttpClient('http://yaf.zhaoquan.com/hprose/httpserver');
        echo $client->hello('World');

        return false;
    }
}