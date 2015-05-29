<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-5-29
 * Time: 下午5:10
 */

class SwooleController extends Controller {


    public $_layout = null;

    public function clientAction(){

        $client = new swoole_client(SWOOLE_SOCK_TCP);
        if (!$client->connect('127.0.0.1', 9021, -1))
        {
            exit("connect failed. Error: {$client->errCode}\n");
        }
        $client->send("hello world\n");
        echo $client->recv();
        $client->close();

        return false;
    }
}