<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-8
 * Time: 下午7:25
 */

class Sontroller extends Yaf_Controller_Abstract
{

    public function init(){
       if ($this->getRequest()->isCli()){
           Yaf_Dispatcher::getInstance()->returnResponse(true);
           Yaf_Dispatcher::getInstance()->disableView();
       }else {
           throw new Exception("Environment is not in CLI mode.\n");
       }
    }
}