<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午2:25
 */

class XapianController extends Controller {

    public $_layout = null;

    public function indexAction(){
        echo "/xapian/index\n";

        $indexer = new Search_Index("gamedb");
        $segmenter = new Search_Segment();
        $query = $segmenter->query();
        foreach ($query as $key=>$value){
            $arr = $segmenter->cut($value);
            $indexer->add($value,$arr);
        }
        return false;
    }
}