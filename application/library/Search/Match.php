<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-5
 * Time: 下午3:14
 */

class Search_Match {

    public $database;
    public $parser;   //查询分析器
    public $enquire;  //查询对象

    public $prefixes = array(
        "name","title","othername","displayName"
    );

    public function __construct($dbpath="gamedb"){
        $this->database = new XapianDatabase(APPLICATION_PATH."/data/".$dbpath);
        $this->parser = new XapianQueryParser();
        $this->enquire = new XapianEnquire($this->database);
    }

    public function call($querystring){
        foreach ($this->prefixes as $k=>$v){
            $this->parser->add_prefix($v,strtoupper($v));
        }

        $query = $this->parser->parse_query($querystring);
        var_dump($query->get_description());

        $this->enquire->set_query($query);

        $matches = $this->enquire->get_mset(0,10);
        var_dump($matches->get_matches_estimated());

        $start = $matches->begin();
        $end = $matches->end();
        $index = 0;

        while (!($start->equals($end))){
            $doc = $start->get_document();
            $docid = $start->get_docid();
            $fields = json_decode($doc->get_data());
            #var_dump($fields);
            $position = 0 + $index + 1;
            print sprintf("%d: #%03d %s\n",$position,$docid,$fields->title);
            $start->next();
            $index++;
        }

    }
}