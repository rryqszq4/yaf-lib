<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-4-17
 * Time: 下午3:29
 */


class RpcController extends Controller {

    public function yarAction(){

        $server = new Yar_Server(new TestModel());
        $server->handle();

        return false;
    }

    public function yar_clientAction(){

        $client = new Yar_Client("http://yaf.zhaoquan.com/rpc/yar");


        $result = $client->getOne();

        DebugTools::print_r($result);

        return false;
    }

    public function yar_client_asyncAction(){
        Yar_Concurrent_Client::call("http://yaf.zhaoquan.com/rpc/yar", "getOne", array(), "RpcController::callback");
        Yar_Concurrent_Client::call("http://yaf.zhaoquan.com/rpc/yar", "getList", array(array('cost'=>8)), "RpcController::callback");

        Yar_Concurrent_Client::loop();
        return false;
    }

    static public function callback($ret, $callinfo) {
        echo $callinfo['method'] , " result: ", $ret , "\n";
        DebugTools::print_r($ret);
    }
}