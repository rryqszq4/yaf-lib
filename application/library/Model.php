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

    public function __construct(){
        $this->init();

        if ($this->data_center == 'Mongo'){
            $system_dc = "System_".$this->data_center;
            $this->handler = $system_dc::getInstance()
                ->conn()
                ->selectDB($this->db)
                ->selectCollection($this->table);
        }else if ($this->data_center == 'Mysql'){

        }else {

        }
    }

    protected function init(){

    }
}