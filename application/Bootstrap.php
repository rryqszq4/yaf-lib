<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
	
	public function _initConfig(){
		$arrConfig = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $arrConfig);
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher){
	
	}

	public function _initView(Yaf_Dispatcher $dispatcher){
	
	}
}
?>
