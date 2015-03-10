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
        $this->table = "entity_ff14_ClassJob";
    }

    public function getOne(){
        return $this->handler->findOne();
    }

    public function getList(){
        return $this->handler->find();
    }

    public function select(){
        return $this->handler->select();
    }

}