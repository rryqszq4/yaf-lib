<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-6
 * Time: 上午10:59
 */

class Search_Config {

    const APP = 'app';
    const APP_NAME = 'app_name';
    const APP_ID = 'app_id';
    const INDEX_FIELDS = 'search_index';
    const PRIMARY_KEY = 'primary_key';
    const CN_NAME = 'cn_name';
    const TITLE = 'title';
    const DETAIL = 'detail';
    const URL = 'url';

    private static $instance;
    private $config = null;


    public function __construct($app="lol",$db="gamedb"){
        $file = APPLICATION_PATH.'/conf/'.$db.'/'.$app.'.php';
        if (file_exists($file)){
            $this->config = include($file);
        }else {
        }
    }

    private function _getValue($key){
        return $this->config[$key];
    }

    public function getApp(){
        return $this->_getValue(self::APP);
    }

    public function getAppName(){
        return $this->_getValue(self::APP_NAME);
    }

    public function getAppId(){
        return $this->_getValue(self::APP_ID);
    }

    public function getTable($table){
        return $this->_getValue($table);
    }

    public function getIndex($table){
        $value = $this->_getValue($table);
        return $value[self::INDEX_FIELDS];
    }

    public function getPrimaryKey($table){
        $value = $this->_getValue($table);
        return $value[self::PRIMARY_KEY];
    }

    public function getTableName($table){
        $value = $this->_getValue($table);
        return $value[self::CN_NAME];
    }

    public function formatTitle($table,$data){
        $value = $this->_getValue($table);
        $title = '';
        foreach ($value[self::TITLE] as $k=>$v){
            $title .= $data[$v].' ';
        }
        return $title;
    }
}