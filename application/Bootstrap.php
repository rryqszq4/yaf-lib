<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
	
	public function _initConfig(){
		$arrConfig = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $arrConfig);
	}

    public function _initLoader(Yaf_Dispatcher $dispatcher) {

        Yaf_Loader::getInstance()->registerLocalNameSpace(array("System"));
    }

	public function _initRoute(Yaf_Dispatcher $dispatcher){
	
	}

    public function _initLayout(Yaf_Dispatcher $dispatcher){
        $layout = new LayoutPlugin('layout.phtml',APPLICATION_PATH.'/application/views/');

        Yaf_Registry::set('layout',$layout);

        $dispatcher->registerPlugin($layout);
    }

	public function _initView(Yaf_Dispatcher $dispatcher){
	
	}
}
?>
