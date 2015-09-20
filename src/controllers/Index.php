<?php
class IndexController extends Controller {



	public function indexAction(){

        /*$this->getRequest()->widget = function(){
            echo 'call widget';
        };*/
        #DebugTools::print_r($this->getRequest());

        $test_model = new TestModel();

        $test = $test_model->getList(array('cost'=>8));
        DebugTools::print_r($test);
        /*foreach ($test as $k=>$v){
            DebugTools::print_r($v);
        }*/

        #System_Memcache::set('test1','12346',60);
        #DebugTools::print_r(System_Memcache::get('test1'));

        #$version = System_Memcache::getInstance()->getVersion();
        #DebugTools::print_r($version);

        #$stats = System_Memcache::getInstance()->getStats();
        #DebugTools::print_r($stats);

        #$server_status = System_Memcache::getInstance()->getServerStatus('127.0.0.1',11211);
        #DebugTools::print_r($server_status);

        #DebugTools::print_r(System_Memcache::getExtendedStats());

        #DebugTools::print_r($test_model->dc_close());



        /*$test = System_Mongo::getInstance()->conn()
            ->selectDB('gamedb')
            ->selectCollection('entity_ff14_ClassJob')
            ->findOne(array('Key'=>intval(1)));

        DebugTools::print_r($test);
        $a = System_Mongo::getInstance()->close();
        DebugTools::print_r($a.'a');

        $test = System_Mongo::getInstance()->conn()
            ->selectDB('gamedb')
            ->selectCollection('entity_ff14_ClassJob')
            ->select(array(),array(),array(),3);

        foreach ($test as $doc){
            DebugTools::print_r($doc);
        }*/






		$this->getView()->assign("content",  "Hello World");
		return TRUE;
	}

    public function sortAction(){
        $arr = array(7,2,1,9,4,6,3);
        $arr = System_Sort::insertion($arr);
        DebugTools::print_r($arr);

        $arr = array(5,2,3,8,1);
        $arr = System_Sort::insertion($arr);
        DebugTools::print_r($arr);

        $arr = array(7,2,1,9,4,6,3);
        $arr = System_Sort::bubble($arr);
        DebugTools::print_r($arr);
        return false;
    }
}
?>
