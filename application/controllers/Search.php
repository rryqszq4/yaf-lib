<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-8
 * Time: 下午9:01
 */

class SearchController extends Controller {

    public $enable_view = false;

    private $_offset = 0;
    private $_limit = 12;

    public function indexAction(){
        Yaf_Dispatcher::getInstance()->returnResponse(false);
        Yaf_Dispatcher::getInstance()->enableView();

        $keyword = isset($_GET['wd']) ? trim($_GET['wd']) : '';
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $data = array();
        $keyword_cut = '';
        $count = 0;
        $timed = 0;

        if (!empty($keyword)){
            $start_time = microtime(true);
            $this->_offset = $p-1 >= 0 ? ($p-1)*$this->_limit:0;

            $client = new Jsonrpc_Client('http://cha.internal.zhaoquan.com/search/server');
            $client->debug = true;
            $result = $client->execute('find',array($keyword,$this->_offset,$this->_limit));

            $keyword_cut = $result['keyword'];

            $count = $result['count'];
            foreach ($result['list'] as $k=>$v){
                $configer = new Search_Config($v['app']);
                $data[$k] = array(
                    'title' => $configer->formatTitle($v['table'],$v,$keyword_cut),
                    'detail' => $configer->formatDetail($v['table'],$v,$keyword_cut),
                    'url' => $configer->formatUrl($v['table'],$v),
                    'image_url' => $configer->formatImage($v['table'],$v)
                );
            }

            $end_time = microtime(true);
            $timed =  round($end_time-$start_time ,4);

            $pagePattern = "p=(:num)";
            $paginator = new Paginator;
            $url = $paginator->getUri();
            $paginator
                ->setUrl($url, $pagePattern)
                ->setPrevNextTitle('上一页','下一页')
                ->setFirstLastTitle('首页','尾页')
                ->setItems($count, $this->_limit);
            $p_html = $paginator->toSimpleHtml();
        }


        $this->getView()->assign('keyword',$keyword);
        $this->getView()->assign('keyword_cut',$keyword_cut);
        $this->getView()->assign('count',$count);
        $this->getView()->assign('timed',$timed);
        $this->getView()->assign('data',$data);
        $this->getView()->assign('p_html',$p_html);

        return true;
    }

    public function serverAction(){
        $server = new Jsonrpc_Server();

        $server->register('find',function($keywords,$offset=0,$limit=12){
            $segmenter = new Search_Segment();
            $matcher = new Search_Match('gamedb');

            $text = $segmenter->cutString($keywords);
            $data = $matcher->call($text,$offset,$limit);

            $data['keyword'] = $text;
            return $data;
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