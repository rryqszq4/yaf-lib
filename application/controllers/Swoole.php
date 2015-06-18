<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-5-29
 * Time: 下午5:10
 */

class SwooleController extends Sontroller {


    public $_layout = null;

    public static $socket;
    public static $workers=array();
    public static $worker_num = 2;
    public static $worker_last = 0;
    public static $a = 0;
    public static $stop = 0;
    public static $client;

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

        /*$socket = stream_socket_server("tcp://127.0.0.1:9021", $errno, $errstr);


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
        }*/

        SwooleController::$socket = stream_socket_server('tcp://127.0.0.1:9021',$errno=0,$errmsg='',STREAM_SERVER_BIND | STREAM_SERVER_LISTEN);
        stream_set_blocking(SwooleController::$socket,0);

        #$socket = System_Socket::create(AF_INET,SOCK_STREAM,0);
        #SwooleController::$socket = $socket;

        /*$socket->bind('127.0.0.1',9021);
        $socket->listen(1024);
        #print_r($socket->listenfd());
        SwooleController::$socket = $socket;
        SwooleController::$socket->setBlocking(false);
        */

        echo "Master: parent, PID=".posix_getpid()."\n";



        $workers = array();
        $worker_num = 1;
        $worker_last = 0;

        function callback_function(swoole_process $worker){
            echo "Worker: start. PID={$worker->pid}.\n";
            echo "Worker: pipe={$worker->pipe}\n";


            $GLOBALS['worker'] = $worker;

            swoole_event_add($worker->pipe,function($pipe){
                $recv = $GLOBALS['worker']->read();
                echo $recv."-----$pipe\n";

                SwooleController::$client = @stream_socket_accept(SwooleController::$socket,0);
                $recv = $GLOBALS['worker']->write('0');


                $message= fread(SwooleController::$client, 1024);
                echo 'I have received that : '.$message;
                fputs (SwooleController::$client, "{$pipe} OK\n");


                fclose(SwooleController::$client);



                #$recv = $GLOBALS['worker']->write('0');

            });

        }


        for ($i = 0; $i < SwooleController::$worker_num; $i++){
            $process = new swoole_process('callback_function',false,true);
            $pid = $process->start();
            SwooleController::$workers[$i] = $process;
            echo "Master: new worker, PID={$pid}\n";
        }

        swoole_event_add(SwooleController::$socket,function ($fd){
            print_r("stop--->>>>".SwooleController::$stop."\n");

            if (!SwooleController::$stop){

                print_r('___'.SwooleController::$socket."\n");

                $w_i = (SwooleController::$worker_last+1)%SwooleController::$worker_num;
                SwooleController::$worker_last = $w_i;

                SwooleController::$workers[$w_i]->write('c');
                SwooleController::$stop = 1;
            }else {
                print_r("pipe_read_start\n");
                $read = SwooleController::$workers[SwooleController::$worker_last]->read();
                print_r("pipe_read--->>>>".$read."\n");
                SwooleController::$stop = $read;
            }

            #swoole_event_del($fd);
        });

        swoole_event_wait();

        swoole_process::wait();

    }

    public function eventAction(){

        $socket = System_Socket::create(AF_INET,SOCK_STREAM,0);

        $socket->bind('127.0.0.1',9021);
        $socket->listen(1024);
        #print_r($socket->listenfd());
        SwooleController::$socket = $socket;
        SwooleController::$socket->setBlocking(false);

        echo "Master: parent, PID=".posix_getpid()."\n";

        for ($x = 1; $x < 5; $x++) {
            switch ($pid = pcntl_fork()) {
                case -1:
                    // @fail
                    die('Fork failed');
                    break;

                case 0:
                    // @child: Include() misbehaving code here
                    print "FORK: Child #{$x} preparing to nuke...\n";
                    //generate_fatal_error(); // Undefined function
                    break;

                default:
                    // @parent
                    print "FORK: Parent, letting the child run amok...\n";
                    pcntl_waitpid($pid, $status);
                    break;
            }
        }

    }

    public function testAction(){
        $socket = stream_socket_server('tcp://127.0.0.1:9021', $errno, $errstr);
        if (!$socket)
        {
            echo "$errstr ($errno)<br />\n";
        }
        else
        {
            // while there is connection, i'll receive it... if I didn't receive a message within $nbSecondsIdle seconds, the following function will stop.
            while ($conn = @stream_socket_accept($socket,5))
            {
                $message= fread($conn, 1024);
                echo 'I have received that : '.$message;
                fputs ($conn, "OK\n");
                print_r($conn);
                fclose ($conn);
            }
            #fclose($socket);
        }
    }
}