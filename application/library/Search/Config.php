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
    const IMAGE_URL = 'image_url';
    const SORT_ID = 'sort_id';

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

    public function getSortId($table){
        $value = $this->_getValue($table);
        return $value[self::SORT_ID];
    }

    public function formatTitle($table,$data,$wd){
        $value = $this->_getValue($table);
        $title = '';
        foreach ($value[self::TITLE] as $k=>$v){
            if (is_array($data)){
                $title .= $data[$v].' ';
            }else {
                $title .= $data->$v.' ';
            }
        }
        $title .= ' - '.$this->getTableName($table).
                  ' - '.$this->getApp().
                  ' - '.$this->getAppName();

        $title = $this->_replaceWord($title,$wd);
        return $title;
    }

    public function formatDetail($table,$data,$wd){
        $value = $this->_getValue($table);
        $detail = '';

        foreach ($value[self::DETAIL] as $k=>$v){
            if (is_array($data)){
                $detail .= $this->_plusquery($data[$v]," ");
            }else {
                $detail .= $this->_plusquery($data->$v," ");
            }

            /*if (is_string($data[$v])){
                $detail .= $data[$v].' ';
            }
            if (is_array($data[$v])){
                foreach ($data[$v] as $kk=>$vv){
                    $detail .= $vv.' ';
                }
            }*/
        }
        $detail = str_replace(array('<br>','<br />','<br/>'),' ',$detail);
        #$detail = strip_tags($detail);
        $detail = $this->_replaceWord($detail,$wd);
        return $detail;
    }

    public function formatUrl($table, $data){
        $value = $this->_getValue($table);
        if (is_array($data)){
            $tmp = $data[$value[self::URL][1]];
        }else {
            $tmp = $data->$value[self::URL][1];
        }
        $url = str_replace('{*}',$tmp,$value[self::URL][0]);
        return $url;
    }

    public function formatImage($table,$data){
        $value = $this->_getValue($table);
        if (is_array($data)){
            $tmp = $data[$value[self::IMAGE_URL][1]];
        }else {
            $tmp = $data->$value[self::IMAGE_URL][1];
        }
        $image_url = str_replace('{*}',$tmp,$value[self::IMAGE_URL][0]);
        return array($image_url,$value[self::IMAGE_URL][2]);
    }

    private function _replaceWord($text,$word){
        $word = explode(" ",$word);
        foreach ($word as $k=>$v){
            $text = str_replace(trim($v),"<em>{$v}</em>",$text);
        }
        return $text;
    }

    private function _plusquery($query,$delimiter=' '){
        if (is_string($query)){
            return $query.$delimiter;
        }
        if (is_array($query)){
            $tmp = '';
            foreach ($query as $k=>$v){
                $tmp.= $this->_plusquery($v).$delimiter;
            }
            return $tmp;
        }
        return '';
    }
}