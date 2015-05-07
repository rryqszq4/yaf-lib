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

    static public function selection($arr){
        $n = count($arr);
        for ($i=0; $i < $n-1; $i++){
            for ($j=$i+1,$least=$i; $j < $n; $j++){
                if ($arr[$j] < $arr[$least])
                    $least = $j;
            }
            $tmp = $arr[$i];
            $arr[$i] = $arr[$least];
            $arr[$least] = $tmp;
        }
        return $arr;
    }

    static public function bubble($arr){
        $n = count($arr);
        for ($i = 0; $i < $n-1; $i++){
            for ($j = $n-1; $j > $i; --$j){
                if ($arr[$j] < $arr[$j-1]){
                    $tmp = $arr[$j];
                    $arr[$j] = $arr[$j-1];
                    $arr[$j-1] = $tmp;
                }
            }
        }
        return $arr;
    }

    static private function al_merge($a,$b){
        $c = array();
        while (count($a) && count($b)){
            $c[] = $a[0] < $b[0] ? array_shift($a) : array_shift($b);
        }
        return array_merge($c,$a,$b);
    }

    static public function merge($arr){
        $count = count($arr);
        if ($count <= 1)
            return $arr;
        $mid = intval($count/2);
        $left = array_slice($arr,0,$mid);
        $right = array_slice($arr,$mid);
        $left = self::merge($left);
        $right = self::merge($right);
        $arr = self::al_merge($left,$right);
        return $arr;
    }
}