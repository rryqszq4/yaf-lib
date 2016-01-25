<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends Yaf_Controller_Abstract
{

    public $_layout = 'layout';
    public $_enable_view = false;

    public function init(){
        if (!$this->getRequest()->isCli()){
            if ($this->_enable_view){
                $this->_regWidget();
                $this->_setLayout();
            }else {
                Yaf_Dispatcher::getInstance()->returnResponse(true);
                Yaf_Dispatcher::getInstance()->disableView();
                $this->_removeLayout();
            }
        }else {
            try {
                throw new Yaf_Exception("Environment is not in WEB mode.\n");
            } catch (Yaf_Exception $e){
                print $e->getMessage();
                exit(1);
            }
        }
    }

    public function _regWidget(){
        $this->getView()->widget = function($widget_name=null,$widget_option=array()){
            $widget = new $widget_name($widget_option);
            #DebugTools::print_r($widget);
        };
    }


    public function _setLayout(){
        $config = Yaf_Registry::get('config');
        if (!$this->_layout){
            $layout = Yaf_Registry::get('layout');
            $layout->setFile($this->_layout.'.'.$config['application']['view']['ext']);
        }else {
            if ($this->_layout != 'layout'){
                $layout = Yaf_Registry::get('layout');
                $layout->setFile($this->_layout.'.'.$config['application']['view']['ext']);
            }
        }
    }

    public function _removeLayout(){
        $layout = Yaf_Registry::get('layout');
        $layout->setFile(false);
    }
}