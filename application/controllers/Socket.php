<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-3-12
 * Time: 下午3:41
 */
class SocketController extends Controller {

    public function indexAction(){

        $socket = System_Socket::create(AF_INET, SOCK_STREAM, SOL_TCP);
        $socket->connect('127.0.0.1',8021);
        $in = array(
            'gamedb',
            'entity_ff14_ClassJob',
            array("key",1)
        );
        $in = msgpack_pack($in);
        $socket->write($in, strlen($in));
        $out = $socket->read(1024, PHP_NORMAL_READ);

        DebugTools::print_r($out);

        return false;
    }

    public function memcachedAction(){
        $m = new System_Memcachedclient();
        $m->addServer('localhost', 11211);

        $m->set('foo', 'bar');
        $foo = $m->get('foo');

        DebugTools::print_r($foo);

        return false;
    }
}