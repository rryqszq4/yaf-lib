<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends Yaf_Controller_Abstract
{

    public $_layout = 'layout';

    public function init(){

    }

    public function set_layout(){
        if ($this->_layout != 'layout'){
            $layout = Yaf_Registry::get('layout');
            $layout->setFile($this->_layout.'.phtml');
        }
    }
}