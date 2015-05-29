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
        $server->add(new TestModel(), "testmodel");
        $server->addAsyncFunction('asyncHello');
        $server->start();

        return false;
    }

    public function httpclientAction(){

        $client = new HproseHttpClient('http://yaf.zhaoquan.com/hprose/httpserver');
        echo $client->hello('World');
        echo $client->testmodel->e();

        return false;
    }

    public function swooletcpclientAction(){
        $test = new HproseSwooleClient("tcp://127.0.0.1:1504");
        #$args = array("world");
        #var_dump($test->invoke("hello", $args, 0, HproseResultMode::Serialized, 0));
        #var_dump($test->invoke("hello", $args, 0, HproseResultMode::Raw, 0));
        #var_dump($test->invoke("hello", $args, 0, HproseResultMode::RawWithEndTag, 0));
        #var_dump($test->A);
        #echo $test->hello("yaf");
        echo $test->TestModel_one();
        #echo $test->b;
        /*try {
            $test->e();
        }
        catch (Exception $e) {
            var_dump($e->getMessage());
        }
        try {
            $test->ee();
        }
        catch (Exception $e) {
            var_dump($e->getMessage());
        }
        $test->hello('async world', function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error);
        });
        $test->hello("async world2", function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error);
        });
        $test->hello("async world3", function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error);
        });
        $test->hello("async world4", function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error);
        });
        $test->hello("async world5", function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error);
        });
        /*$test->e(function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error->getMessage());
        });
        var_dump($test->hello("world"));
        $test->ee(function($result, $args, $error) {
            echo "result: ";
            var_dump($result);
            echo "args: ";
            var_dump($args);
            echo "error: ";
            var_dump($error->getMessage());
        });
        $test->asyncHello("WORLD");
        $test->asyncHello("WORLD", function($result) {
            echo "result: ";
            var_dump($result);
        });
        $test->asyncHello("WORLD2", function($result) {
            echo "result: ";
            var_dump($result);
        });*/

        return false;
    }
}