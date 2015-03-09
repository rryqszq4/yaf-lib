<?php
class IndexController extends Controller {
	
	public function indexAction(){

        new Model();

        $test = System_Mongo::GetInstance()->conn()
            ->selectDB('gamedb')
            ->selectCollection('entity_ff14_ClassJob')
            ->findOne(array('Key'=>intval(1)));

        DebugTools::print_r($test);

        $test = System_Mongo::GetInstance()->conn()
            ->selectDB('gamedb')
            ->selectCollection('entity_ff14_ClassJob')
            ->find(array());

        foreach ($test as $doc){
            DebugTools::print_r($doc);
        }



		$this->getView()->assign("content",  "Hello World");
		return TRUE;
	}
}
?>
