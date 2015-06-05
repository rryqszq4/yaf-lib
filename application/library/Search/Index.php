<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午7:02
 */

class Search_Index {

    public $database = null;
    public $indexer = null;
    public $document = null;

    public function __construct($dbpath="gamedb"){
        try {
            $this->database = new XapianWritableDatabase(APPLICATION_PATH."/data/".$dbpath,
            Xapian::DB_CREATE_OR_OPEN);
            $this->indexer = new XapianTermGenerator();
        } catch (Exception $e){
            print $e->getMessage()."\n";
        }
    }

    public function add($query,$query_term){
        try {
            $this->document = new XapianDocument();
            $this->indexer->set_document($this->document);
            foreach ($query_term as $key=>$value){
                $this->indexer->index_text($value, 1, strtoupper($key));
                $this->indexer->index_text($value);
            }
            $this->document->set_data(json_encode($query));
            $this->document->add_term("Q"."lol_hero".$query['_id']);

            $this->database->add_document($this->document);

            $this->database->commit();
        }catch (Exception $e){
            print $e->getMessage()."\n";
        }
    }
}