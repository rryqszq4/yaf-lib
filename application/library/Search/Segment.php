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

    public $tool;

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
        scws_set_ignore($this->tool, false);
        scws_set_multi($this->tool,self::SCWS_MULTI_SHORT);
        scws_set_duality($this->tool, false);

    }

    public function close(){
        scws_close($this->tool);
    }

    public function query($model="SimpleModel"){
        $m = new $model;
        $list = $m->getOne();
        return $list;
    }

    public function cut($model="SimpleModel"){
        $query = $this->query($model);
        foreach ($query as $k=>$v){
            if (in_array($k,$model::$index_fields)){
                scws_send_text($this->tool,$v);
                #$top = scws_get_tops($this->tool);
                while($tmp = scws_get_result($this->tool)){
                    DebugTools::print_r($tmp);
                }
            }
        }

    }
}