<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午7:02
 */

class Search_Index {

    public $database;
    public $indexer;
    public $document;

    public function __construct($dbpath="gamedb"){
        $this->database = new XapianWritableDatabase(APPLICATION_PATH."/data/".$dbpath,
        Xapian::DB_CREATE_OR_OPEN);
        $this->indexer = new XapianTermGenerator();
        $this->document = new XapianDocument();
    }

    public function run($query){
        foreach ($query as $key=>$value){
            
        }
    }
}