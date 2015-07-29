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

    public function ventilatorAction(){
        $context = new ZMQContext();

        $sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
        $sender->bind("tcp://127.0.0.1:5557");

        echo "Press Enter when the workers are ready: ";
        $fp = fopen('php://stdin','r');
        $line = fgets($fp, 512);
        fclose($fp);
        echo "Sending tasks to workers...".PHP_EOL;

        $sender->send(0);

        $total_msec = 0;
        for ($task_nbr = 0; $task_nbr < 100; $task_nbr++){
            $workload = mt_rand(1, 100);
            $total_msec += $workload;
            $sender->send($workload);
        }

        printf("Total expected cost: %d msec\n",$total_msec);
        sleep(1);

    }

    public function taskworkAction(){
        $context = new ZMQContext();

        $receiver = new ZMQSocket($context, ZMQ::SOCKET_PULL);
        $receiver->connect("tcp://127.0.0.1:5557");

        $sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
        $sender->connect("tcp://127.0.0.1:5558");

        while (true){
            $string = $receiver->recv();

            echo $string . PHP_EOL;

            usleep($string * 1000);

            $sender->send("");
        }
    }

    public function sinkAction(){
        $context = new ZMQContext();
        $receiver = new ZMQSocket($context, ZMQ::SOCKET_PULL);
        $receiver->bind("tcp://127.0.0.1:5558");

        $string = $receiver->recv();

        $tstart = microtime(true);

        $total_msec = 0;
        for ($task_nbr = 0; $task_nbr < 100; $task_nbr++){
            $string = $receiver->recv();
            if ($task_nbr % 10 == 0){
                echo ":";
            }else {
                echo ".";
            }
        }

        $tend = microtime(true);

        $total_msec = ($tend - $tstart) * 1000;
        echo PHP_EOL;
        printf("Total elapsed time: %d msec", $total_msec);
        echo PHP_EOL;
    }

    public function multiple_pollerAction(){
        $context = new ZMQSocket();

        $receiver = new ZMQSocket($context, ZMQ::SOCKET_PULL);
        $receiver->connect("tcp://127.0.0.1:5557");



    }

}