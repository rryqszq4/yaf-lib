<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午7:02
 */

class Search_Index {

    const ID_PREFIX = 'Q';
    const DEFAULT_PRIMARY_KEY = '_id';

    private $database = null;
    private $indexer = null;
    private $document = null;
    private $idprefix = '';
    private $primarykey = '';

    public function __construct($dbpath="gamedb"){
        try {
            $this->database = new XapianWritableDatabase(APPLICATION_PATH."/data/".$dbpath,
            Xapian::DB_CREATE_OR_OPEN);
            $this->indexer = new XapianTermGenerator();
        } catch (Exception $e){
            print $e->getMessage()."\n";
        }
    }

    public function __destruct(){
        $this->database->close();
    }

    public function setIdPrefix($app='',$table='',$primary_key=''){
        $this->idprefix = self::ID_PREFIX.$app.$table;
        if (empty($primary_key))
            $this->primarykey = self::DEFAULT_PRIMARY_KEY;
        else
            $this->primarykey = $primary_key;
    }

    private function _getIdTerm($id){
        return $this->idprefix.$id;
    }

    public function add($query,$query_term,$other_term=array(),$sort_id=0){
        try {
            $this->document = new XapianDocument();
            $this->indexer->set_document($this->document);
            foreach ($query_term as $key=>$value){
                //$this->indexer->index_text($value, 1, strtoupper($key));
                $this->indexer->index_text($value);
                $this->indexer->increase_termpos();
            }

            if (!empty($other_term)){
                foreach ($other_term as $key=>$value){
                    $this->indexer->index_text($value);
                    $this->indexer->increase_termpos();
                }
            }

            $this->document->set_data(json_encode($query));

            if (!empty($sort_id)){
                $this->document->add_value(0,Xapian::sortable_serialise(intval($sort_id)));
            }
            $id_term = $this->_getIdTerm($query[$this->primarykey]);
            $this->document->add_term($id_term);

            $this->database->add_document($this->document);

            $this->database->commit();
        }catch (Exception $e){
            print $e->getMessage()."\n";
        }
    }

    public function alert($query,$query_term,$other_term=array(),$sort_id=0){
        try {
            $this->document = new XapianDocument();
            $this->indexer->set_document($this->document);
            foreach ($query_term as $key=>$value){
                //$this->indexer->index_text($value, 1, strtoupper($key));
                $this->indexer->index_text($value);
                #$this->indexer->increase_termpos();
            }

            if (!empty($other_term)){
                foreach ($other_term as $key=>$value){
                    $this->indexer->index_text($value);
                    #$this->indexer->increase_termpos();
                }
            }

            $this->document->set_data(json_encode($query));

            if (!empty($sort_id)){
                $this->document->add_value(0,Xapian::sortable_serialise(intval($sort_id)));
            }

            $id_term = $this->_getIdTerm($query[$this->primarykey]);
            $this->document->add_term($id_term);

            $this->database->replace_document($id_term,$this->document);

            $this->database->commit();
        }catch (Exception $e){
            print $e->getMessage()."\n";
        }
    }

    public function delete($query){
        try {
            $id_term = $this->_getIdTerm($query[$this->primarykey]);
            $this->database->delete_document($id_term);
            $this->database->commit();
        }catch (Exception $e){
            print $e->getMessage()."\n";
        }
    }


}