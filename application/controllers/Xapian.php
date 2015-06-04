<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-4
 * Time: 下午2:25
 */

class XapianController extends Controller {

    public $_layout = null;

    public function indexAction(){
        echo "/xapian/index\n";
        $se = new Search_Segment();
        $se->cut();
        return false;
    }
}