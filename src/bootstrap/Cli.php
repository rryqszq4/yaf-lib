<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{

    private $_config;
    private $_option;

    public function _initOption(Yaf_Dispatcher $dispatcher){
        $option = new Core_Option();
        $this->_option = $option->run();
    }

    public function _initConfig(Yaf_Dispatcher $dispatcher){
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $this->_config);
    }

    public function _initRequest(Yaf_Dispatcher $dispatcher){
        #if (!empty($this->_option) && !empty($this->_option['c']) && !empty($this->_option['a']))
        #    $dispatcher->getRequest()->setRequestUri($this->_option['c']."/".$this->_option['a']);
        $dispatcher->getRequest()->setRequestUri(basename(APPLICATION_INDEX,".php")."/main");
    }

    public function _initLoader(Yaf_Dispatcher $dispatcher) {

        Yaf_Loader::getInstance()->registerLocalNameSpace(array("System"));
        Yaf_Loader::getInstance()->import($this->_config->application->directory.'/widgets/Index.php');
        Yaf_Loader::import($this->_config->application->directory."/tools/xapian/xapian.php");

    }

    public function _initRoute(Yaf_Dispatcher $dispatcher){
        $router = Yaf_Dispatcher::getInstance()->getRouter();

        //$router->addConfig($this->_config->routes);
        //DebugTools::print_r($dispatcher);
        /*$matches = array();
        if (preg_match('/^\/([A-Za-z0-9_\-\.]+)[\/]?([A-Za-z0-9_\-\.]*)[\/]?([A-Za-z0-9_\-\.]*).*$/',$_SERVER['REQUEST_URI'],$matches)){
            #DebugTools::print_r($matches);
        }
        $route = new Yaf_Route_Regex('#^/xyq/#',
            array('controller'=>isset($matches[2])&&!empty($matches[2]) ? $matches[2] : 'index',
            'action'=>isset($matches[3])&&!empty($matches[3]) ? $matches[3] : 'index'
            )
        );
        $router->addRoute('xyq', $route);
        */
        //DebugTools::print_r($router);
    }
}

?>