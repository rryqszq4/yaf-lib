<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-5-29
 * Time: 下午2:59
 */

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