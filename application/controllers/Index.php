<?php
class IndexController extends Controller {
	
	public function indexAction(){

        $test = new TestModel();
        $test = $test->getOne();
        DebugTools::print_r($test);

        $test = System_Mongo::getInstance()->conn()
            ->selectDB('gamedb')
            ->selectCollection('entity_ff14_ClassJob')
            ->findOne(array('Key'=>intval(1)));

        DebugTools::print_r($test);

        $test = System_Mongo::getInstance()->conn()
            ->selectDB('gamedb')
            ->selectCollection('entity_ff14_ClassJob')
            ->select(array(),array(),array(),3);

        foreach ($test as $doc){
            DebugTools::print_r($doc);
        }



		$this->getView()->assign("content",  "Hello World");
		return TRUE;
	}
}
?>
