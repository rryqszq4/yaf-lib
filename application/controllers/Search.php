<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-8
 * Time: 下午9:01
 */

class SearchController extends Controller {

    public $enable_view = false;

    public function indexAction(){
        $server = new Jsonrpc_Server();

        $server->register('find',function($keywords){
            $segmenter = new Search_Segment();
            $matcher = new Search_Match('gamedb');

            $text = $segmenter->cutString($keywords);
            $data = $matcher->call($text,0,100);

            return $result = array('keywords'=>$text,'data'=>$data);
        });

        echo $server->execute();
    }

    public function clientAction(){
        $client = new Jsonrpc_Client('http://cha.internal.zhaoquan.com/search');
        $client->debug = true;
        $result = $client->execute('find',array('卡牌'));

        DebugTools::print_r($result);
    }
}