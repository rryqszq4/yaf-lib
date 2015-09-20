<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-3-9
 * Time: 上午11:32
 */

class Model {

    protected $data_center;

    protected $db;

    protected $table;

    protected $handler;

    protected static $index_fields=array();

    public function __construct($table=""){
        $this->init($table);

        $this->set_handler();
    }

    protected function init(){

    }

    protected function set_handler(){
        if ($this->data_center == 'Mongo'){
            $system_dc = "System_".$this->data_center;
            $this->handler = $system_dc::getInstance()
                ->selectDB($this->db)
                ->selectCollection($this->table);
        }else if ($this->data_center == 'Mysql'){

        }else {

        }
    }

    public function __destruct(){
        $this->dc_close();
    }

    public function dc_close(){
        if ($this->data_center == 'Mongo'){
            $system_dc = "System_".$this->data_center;
            return $system_dc::getInstance()->close();
        }else if ($this->data_center == 'Mysql'){

        }else {

        }
    }

}