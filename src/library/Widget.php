<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zhaoquan
 * Date: 14-2-17
 * Time: 下午2:50
 * To change this template use File | Settings | File Templates.
 * Widget类为全部widget组件的父类
 */

class Widget {

    /**
     * @var string 游戏数据库名称
     */
    public $object_code = '';

    /**
     * @var array 传入组件的参数
     */
    public $query = array();

    /**
     * @var array 需要向模板中返回的数据
     */
    private $data = array();

    /**
     * @var array 需要传入的辅助数据
     */
    public $assist_data = array();

    /**
     * @var string 子类名称标示
     */
    public $class_key = '';

    /**
     * @var string  输出方式，同步或异步
     */
    public $output = 'html';

    /**
     * @var null 调用WIDGET返回
     */
    protected $callback = NULL;

    /***
     * @var layout
     */
    public $layout = NULL;

    public $layout_tdk = array();

    public $class_name;

    public $view = '';

    public function __construct($option){
        foreach ($option as $k=>$v){
            $this->$k = $v;
        }
        $this->base_init();
        $this->run();
    }

    /**
     * 实例初始化，检测配置，设置子类关键字等
     */
    protected function base_init(){
        $this->check_config();
        $this->set_class_key();
    }

    /**
     * 实例化调用方法，传递需要的数据到模板
     */
    protected function run(){

        $_function_get = 'get_'.$this->class_key;
        #$_class_key = $this->object_code.'_'.$this->class_key;
        #$this->add_data($_class_key,$this->$_function_get());
        #DebugTools::print_r($this->$_function_get());

        if ($this->output == 'html'){
            $dir = Yaf_Registry::get('config')->application->directory.'/widgets/views/';
            $widget_view = new Yaf_View_Simple($dir);
            $widget_view->assign('data', $this->$_function_get());
            $widget_view->assign('assist_data', $this->assist_data);
            echo $widget_view->render($this->class_key.'.phtml');
            #DebugTools::print_r($res);
            #Yaf_Loader::getInstance()->import($dir.'/item_condition.phtml');
            #DebugTools::print_r($widget_view);

            /*if (empty($this->view)){$this->view = $this->class_key;}
            $this->render($this->view,array(
                'data'=>$this->$_function_get(),
                'assist_data'=>$this->assist_data
            ));*/
        }else if ($this->output == 'json')
            $this->output_json($this->$_function_get());
        else if ($this->output == 'array')
            $this->output_array($this->$_function_get());
    }

    /**
     * 检查配置是否存在
     * @throws CException Yii::app()->params['dbGame'] is not define.
     * @throws CException Yii::app()->params['default_object'] is not define.
     */
    public function check_config(){
        /*if (!isset(Yii::app()->params['dbGame']))
            throw new CException(Yii::t('yii',"Yii::app()->params['dbGame'] is not define."));
            #DebugTools::print_r("Yii::app()->params['dbGame'] is not define;");

        $this->set_object_code();
        if ($this->object_code == '')
            throw new CException(Yii::t('yii',"Yii::app()->params['default_object'] is not define."));
            #DebugTools::print_r("Yii::app()->params['default_object'] is not define;");
        */

    }

    /**
     * 设置object_code
     */
    public function set_object_code(){
        /*if ($this->object_code == '' && isset(Yii::app()->params['default_object']))
            $this->object_code = Yii::app()->params['default_object'];
        */
    }

    /**
     * 设置子类的类关键字
     * @throws CException function run is not call in widget class,because widget is a parent class.
     */
    public function set_class_key(){
        if (empty($this->class_key)){
            $class_name = get_class($this);
            $file_view_names = explode('widget',strtolower($class_name));
            if (!isset($file_view_names[0]) || empty($file_view_names[0]))
                #throw new CException(Yii::t('yii','function run is not call in widget class,because widget is a parent class.'));
            $this->class_key = $file_view_names[0];
        }
    }

    public function get_class_key(){
        return $this->class_key;
    }

    public function get_data($key){
        return $this->data[$key];
    }

    public function add_data($key,$value){
        $this->data[$key] = $value;
    }

    public function del_data($key){
        unset($this->data[$key]);
    }

    public function output_json(&$arr){
        $this->callback = json_encode($arr);
    }

    public function output_array(&$arr){
        $this->callback = $arr;
    }

    public function call_back(){
        return $this->callback;
    }

}