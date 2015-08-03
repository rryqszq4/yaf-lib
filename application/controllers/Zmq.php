<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-7-27
 * Time: 下午1:48
 */

class ZmqController extends Sontroller
{

    public static $msgs = 1;

    public function libevent_serverAction(){

        function print_line($fd, $events, $arg){
            echo "callback fired".PHP_EOL;
            var_dump($arg[0]->recv());
            $arg[0]->send("got msg ".ZmqController::$msgs);
            if (ZmqController::$msgs++ >= 10){
                event_base_loopexit($arg[1]);
            }
        }

        $base = event_base_new();
        $event = event_new();

        $context = new ZMQContext();

        $rep = $context->getSocket(ZMQ::SOCKET_REP);

        $rep->bind("tcp://127.0.0.1:5555");

        $fd = $rep->getSockOpt(ZMQ::SOCKOPT_FD);

        event_set($event, $fd, EV_READ|EV_PERSIST, "print_line", array($rep, $base));

        event_base_set($event, $base);

        event_add($event);

        event_base_loop($base);
    }

    public function libevent_clientAction(){
        $queue = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REQ, "mysocket1");
        $queue->connect("tcp://127.0.0.1:5555");

        var_dump($queue->send("hello there")->recv());
    }

    public function requestAction(){

        $context = new ZMQContext();

        echo "Connecting to hello world server...\n";

        $requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);

        $requester->connect("tcp://127.0.0.1:5555");

        for ($request_nbr=0; $request_nbr != 10; $request_nbr++)
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

            $update = sprintf("{%05d %d %d", $zipcode, $temperature, $relhumidity);

            $publisher->send($update);

            sleep(1);
            echo $update.PHP_EOL;
        }
    }

    public function subscriberAction(){
        $context = new ZMQContext();

        echo "Collecting updates from weather server...",PHP_EOL;
        $subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
        $subscriber->connect("tcp://127.0.0.1:5556");

        $filter = "{";
        $subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE,$filter);

        $total_temp = 0;
        for($update_nbr = 0; $update_nbr < 100; $update_nbr++)
        {
            $string = $subscriber->recv();
            sscanf($string, "{%d %d %d", $zipcode, $temperature, $relhumidity);
            $total_temp += $temperature;
            echo $string.PHP_EOL;
            sleep(2);
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

    /*
     * pub-rep and sub-req
     */

    public function pub_repAction(){
        $config = array(
            'key_1'=>'value_1',
            'key_2'=>'value_2',
            'key_3'=>'value_3'
        );
        $string = json_encode($config);

        $clients = array('s1','s2','s3');

        $context = new ZMQContext();

        $pub = new ZMQSocket($context, ZMQ::SOCKET_PUB);

        $pub->bind('tcp://127.0.0.1:5561');

        $server = new ZMQSocket($context, ZMQ::SOCKET_REP);

        $server->bind('tcp://127.0.0.1:5555');

        while (count($clients) != 0)
        {
            $client_name = $server->recv();
            echo "{$client_name} is connect!\n";
            $key = array_search($client_name, $clients);
            unset($clients[$key]);

            echo "{$client_name} has come in!\n";
            $server->send("version is 2.0\n");

        }

        $pub->send($string);
    }

    public function sub_reqAction(){
        $hostname = "s3";

        $context = new ZMQContext();

        $sub = new ZMQSocket($context, ZMQ::SOCKET_SUB);
        $sub->connect("tcp://127.0.0.1:5561");

        $sub->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");

        $client = $context->getSocket(ZMQ::SOCKET_REQ);
        $client->connect("tcp://127.0.0.1:5555");

        while (1)
        {
            $client->send($hostname);
            $version = $client->recv();
            echo $version;

            if (!empty($version)){
                $recive = $sub->recv();
                $vars = json_decode($recive);
                var_dump($vars);
            }
        }

    }

    public function multiple_pollerAction(){
        $context = new ZMQContext();

        $receiver = new ZMQSocket($context, ZMQ::SOCKET_PULL);
        $receiver->connect("tcp://127.0.0.1:5557");

        $subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
        $subscriber->connect("tcp://127.0.0.1:5556");
        $subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "10001");

        //DebugTools::print_r($context);
        $poll = new ZMQPoll();
        $poll->add($receiver, ZMQ::POLL_IN);
        $poll->add($subscriber,ZMQ::POLL_IN);

        $readable = $writeable = array();

        while (true)
        {
            $events = $poll->poll($readable, $writeable);
            if ($events > 0){
                foreach ($readable as $socket){
                    if ($socket === $receiver){
                        $message = $socket->recv();
                    }else if ($socket === $subscriber){
                        $message = $socket->recv();
                    }
                }
            }
        }


    }

    public function multiple_readerAction(){
        $context = new ZMQContext();

        $receiver = new ZMQSocket($context, ZMQ::SOCKET_PULL);
        $receiver->connect("tcp://127.0.0.1:5557");

        $subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
        $subscriber->connect("tcp://127.0.0.1:5556");
        $subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "10001");

        while(true){
            try {
                for ($rc = 0; !$rc;){
                    if ($rc = $receiver->recv(ZMQ::MODE_NOBLOCK)){
                        // process task
                    }
                }
            } catch( ZMQSocketException $e){
                //do nothing
            }

            try {
                for ($rc = 0; !$rc;){
                    if ($rc = $receiver->recv(ZMQ::MODE_NOBLOCK)){
                        // process task
                    }
                }
            } catch( ZMQSocketException $e){
                //do nothing
            }

            usleep(1);
        }
    }

}