<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-8-14
 * Time: 下午5:55
 */

class SearchController extends Controller {

    public $enable_view = false;

    public function IndexAction(){

        echo 123;
    }

    public function DataAction(){
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $database = new Search_Database();

        $offset = 15;
        $count = $database->get_doccount();

        $query = $database->select(($p-1)*$offset+1,$offset);
        $query = json_encode($query);
        echo $query;
    }

}