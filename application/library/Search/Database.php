<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-8-13
 * Time: 下午5:21
 */

class Search_Database {

    private $database = null;
    private $document = null;

    public function __construct($dbpath="gamedb"){
        try {
            $config = Yaf_Application::app()->getConfig();
            $this->database = new XapianWritableDatabase($config->search->db->dir.$dbpath,
                Xapian::DB_CREATE_OR_OPEN);
        } catch(Exception $e){
            print $e->getMessage()."\n";
        }
    }

    public function __destruct(){
        $this->database->close();
    }

    public function get_doccount(){
        return $this->database->get_doccount();
    }

    public function get_data($did){
        return $this->database->get_document($did)->get_data();
    }

    public function get_termlist($did){
        $termlist = "";
        $doc = $this->database->get_document($did);

        $current = $doc->termlist_begin();
        while (!$current->equals($doc->termlist_end())){
            $termlist .= $current->get_term().' ';
            $current->next();
        }

        return $termlist;
    }

    public function select($offset=1,$limit=10){
        $query = array();
        $limit = $offset+$limit;
        for (; $offset < $limit; $offset++){
            $query[] = array(
                'did' => $offset,
                'data' => json_decode($this->get_data($offset)),
                'termlist' => $this->get_termlist($offset)
            );
        }
        return $query;
    }
}