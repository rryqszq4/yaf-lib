<?php
/**
 * memcache封装
 *
 * @author rryqszq4
 */
class System_Memcache
{

    private static $instance;

    private static function init(){
        if (!self::$instance){
            self::$instance = new Memcache();
            $config = Yaf_Registry::get('config');
            self::$instance->addserver(
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

        $ret = self::$instance->get($key);

        return $ret;
    }

    public static function delete($key)
    {
        self:init();

        $ret = self::$instance->delete($key);

        return $ret;
    }


    public static function set($key, $value, $ttl)
    {
        self::init();

        $ret = self::$instance->set($key, $value, MEMCACHE_COMPRESSED, $ttl);

        return $ret;

    }

    public static function flush()
    {
        self::init();

        return self::$instance->flush();
    }

    public static function increment($key,$value){
        self::init();

        return self::$instance->increment($key,$value);
    }

    public static function decrement($key, $value){
        self::init();

        return self::$instance->decrement($key, $value);
    }
}