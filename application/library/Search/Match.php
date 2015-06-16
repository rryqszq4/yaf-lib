<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-5
 * Time: 下午3:14
 */

class Search_Match {

    public $database = null;
    public $parser = null;   //查询分析器
    public $enquire = null;  //查询对象

    public $prefixes = array(
        "name","title","othername","displayName"
    );

    public function __construct($dbpath="gamedb"){
        try {
            $this->database = new XapianDatabase(APPLICATION_PATH."/data/".$dbpath);
            $this->parser = new XapianQueryParser();
            $this->enquire = new XapianEnquire($this->database);
        }catch (Exception $e){
                print $e->getMessage()."\n";
        }
    }

    private function _get_enquire_mset($offset,$limit){
        return $this->enquire->get_mset($offset,$limit);
    }

    private function _get_hit_total(){
        $doc_count = $this->database->get_doccount();
        return $this->_get_enquire_mset(0,$doc_count)->size();
    }


    public function call($querystring,$offset=0,$limit=10){
        $data = array();

        try {
            foreach ($this->prefixes as $k=>$v){
                #$this->parser->add_prefix($v,strtoupper($v));
            }

            $query = $this->parser->parse_query($querystring);
            #var_dump($query->get_description());

            $this->enquire->set_query($query);

            $count = $this->_get_hit_total();

            if ($offset > $count){
                $offset = intval($count/$limit)*$limit;
            }

            #$this->enquire->set_docid_order(XapianEnquire::DESCENDING);
            #$this->enquire->set_sort_by_value_then_relevance(0,false);
            $matches = $this->_get_enquire_mset($offset,$limit);

            #var_dump($matches->get_matches_estimated());

            $start = $matches->begin();
            $end = $matches->end();
            $index = 0;

            while (!($start->equals($end))){
                $doc = $start->get_document();
                $docid = $start->get_docid();
                $fields = json_decode($doc->get_data());
                #var_dump($fields);
                $position = $offset + $index + 1;
                #print sprintf("%d: #%03d %s\n",$position,$docid,$fields->title);
                $data[] = $fields;
                $start->next();
                $index++;
            }#while
        }catch (Exception $e){
            print $e->getMessage()."\n";
        }

        return array('count'=>$count,'list'=>$data);
    }
}