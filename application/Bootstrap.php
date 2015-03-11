<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{

    private $_config;

	public function _initConfig(){
		$this->_config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $this->_config);
	}

    public function _initLoader(Yaf_Dispatcher $dispatcher) {

        Yaf_Loader::getInstance()->registerLocalNameSpace(array("System"));
        Yaf_Loader::getInstance()->import($this->_config->application->directory.'/widgets/Index.php');
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
