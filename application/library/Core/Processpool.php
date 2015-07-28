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
    public  static $workers = array();
    public static $workers_pipe = array();
    private $process_number = 4;
    private $socket_fd;
    private $event_base;

    public static $restart_event = array();
    public  static $worker_event_base = array();

    private $stop = 0;
    private $last_worker = 0;
    private static $read_events = array();
    private static $write_events = array();

    public $read = null;
    public $write = null;

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
            $pid = $process->start();
            self::$workers[$i] = $process;
            self::$workers_pipe[$process->pipe] = $process;
            self::$restart_event[$i] = event_new();
            //var_dump($process->pipe);
            event_set(
                self::$restart_event[$i],
                $process->pipe,
                EV_READ|EV_PERSIST,
                array($this,"relisten")
            );
            event_base_set(self::$restart_event[$i], $this->event_base);
            event_add(self::$restart_event[$i]);

        }
    }

    public function worker_callback(swoole_process $worker){
        self::$worker_event_base[$worker->pipe] = event_base_new();
        $event = event_new();
        //$GLOBALS['worker'] = $worker;
        event_set($event,$worker->pipe,EV_READ|EV_PERSIST,array($this,'worker_accept'),$worker);
        event_base_set($event,self::$worker_event_base[$worker->pipe]);
        event_add($event);
        event_base_loop(self::$worker_event_base[$worker->pipe]);
    }

    public function relisten($pipe){
        self::$workers_pipe[$pipe]->read();
        $this->stop = 0;
    }

    public function dispatch(){
        if (!$this->stop)
        {
            $w_i = ($this->last_worker + 1) % $this->process_number;
            $this->last_worker = $w_i;
            self::$workers[$w_i]->write('c');
            $this->stop = 1;
        }
    }

    public function worker_accept($fd, $what, $arg){
        $arg->read();

        $client = @stream_socket_accept($this->socket_fd, 0);
        stream_set_blocking($client, 0);

        $arg->write('0');

        $read_event = event_new();
        $idx = intval($arg->pipe);
        self::$read_events[$idx] = $read_event;
        event_set(self::$read_events[$idx],$client,EV_READ|EV_PERSIST,array($this, "worker_read"),$idx);
        event_base_set(self::$read_events[$idx],self::$worker_event_base[$idx]);
        event_add(self::$read_events[$idx]);
    }

    public function worker_read($fd, $what ,$arg){
        call_user_func($this->read, $fd);
        $write_event = event_new();
        self::$write_events[$arg] = $write_event;
        event_set(self::$write_events[$arg],$fd,EV_WRITE|EV_PERSIST,array($this, "worker_write"),$arg);
        event_base_set(self::$write_events[$arg], self::$worker_event_base[$arg]);
        event_add(self::$write_events[$arg]);
        event_del(self::$read_events[$arg]);
    }

    public function worker_write($fd,$what,$arg){
        call_user_func($this->write, $fd);
        event_del(self::$write_events[$arg]);
        unset(self::$write_events[$arg]);
        unset(self::$read_events[$arg]);
        fclose($fd);
    }

    /*public function read($fd){
        $recv_buf = '';
        while(1){
            $buffer= fread($fd, 8192);
            if ($buffer===''||$buffer===false){

                break;
            }
            $recv_buf .= $buffer;
        }
    }
    public function write($fd){
        $len = fwrite($fd, "ok\n");
    }*/
}