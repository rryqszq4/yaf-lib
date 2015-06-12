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

}