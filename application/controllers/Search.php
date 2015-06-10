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
        Yaf_Dispatcher::getInstance()->returnResponse(false);
        Yaf_Dispatcher::getInstance()->enableView();

        $keyword = isset($_GET['wd']) ? trim($_GET['wd']) : '';
        $data = array();
        $keyword_cut = '';

        if (!empty($keyword)){
            $client = new Jsonrpc_Client('http://cha.internal.zhaoquan.com/search/server');
            $client->debug = true;
            $result = $client->execute('find',array($keyword));

            $keyword_cut = $result['keyword'];

            foreach ($result['data'] as $k=>$v){
                $configer = new Search_Config($v['app']);
                $data[$k] = array(
                    'title' => $configer->formatTitle($v['table'],$v,$keyword_cut),
                    'detail' => $configer->formatDetail($v['table'],$v,$keyword_cut),
                    'url' => $configer->formatUrl($v['table'],$v),
                    'image_url' => $configer->formatImage($v['table'],$v)
                );
            }

        }

        $this->getView()->assign('keyword',$keyword);
        $this->getView()->assign('keyword_cut',$keyword_cut);
        $this->getView()->assign('data',$data);

        return true;
    }

    public function serverAction(){
        $server = new Jsonrpc_Server();

        $server->register('find',function($keywords){
            $segmenter = new Search_Segment();
            $matcher = new Search_Match('gamedb');

            $text = $segmenter->cutString($keywords);
            $data = $matcher->call($text,0,12);

            return $result = array('keyword'=>$text,'data'=>$data);
        });

        echo $server->execute();
    }

    public function clientAction(){
        $client = new Jsonrpc_Client('http://cha.internal.zhaoquan.com/search/server');
        $client->debug = true;
        $result = $client->execute('find',array('卡牌'));

        DebugTools::print_r($result);
    }
}