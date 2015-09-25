<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-9-24
 * Time: 下午5:11
 */

class MadserverController extends Sontroller {

    const SUBTREE = "/madserver/";

    public function mainAction()
    {
        echo "Madserver\n";
        $context = new ZMQContext();
        $sub_socket = new ZMQSocket($context, ZMQ::SOCKET_SUB);
        $sub_socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
        $sub_socket->connect("tcp://127.0.0.1:5557");

        $snapshot = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
        $snapshot->connect("tcp://127.0.0.1:5556");

        $sequence = 1;
        $snapshot->sendmulti(array("ICANHAZ?",self::SUBTREE), ZMQ::MODE_SNDMORE);
        //$snapshot->send("ICANHAZ?");
        while (1){
            //$a = $snapshot->recv();
            //var_dump($a);
            $kvmsg = new Zmq_Kvmsg($sequence);
            $kvmsg->recv($snapshot);
            var_dump($kvmsg->body());
            $kvmsg->dump();

        }


        return false;
    }
}