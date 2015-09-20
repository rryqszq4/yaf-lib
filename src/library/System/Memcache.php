<?php
/**
 * memcache封装
 *
 * @author rryqszq4
 */
class System_Memcache
{

    private static $pool;

    private static $instance;

    private $mc;

    private static function init(){
        if (!self::$pool){
            self::$pool = new Memcache();
            $config = Yaf_Registry::get('config');
            self::$pool->addserver(
                $config->memcache->host,
                $config->memcache->port,
                true,
                $config->memcache->weight
            );
        }
    }

    public static function get($key)
    {
        self::init();

        $ret = self::$pool->get($key);

        return $ret;
    }

    public static function delete($key)
    {
        self:init();

        $ret = self::$pool->delete($key);

        return $ret;
    }


    public static function set($key, $value, $ttl)
    {
        self::init();

        $ret = self::$pool->set($key, $value, MEMCACHE_COMPRESSED, $ttl);

        return $ret;

    }

    public static function flush()
    {
        self::init();

        return self::$pool->flush();
    }

    public static function increment($key,$value){
        self::init();

        return self::$pool->increment($key,$value);
    }

    public static function decrement($key, $value){
        self::init();

        return self::$pool->decrement($key, $value);
    }

    public static function getExtendedStats(){
        self::init();

        return self::$pool->getExtendedStats();
    }

    private function __construct(){
        #$this->connect();
        $this->pconnect();
    }

    private function __clone(){

    }

    public function __destruct(){
        $this->close();
    }

    public static function getInstance(){
        if (!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect(){
        $config = Yaf_Registry::get('config');

        $this->mc = new Memcache();
        $this->mc->connect(
            $config->memcache->host,
            $config->memcache->port
        );
        return $this;
    }

    public function pconnect(){
        $config = Yaf_Registry::get('config');

        $this->mc = new Memcache();
        $this->mc->pconnect(
            $config->memcache->host,
            $config->memcache->port
        );
        return $this;
    }

    public function close(){
        return $this->mc->close();
    }

    public function getVersion(){
        return $this->mc->getVersion();
    }

    public function getStats(){
        return $this->mc->getStats();
    }

    public function getServerStatus($host, $port){
        return $this->mc->getServerStatus($host, $port);
    }


}