<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-6-26
 * Time: 下午4:32
 *
 * 进程池
 */

class Core_Processpool {

    private static $instance = null;

    private static $workers = array();

    private static $workers_pipe = array();

    private $process_number = 4;

    private $socket_fd;

    private $event_base;

    private $worker_event_base;

    private $stop = 0;

    private $last_worker = 0;

    private static $read_events = array();

    private static $write_events = array();

    private function __construct($socket_fd, $process_number)
    {
        $this->init($socket_fd, $process_number);
    }

    private function __clone(){
    }

    private function init($socket_fd, $process_number)
    {
        $this->socket_fd = $socket_fd;
        $this->process_number = $process_number;
        $this->event_base = event_base_new();
    }

    public static function create($socket_fd, $process_number=4)
    {
        if (!(self::$instance instanceof self))
        {
            self::$instance = new self($socket_fd, $process_number);
        }
        return self::$instance;
    }

    public function run(){
        $this->worker_run();
        $this->master_run();
    }

    private function master_run(){
        $event = event_new();
        event_set($event,$this->socket_fd,EV_READ|EV_PERSIST,array($this,'dispatch'));
        event_base_set($event,$this->event_base);
        event_add($event);

        event_base_loop($this->event_base);

        swoole_process::wait();
    }

    private function worker_run(){
        for ($i = 0; $i < $this->process_number; $i++)
        {
            $process = new swoole_process(array($this,"worker_callback"), false, true);
            $process = $process->start();
            self::$workers_pipe[$process->pipe] = $process;
            $event = event_new();
            event_set(
                $event,
                $process->pipe,
                EV_READ|EV_PERSIST,
                array($this,"relisten")
            );
            event_base_set($event, $this->event_base);
            event_add($event);
        }
    }

    private function worker_callback(swoole_process $worker){
        $this->worker_event_base = event_base_new();
        $event = event_new();

        $GLOBALS['worker'] = $worker;

        event_set($event,$worker->pipe,EV_READ|EV_PERSIST,array($this,'worker_accept'));
        event_base_set($event,$this->worker_event_base);
        event_add($event);

        event_base_loop($this->worker_event_base);

    }

    private function relisten($pipe){
        self::$workers_pipe[$pipe]->read();

        $this->stop = 0;
    }

    private function dispatch(){
        if (!$this->stop)
        {
            $w_i = ($this->last_worker + 1) % $this->process_number;
            $this->last_worker = $w_i;

            self::$workers_pipe[$w_i]->write('c');
            $this->stop = 1;
        }
    }

    private function worker_accept($fd, $what, $arg){
        $GLOBALS['worker']->read();

        $client = @stream_socket_accept($this->socket_fd, 0);
        stream_set_blocking($client, 0);
        $GLOBALS['worker']->write('0');

        $read_event = event_new();
        $idx = intval($read_event);
        self::$read_events[$idx] = $read_event;

        event_set(self::$read_events[$idx],$client,EV_READ|EV_PERSIST,array($this, "worker_read"),$idx);
        event_base_set(self::$read_events[$idx],$this->worker_event_base);
        event_add(self::$read_events[$idx]);

    }

    private function worker_read($fd, $what ,$arg){
        call_user_func(array($this,"read"), $fd);

        $write_event = event_new();
        self::$write_event[$arg] = $write_event;


        event_set(self::$write_events[$arg],$fd,EV_WRITE|EV_PERSIST,array($this, "worker_write"),$arg);
        event_base_set(self::$write_events[$arg], $this->worker_event_base);
        event_add(self::$write_events[$arg]);

        event_del(self::$read_events[$arg]);
    }

    private function worker_write($fd,$what,$arg){
        call_user_func(array($this,"write"), $fd);

        event_del(self::$write_events[$arg]);
        unset(self::$write_events[$arg]);
        unset(self::$read_events[$arg]);
        fclose($fd);
    }

    public function read($fd){

    }

    public function write($fd){

    }
}