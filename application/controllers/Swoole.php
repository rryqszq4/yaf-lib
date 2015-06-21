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
    public static $worker_num = 4;
    public static $worker_last = 0;
    public static $a = 0;
    public static $stop = 0;
    public static $client;
    public static $workers_pipe = array();
    public static $event;
    public static $read_event;
    public static $write_event;
    public static $event_base;
    public static $restart_event = array();
    public static $send_str = " ok\n";
    public static $socket_q = array();
    public static $size = 0;

    public function clientAction(){

        $client = new swoole_client(SWOOLE_SOCK_TCP);



        $client->connect('192.168.80.140', 9021, 0.5);

        $client->send('hello world!');
        echo $client->recv();
        $client->close();
        return false;
    }

    public function serverAction(){
        $server = new swoole_server("192.168.80.140", 9021, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

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
            #echo "connect\n";
        });

        $server->on("receive", function($server, $fd, $from_id, $data){
            $server->send($fd, 'Swoole: '.$data);
            $server->close($fd);
        });

        $server->on("close", function($server, $fd){
            #echo "close\n";
            #$server->shutdown();
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

        $context_option['socket']['backlog'] = 1024;
        $context = stream_context_create($context_option);
        SwooleController::$socket = stream_socket_server(
            'tcp://127.0.0.1:9021',
            $errno=0,
            $errmsg='',
            STREAM_SERVER_BIND | STREAM_SERVER_LISTEN,
            $context
        );

        // 尝试打开tcp的keepalive，关闭TCP Nagle算法
        if(function_exists('socket_import_stream'))
        {
            $s   = socket_import_stream($this->_mainSocket );
            @socket_set_option($s, SOL_SOCKET, SO_KEEPALIVE, 1);
            @socket_set_option($s, SOL_SOCKET, TCP_NODELAY, 1);
        }

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
                #echo $recv."-----$pipe\n";

                SwooleController::$client = @stream_socket_accept(SwooleController::$socket,0);
                stream_set_blocking(SwooleController::$client,0);
                $recv = $GLOBALS['worker']->write('0');

                #sleep(5);

                $message= fread(SwooleController::$client, 1024);
                echo 'I have received that : '.$message;

                fwrite(SwooleController::$client, "{$pipe} OK\n");


                fclose(SwooleController::$client);



                #$recv = $GLOBALS['worker']->write('0');

            });

            swoole_event_wait();

        }


        for ($i = 0; $i < SwooleController::$worker_num; $i++){
            $process = new swoole_process('callback_function',false,true);
            $pid = $process->start();
            SwooleController::$workers[$i] = $process;
            echo "Master: new worker, PID={$pid}\n";
            SwooleController::$workers_pipe[$process->pipe] = $process;
            swoole_event_add($process->pipe,function($pipe){
                $read = SwooleController::$workers_pipe[$pipe]->read();
                SwooleController::$stop = 0;
            });
        }

        swoole_event_add(SwooleController::$socket,function ($fd){
            #print_r("stop--->>>>".SwooleController::$stop."\n");

            if (!SwooleController::$stop){

                #print_r('___'.SwooleController::$socket."\n");

                $w_i = (SwooleController::$worker_last+1)%SwooleController::$worker_num;
                SwooleController::$worker_last = $w_i;

                /*swoole_event_add(SwooleController::$workers[$w_i]->pipe,null,function($pipe){
                    #SwooleController::$workers[$pipe]->write('c');
                });*/
                SwooleController::$workers[$w_i]->write('c');
                SwooleController::$stop = 1;
            }else {
                #print_r("pipe_read_start\n");
                #$read = SwooleController::$workers[SwooleController::$worker_last]->read();
                #print_r("pipe_read--->>>>".$read."\n");
                #SwooleController::$stop = $read;
                #echo 123;
            }

            #swoole_event_del($fd);
        });

        swoole_event_wait();

        swoole_process::wait();

    }

    public function eventAction(){
        $context_option['socket']['backlog'] = 1024;
        $context = stream_context_create($context_option);
        SwooleController::$socket = stream_socket_server(
            'tcp://192.168.80.140:9021',
            $errno=0,
            $errmsg='',
            STREAM_SERVER_BIND | STREAM_SERVER_LISTEN,
            $context
        );

        // 尝试打开tcp的keepalive，关闭TCP Nagle算法
        if(function_exists('socket_import_stream'))
        {
            $s   = socket_import_stream(SwooleController::$socket);
            @socket_set_option($s, SOL_SOCKET, SO_KEEPALIVE, 1);
            @socket_set_option($s, SOL_SOCKET, TCP_NODELAY, 1);
        }

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

            SwooleController::$event_base = event_base_new();
            SwooleController::$event = event_new();

            $GLOBALS['worker'] = $worker;

            function do_write($fd,$what,$arg){
                SwooleController::$size--;
                $len = fwrite($fd, SwooleController::$send_str);

                event_del(SwooleController::$write_event[$arg]);
                unset(SwooleController::$write_event[$arg]);
                unset(SwooleController::$read_event[$arg]);
                fclose($fd);




            }

            function do_read($fd,$what,$arg){

                $recv_buf = '';
                while(1){
                    $buffer= fread($fd, 8192);
                    #echo "123\n";
                    if ($buffer===''||$buffer===false){

                        break;
                    }
                    $recv_buf .= $buffer;
                }
                #echo 'I have received that : '.$recv_buf;

                #fwrite(SwooleController::$client, " OK\n");


                $write_event = event_new();
                SwooleController::$write_event[$arg] = $write_event;


                event_set(SwooleController::$write_event[$arg],$fd,EV_WRITE|EV_PERSIST,'do_write',$arg);
                event_base_set(SwooleController::$write_event[$arg],SwooleController::$event_base);
                event_add(SwooleController::$write_event[$arg]);

                #fclose(SwooleController::$client);
                event_del(SwooleController::$read_event[$arg]);

            }

            function do_accept($fd,$what,$arg){

                $res = $GLOBALS['worker']->read();
                #echo $res;
                $client = @stream_socket_accept(SwooleController::$socket,0);
                stream_set_blocking($client,0);
                $recv = $GLOBALS['worker']->write('0');


                $read_event = event_new();
                $idx = intval($read_event);
                #SwooleController::$client[$idx] = $client;
                SwooleController::$read_event[$idx] = $read_event;

                #print_r(SwooleController::$read_event);

                event_set(SwooleController::$read_event[$idx],$client,EV_READ|EV_PERSIST,'do_read',$idx);
                event_base_set(SwooleController::$read_event[$idx],SwooleController::$event_base);
                event_add(SwooleController::$read_event[$idx]);



                /*$message= fread(SwooleController::$client, 8192);
                echo 'I have received that : '.$message;

                fwrite(SwooleController::$client, " OK\n");


                fclose(SwooleController::$client);*/
                #event_del(SwooleController::$event);

            }


            event_set(SwooleController::$event,$worker->pipe,EV_READ|EV_PERSIST,'do_accept');
            event_base_set(SwooleController::$event,SwooleController::$event_base);
            event_add(SwooleController::$event);

            event_base_loop(SwooleController::$event_base);


        }

        $event_base = event_base_new();

        function do_listen_restart($pipe){
            $res = SwooleController::$workers_pipe[$pipe]->read();
            #echo $pipe."\n";
            SwooleController::$stop = 0;
        }

        for ($i = 0; $i < SwooleController::$worker_num; $i++){
            $process = new swoole_process('callback_function',false,true);
            $pid = $process->start();
            SwooleController::$workers[$i] = $process;
            echo "Master: new worker, PID={$pid}\n";
            SwooleController::$workers_pipe[$process->pipe] = $process;
            SwooleController::$restart_event[$i] = event_new();
            event_set(SwooleController::$restart_event[$i],$process->pipe,EV_READ|EV_PERSIST,'do_listen_restart');
            event_base_set(SwooleController::$restart_event[$i],$event_base);
            event_add(SwooleController::$restart_event[$i]);
            /*swoole_event_add($process->pipe,function($pipe){
                $read = SwooleController::$workers_pipe[$pipe]->read();
                SwooleController::$stop = 0;
            });*/
        }

        function do_dispatch(){
            if (!SwooleController::$stop){

                #print_r('___'.SwooleController::$socket."\n");

                $w_i = (SwooleController::$worker_last+1)%SwooleController::$worker_num;
                SwooleController::$worker_last = $w_i;


                SwooleController::$workers[$w_i]->write('c');
                SwooleController::$stop = 1;
            }
        }


        $event = event_new();
        event_set($event,SwooleController::$socket,EV_READ|EV_PERSIST,'do_dispatch');
        event_base_set($event,$event_base);
        event_add($event);

        /*swoole_event_add(SwooleController::$socket,function ($fd){
            #print_r("stop--->>>>".SwooleController::$stop."\n");

            if (!SwooleController::$stop){

                #print_r('___'.SwooleController::$socket."\n");

                $w_i = (SwooleController::$worker_last+1)%SwooleController::$worker_num;
                SwooleController::$worker_last = $w_i;


                SwooleController::$workers[$w_i]->write('c');
                SwooleController::$stop = 1;
            }else {
                #print_r("pipe_read_start\n");
                #$read = SwooleController::$workers[SwooleController::$worker_last]->read();
                #print_r("pipe_read--->>>>".$read."\n");
                #SwooleController::$stop = $read;
                #echo 123;
            }

            #swoole_event_del($fd);
        });*/

        event_base_loop($event_base);

        //swoole_event_wait();

        swoole_process::wait();


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
            while ($conn = @stream_socket_accept($socket,100))
            {
                print_r($conn."\n");
                $message= fread($conn, 1024);
                echo 'I have received that : '.$message;
                fputs ($conn, "OK\n");

                #fclose ($conn);
            }
            #fclose($socket);
        }
    }
}