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
        $sub_socket->setSockOpt(ZMQ::SOCKOPT_TCP_KEEPALIVE, 0);
        $sub_socket->setSockOpt(ZMQ::SOCKOPT_TCP_KEEPALIVE_IDLE, 10);
        $sub_socket->setSockOpt(ZMQ::SOCKOPT_TCP_KEEPALIVE_CNT, 2);
        $sub_socket->setSockOpt(ZMQ::SOCKOPT_TCP_KEEPALIVE_INTVL, 5);
        $sub_socket->connect("tcp://127.0.0.1:5557");

        $snapshot = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
        $snapshot->connect("tcp://127.0.0.1:5556");

        $sequence = 1;
        $snapshot->sendmulti(array("ICANHAZ?",self::SUBTREE), ZMQ::MODE_SNDMORE);
        while (1){
            $kvmsg = new Zmq_Kvmsg($sequence);
            $kvmsg->recv($snapshot);
            if ($kvmsg->key() == "KTHXBAI"){
                echo "I: received snapshot=".$kvmsg->sequence()."\n";
                break;
            }

        }
        while (1){
            $kvmsg = new Zmq_Kvmsg($sequence);
            $kvmsg->recv($sub_socket);
            echo "I: start receive sub=".$kvmsg->sequence()."\n";
            $kvmsg->dump();
        }


        return false;
    }
}