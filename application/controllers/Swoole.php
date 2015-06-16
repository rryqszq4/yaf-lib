<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-5-29
 * Time: 下午5:10
 */

class SwooleController extends Sontroller {


    public $_layout = null;

    public function clientAction(){

        $client = new swoole_client(SWOOLE_SOCK_TCP);
        if (!$client->connect('127.0.0.1', 9021, -1))
        {
            exit("connect failed. Error: {$client->errCode}\n");
        }
        $client->send("hello world\n");
        echo $client->recv();
        $client->close();

        return false;
    }

    public function serverAction(){
        $server = new swoole_server("127.0.0.1", 9021, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

        $setting = array(
            'daemonize' => 0,
            'reactor_num' => 2,
            'worker_num' => 4,
            'max_request' => 2000,
            'backlog' => 128,
            'open_cpu_affinity' => 1,
            'open_tcp_nodelay' => 1,
            'log_file' => '../log/swoole_test.log',
            #'open_eof_check' => 1,
            #'package_eof' => "\r\n\r\n",
            'heartbeat_check_interval' => 30,
            'heartbeat_idle_time' => 60,
            'dispatch_mode' => 2
        );

        $server->set($setting);

        var_dump($server->setting);

        $server->on("connect", function($server, $fd){
            echo "connect\n";
        });

        $server->on("receive", function($server, $fd, $from_id, $data){
            $server->send($fd, 'Swoole: '.$data);
            $server->close($fd);
        });

        $server->on("close", function($server, $fd){
            echo "close\n";
            $server->shutdown();
        });

        $server->start();

        return false;
    }

    public function processAction(){

        $socket = stream_socket_server("tcp://127.0.0.1:9021", $errno, $errstr);


        function callback_function(swoole_process $worker){
            echo "process\n";
            print_r($worker);
            sleep(3);
            echo posix_getpid();
        }

        $process = new swoole_process('callback_function',false,true);
        $pid = $process->start();
        print_r($pid);
        #$wait = swoole_process::wait();
        #print_r($wait);

        echo posix_getpid();

        if (!$socket) {
            echo "$errstr ($errno)<br />\n";
        } else {
            while ($conn = stream_socket_accept($socket)) {
                fwrite($conn, 'The local time is ' . date('n/j/Y g:i a') . "\n");
                fclose($conn);
                $wait = swoole_process::wait();
            }
            fclose($socket);
        }
    }

    public function eventAction(){

    }
}