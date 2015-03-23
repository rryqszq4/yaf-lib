<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-2-17
 * Time: 下午5:43
 * To change this template use File | Settings | File Templates.
 */

class DebugTools {

    static private $start_time,$end_time,$start_mem,$end_mem;

    static public function print_r($val=NULL){
        echo "<p style='margin: 0;padding: 0;'>==================== DebugTools::print_r start; ===================</p>";
        echo "<pre style='margin: 0;padding: 0;color: #dd1144;white-space: pre-wrap;word-wrap: break-word;'>";
        if (is_array($val))
            print_r($val);
        else if (is_string($val))
            echo $val;
        else
            var_dump($val);
        echo "</pre>";
        echo "<p style='margin: 0;padding: 0;'>==================== DebugTools::print_r end; ====================</p><br />";
    }

    static public function var_dump($val){
        echo "<pre>";
        var_dump($val);
        echo "</pre>";
    }

    static private function getmicrotime(){
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }

    static public function startTime(){
        self::$start_time = self::getmicrotime();
    }

    static public function useTime(){
        self::$end_time = self::getmicrotime();
        self::print_r('<span style="color:#dd1144;">time: '.(float)(self::$end_time - self::$start_time).' s'.'</span>');
    }

    static public function startMemory(){
        self::$start_mem = memory_get_usage();
    }

    static public function useMemory(){
        self::$end_mem = memory_get_usage();
        self::print_r('<span style="color:#dd1144;">memory: '.((float)(self::$end_mem - self::$start_mem)/(1024*1024)).' Mb'.'</span>');
    }

    static public function startTimeMemory(){
        self::startTime();
        self::startMemory();
    }

    static public function useTimeMemory(){
        self::useTime();
        self::useMemory();
    }

}