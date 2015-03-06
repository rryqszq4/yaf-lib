<?php
class IndexController extends Yaf_Controller_Abstract {
	
	public function indexAction(){

        $test = System_Mongo::GetInstance();


        //DebugTools::print_r($test);

		$this->getView()->assign("content",  "Hello World");
		return TRUE;
	}
}
?>
