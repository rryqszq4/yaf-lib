<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rryqszq4
 * Date: 15-3-6
 * Time: 下午3:57
 * To change this template use File | Settings | File Templates.
 */
class System_Mongo {
    /**
     * Description:（1）静态变量，保存全局实例，跟类绑定，跟对象无关
     *             （2）私有属性，为了避免类外直接调用 类名::$instance，防止为空
     */
    private static $instance;

    private $connection;

    /**
     * Description:数据库连接句柄
     */
    private $db;

    private $collection;

    /**
     * Description:私有化构造函数，防止外界实例化对象
     */
    private function __construct()
    {
        $this->conn();
    }

    /**
     * Description:私有化克隆函数，防止外界克隆对象
     */
    private function __clone()
    {
    }

    /**
     * Description:静态方法，单例访问统一入口
     * @return System_Mongo：返回应用中的唯一对象实例
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function conn(){
        $config = Yaf_Application::app()->getConfig();
        $options = array();
        $options['connect'] = $config->mongo->connect;
        !empty($config->mongo->username) ? $options['username'] = $config->mongo->username : "";
        !empty($config->mongo->password) ? $options['password'] = $config->mongo->password : "";
        $options['db'] = $config->mongo->db;
        $this->connection = new MongoClient($config->mongo->server, $options);
		return $this;
    }

	public function selectDB($dbname){
        $this->db = $this->connection->selectDB($dbname);
		return $this;
    }

    public function selectCollection($collection){
        $this->collection = $this->db->selectCollection($collection);
		return $this;
    }

    public function findOne($query=array(), $fields=array()){
        $data = $this->collection->findOne($query,$fields);
        return $data;
    }

    public function find($query=array(), $fields=array()){
        $cursor = $this->collection->find($query, $fields);
        return $cursor;
    }

    public function select($query=array(),$fields=array(),$sort=array(),$limit=0,$skip=0){
        // 得到集合
        $col = $this->collection;
        // 结果集偏历
        $cursor  = $col->find($query,$fields);
        // 排序
        if($sort){
            $cursor->sort($sort);
        }
        // 跳过记录数
        if($skip > 0){
            $cursor->skip($skip);
        }
        // 取多少行记录
        if($limit > 0){
            $cursor->limit($limit);
        }

        return $cursor;
    }

    public function count($query=array()){
        $res = $this->collection->count($query);
        return $res;
    }

    public function insert($query=array(),$option=array()){
        $res = $this->collection->insert($query, $option);
        return $res;
    }

    public function close(){
        #if ($this->connection->connected)
        if ($this->connection)
            return $this->connection->close();
        else
            return true;
    }
}
