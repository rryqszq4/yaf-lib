<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-4-7
 * Time: 下午7:34
 */

class System_Sort {


    static public function insertion($arr){
        $n = count($arr);
        for ($i = 1; $i < $n; $i++){
            $tmp = $arr[$i];
            for ($j = $i; $j > 0 && $tmp < $arr[$j-1]; $j--){
                $arr[$j] = $arr[$j-1];
            }
            $arr[$j] = $tmp;
        }
        return $arr;
    }
}