<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-9-22
 * Time: 下午3:53
 */

class MadclientController extends Controller{

    public $_layout = null;

    public function indexAction(){

        $context = new ZMQContext();
        $socket = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
        //$socket->setSockOpt(ZMQ::SOCKOPT_IDENTITY,"identity");
        $socket->getSockOpt(ZMQ::SOCKOPT_IDENTITY);

        $socket->connect("tcp://127.0.0.1:5555");

        $zmsg = new Zmq_Msg($socket);
        $zmsg->body_set("Hello world");
        $zmsg->push("echo");
        $zmsg->push("MDPC01");
        $zmsg->push("");

        printf ("I: send request to 'echo' service: %s", PHP_EOL);
        echo $zmsg->__toString();

        $zmsg->send();

        return false;
    }

    public function kvmsgAction(){
        $kvmsg = new Zmq_Kvmsg(1);
        $a = $kvmsg->set_sequence(45678);
        var_dump($a);
        echo "<br>";
        var_dump($kvmsg->sequence());



        Zmq_Kvmsg::test();

        return false;
    }

    public function msgAction(){

        Zmq_Msg::test();
        return false;

    }

}