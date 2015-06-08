<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午3:57
 */

class Search_Segment {

    const ROOT = "/usr/local/scws/";
    const SCWS_XDICT_TXT = SCWS_XDICT_TXT;
    const SCWS_XDICT_XDB = SCWS_XDICT_XDB;
    const SCWS_XDICT_MEM = SCWS_XDICT_MEM;

    const SCWS_MULTI_SHORT = SCWS_MULTI_SHORT;
    const SCWS_MULTI_DUALITY = SCWS_MULTI_DUALITY;
    const SCWS_MULTI_ZMAIN = SCWS_MULTI_ZMAIN;
    const SCWS_MULTI_ZALL = SCWS_MULTI_ZALL;

    public $tool = null;
    private $filter = array('p','uj','c','f','d','r','m');

    public function __construct($charset = "utf8"){
        $this->tool = scws_open();
        $this->init();
        return $this->tool;
    }

    public function __destruct(){
        $this->close();
    }

    public function init(){
        scws_set_charset($this->tool,"utf8");
        scws_set_dict($this->tool, self::ROOT."etc/dict.utf8.xdb");
        scws_set_rule($this->tool, self::ROOT."etc/rules.utf8.ini");
        scws_set_ignore($this->tool, true); //清楚标点
        scws_set_multi($this->tool,self::SCWS_MULTI_DUALITY);
        #scws_set_multi($this->tool,self::SCWS_MULTI_SHORT);
        scws_set_duality($this->tool, false);

    }

    public function close(){
        scws_close($this->tool);
    }

    public function query($model="SimpleModel"){
        $m = new $model;
        $list = $m->getList();
        return $list;
    }

    public function queryOne($model = "SimpleModel"){
        $m = new $model;
        $one = $m->getOne();
        return $one;
    }

    public function cutQuery($queryOne,$fields=null){
        if (empty($fields)) {
            $fields = SimpleModel::$index_fields;
        }

        $text_arr = array();
        foreach ($queryOne as $k=>$v){
            $text = "";
            if (in_array($k,$fields)){
                //echo $v."==>";
                scws_send_text($this->tool,$v);
                //$top = scws_get_tops($this->tool);
                while($tmp = scws_get_result($this->tool)){
                    foreach ($tmp as $kk=>$vv){
                        if (!in_array($vv['attr'],$this->filter)){
                            //echo $vv['word']." ";
                            $text .= $vv['word']." ";
                        }
                    }#foreach
                }#while
                //echo "\n\n";
                $text_arr[$k] = $text;
            }#if
        }#foreach
        return $text_arr;
    }

    public function cutString($string){
        $text = '';
        scws_send_text($this->tool, $string);
        while ($tmp = scws_get_result($this->tool)){
            foreach ($tmp as $k=>$v){
                if (!in_array($v['attr'],$this->filter)){
                    $text .= $v['word'];
                }
            }
        }
        return $text;
    }
}