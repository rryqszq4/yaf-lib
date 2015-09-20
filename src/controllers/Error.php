<?php
/**
 * @name ErrorController
 * @desc   错误控制器, 在发生未捕获的异常时刻被调用
 * @see    http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author chenzhidong
 */
class ErrorController extends Controller {

    public function init(){}
    //从2.1开始, errorAction支持直接通过参数获取异常
    public function errorAction($exception) {
        //1. assign to view engine
        //DebugTools::print_r($exception);
        echo json_encode(array(
            'state'=>1,
            'code'=>null,
            'msg'=>array(
                'code'=>$exception->getCode(),
                'file'=>$exception->getFile(),
                'line'=>$exception->getLine(),
                'message'=>$exception->getMessage()
            )
        ));
        return false;
        //$this->_view->assign("message", $exception->getMessage());
        //return true;
    }
}