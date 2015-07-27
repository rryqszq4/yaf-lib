<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-7-27
 * Time: 下午1:48
 */

class ZmqController extends Sontroller
{

    public function requestAction(){

        $context = new ZMQContext();

        echo "Connecting to hello world server...\n";

        $requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);

        $requester->connect("tcp://127.0.0.1:5555");

        for ($request_nbr=0; $request_nbr != 10; $requester++)
        {
            printf("Sending request %d...\n", $request_nbr);

            $requester->send("Hello");

            $reply = $requester->recv();

            printf("Received reply %d: [%s]\n", $request_nbr, $reply);
        }
    }

    public function replyAction(){

        $context = new ZMQContext(1);

        $responder = new ZMQSocket($context, ZMQ::SOCKET_REP);

        $responder->bind("tcp://127.0.0.1:5555");

        while(true){
            $request = $responder->recv();

            printf("Received request: [%s]\n",$request);

            sleep(1);

            $responder->send("World");
        }
    }
}