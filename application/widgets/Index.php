<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-3-11
 * Time: 上午11:12
 */

class IndexWidget extends Widget {

    public function get_item_condition(){
        DebugTools::print_r('get_item_condition called');

        return array(
            'test'=>'test data'
        );
    }
}