<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午2:25
 */

class XapianController extends Sontroller {

    public $_layout = null;
    public $table = '';

    public function indexAction(){
        echo "/xapian/index\n";

        $this->table = 'hero';

        $config = new Search_Config('lol');
        $indexer = new Search_Index("gamedb");
        $segmenter = new Search_Segment();

        $indexer->setIdPrefix(
            $config->getApp(),
            $this->table,
            $config->getPrimaryKey($this->table)
        );

        $query = $segmenter->query();

        //=> Search_Index::add /*
        foreach ($query as $key=>$value){
            #$arr = $segmenter->cutQuery($value,$config->getIndex($this->table));
            /*$indexer->add($value,
                $arr,
                array($config->getApp(),
                    $config->getAppName(),
                    $this->table,
                    $config->getTableName($this->table)
                )
            );*/
        }
        // */

        //=> Search_Index::al /*
        foreach ($query as $key=>$value){
            $alert_data = array();
            $arr = $segmenter->cutQuery($value,$config->getIndex($this->table));
            #$alert_data[$config->getPrimaryKey($this->table)] = $value[$config->getPrimaryKey($this->table)];
            #foreach ($config->getIndex($this->table) as $k=>$v){
            #    $alert_data[$v] = $value[$v];
            #}
            $indexer->alert($value,
                $arr,
                array($config->getApp(),
                    $config->getAppName(),
                    $this->table,
                    $config->getTableName($this->table)
                )
            );
        }
        // */

        //=> Search_Index::delete
        /*
        foreach ($query as $key=>$value){
            $indexer->delete($value);
        }
        // */


        return false;
    }

    public function matchAction(){
        $matcher = new Search_Match("gamedb");
        $matcher->call("卡牌",12,12);

        return false;
    }

    public function queryAction(){
        $indexer = new Search_Segment();
        $query = $indexer->queryOne();
        DebugTools::print_r($query);

        return false;
    }

    public function configAction(){
        $config = new Search_Config('lol');
        DebugTools::print_r($config);
        return false;
    }

    public function databaseAction(){
        $database = new Search_Database();
        DebugTools::print_r($database->get_doccount());
        DebugTools::print_r($database->get_data(1));
        DebugTools::print_r($database->get_termlist(1));
        DebugTools::print_r($database->select());
    }

    public function testAction(){
        //$config = Yaf_Registry::get('config');
        $database = new XapianDatabase("/develop/cha.internal.zhaoquan.com/service/data/gamedb");
        $indexer = new XapianTermGenerator();
        $document = new XapianDocument();


        var_dump($database->get_lastdocid());
        var_dump($database->get_doccount());

        $a = $document->get_docid();
        var_dump($database->get_document(2)->termlist_count());

        $i = $database->get_document(50000)->termlist_begin();
        while (!$i->equals($database->get_document(50000)->termlist_end())){
            var_dump($i->get_term());
            $i->next();
        }


        var_dump($i);
        /*$i = $database->termlist_begin(1);
        while (!$i->equals($database->termlist_end(10))){
            var_dump($i->get_description());
            $i->next();
        }*/

    }

}