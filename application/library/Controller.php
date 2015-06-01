<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends Yaf_Controller_Abstract
{

    public $_layout = 'layout';

    public function init(){

        $this->_regWidget();
        $this->_setLayout();
    }

    public function _regWidget(){
        $this->getView()->widget = function($widget_name=null,$widget_option=array()){
            $widget = new $widget_name($widget_option);
            #DebugTools::print_r($widget);
        };
    }


    public function _setLayout(){
        if ($this->getRequest()->method != 'CLI'){
            if (!$this->_layout){
                $layout = Yaf_Registry::get('layout');
                $layout->setFile($this->_layout);
            }else {
                if ($this->_layout != 'layout'){
                    $layout = Yaf_Registry::get('layout');
                    $layout->setFile($this->_layout.'.phtml');
                }
            }
        }
    }
}