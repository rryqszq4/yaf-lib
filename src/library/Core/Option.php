<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-9-21
 * Time: 上午12:21
 */

class Core_Option {

    private $small = "c:a:";

    private $long = array();

    private $help = "help";

    public function __construct(){

    }

    public function run(){
        $opt = getopt($this->small, $this->long);
        return $opt;
    }

    private function helpFun(){

    }

}