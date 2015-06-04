<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午4:35
 */

class SimpleModel extends Model{

    public $data_center;
    public $db;
    public $table;
    private $handle;

    public static $index_fields = array(
        "name","title","othername","displayName","filtersex","story",
        "useskill","pkskill","editersaid","startintro","centerintro",
        "lateintro","recommendskillsintro","runeintro","talentintro",
        "summonerskillintro","lesson"
    );

    public function init($table){
        $this->data_center = "Mongo";
        $this->db = "gamedb";
        $this->table = empty($table)?"entity_lol_hero":$table;
    }

    public function getOne($query=array()){
        return $this->handler->findOne($query);
    }

    public function getList($query=array()){
        $result = $this->handler->find($query);
        $result = self::rebuild_list($result);
        return $result;
    }

    public function select($query=array(),$fields=array(),$sort=array()){
        return $this->handler->select($query,$fields,$sort);
    }

    public function edg(){
        return "1234";
    }

    static public function rebuild_list($data){
        $tmp = array();
        foreach ($data as $k=>$v){
            $tmp[$k] = $v;
        }
        return $tmp;
    }

}