<?php
class IndexController extends Controller {



	public function indexAction(){


        /*$this->getRequest()->widget = function(){
            echo 'call widget';
        };*/
        #DebugTools::print_r($this->getRequest());

        $test_model = new TestModel();

        $test = $test_model->getOne();
        DebugTools::print_r($test);
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
}
?>
