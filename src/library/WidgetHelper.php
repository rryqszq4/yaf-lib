<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhaoquan
 * Date: 14-3-20
 * Time: 上午11:03
 * To change this template use File | Settings | File Templates.
 */

class WidgetHelper{

    static public function create_mongo_id($id){
        $yuan = '100000000000';
        $id = strval($id);
        $tmp_totle = strlen($id);

        $id = substr($yuan,0,strlen($yuan)-$tmp_totle).strval($id);
        $total = strlen($id);
        return substr(md5(substr($id,0,ceil($total/2))),0,24-$total).$id;
    }

    static public function create_new_id($mongo_id){
        return intval(substr($mongo_id,13,12));
    }

    static public function get_mongo_ids($ids){
        foreach ($ids as $k=>$v){
            $mongo_ids[$k] = new MongoId(trim($v));
        }
        return $mongo_ids;
    }

    static public function filter_194($str){
        return (ord($str)==194)?substr($str,2,strlen($str)-2):$str;
    }

    //获取客户端ip
    static function get_client_ip() {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }

    static public function filter_clean($str)
    {
        $str=trim($str);
        $str=strip_tags($str);
        $str=stripslashes($str);
        $str=quotemeta($str);
        $str=htmlspecialchars($str);
        #$str=preg_replace("/[[:punct:]]/",'',$str);//去除标点
        $str=preg_replace('/\+|\*|\`|\/|\-|\$|\#|\^|\!|\@|\%||\~|\^|\[|\]|\"|\:|\;|\=|\,|\<|\>|\.|\?|\{|\}/',"", $str);//去除特殊字符
        $str=preg_replace("/\s[\s]+/", "", $str);//去除空格、换行符、制表符
        return $str;
    }
}

function hs_attr_sort($a,$b){
    if ($a['sort'] == $b['sort']) return 0;
    return ($a['sort'] < $b['sort']) ? -1 : 1;
}