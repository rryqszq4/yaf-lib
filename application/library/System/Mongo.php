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

    }

    /**
     * Description:私有化克隆函数，防止外界克隆对象
     */
    private function __clone()
    {
    }

    /**
     * Description:静态方法，单例访问统一入口
     * @return Singleton：返回应用中的唯一对象实例
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Description:获取数据库的私有方法的连接句柄
     */
    public function getDbConnect()
    {
        return $this->db;
    }

    public function conn(){
		$this->connection = new MongoClient('mongodb://root:root@127.0.0.1:27017/gamedb');
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
}
