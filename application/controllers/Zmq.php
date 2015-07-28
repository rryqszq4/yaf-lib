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

    public function publisherAction(){
        $context = new ZMQContext();

        $publisher = $context->getSocket(ZMQ::SOCKET_PUB);

        $publisher->bind("tcp://127.0.0.1:5556");

        while(true){
            $zipcode = mt_rand(0, 100000);
            $temperature = mt_rand(-80, 135);
            $relhumidity = mt_rand(10, 60);

            $update = sprintf("%05d %d %d", $zipcode, $temperature, $relhumidity);

            $publisher->send($update);
        }
    }

    public function subscriberAction(){
        $context = new ZMQContext();

        echo "Collecting updates from weather server...",PHP_EOL;
        $subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
        $subscriber->connect("tcp://127.0.0.1:5556");

        $filter = "1001";
        $subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, $filter);

        $total_temp = 0;
        for($update_nbr = 0; $update_nbr < 100; $update_nbr++)
        {
            $string = $subscriber->recv();
            sscanf($string, "%d %d %d", $zipcode, $temperature, $relhumidity);
            $total_temp += $temperature;
        }
        printf("Average temperature for zipcode '%s' was %dF\n", $filter, (int)($total_temp/$update_nbr));
    }
}