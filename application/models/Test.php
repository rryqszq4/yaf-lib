<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-3-9
 * Time: 下午2:55
 */

class TestModel extends Model{

    public $data_center;
    public $db;
    public $table;
    private $handle;

    public function init(){
        $this->data_center = "Mongo";
        $this->db = "gamedb";
        $this->table = "entity_hs_card_zhcn";
    }

    public function getOne(){
        return $this->handler->findOne();
    }

    public function getList($query){
        $result = $this->handler->find($query);
        $result = self::rebuild_list($result);
        return $result;
    }

    public function select(){
        return $this->handler->select();
    }

    public function e(){
        return "123";
    }

    static public function rebuild_list($data){
        $tmp = array();
        foreach ($data as $k=>$v){
            $tmp[$k] = $v;
        }
        return $tmp;
    }

}