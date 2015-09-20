<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-3-12
 * Time: 上午11:37
 */
class IndexController extends Controller {

    public $_layout = 'admin_layout';

    public function IndexAction(){

        return true;
    }

    public function LoginAction(){
        return true;
    }

    public function SearchAction(){
        $client = new Jsonrpc_Client('http://yaf-lib.com/search/server');
        $client->debug = true;
        $result = $client->execute('find',array('lol'));
    }
}