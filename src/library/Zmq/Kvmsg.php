<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-9-22
 * Time: 下午7:54
 */

class Zmq_Kvmsg
{

    const KEY_MAX = 255;

    const FRAME_KEY = 1;
    const FRAME_SEQ = 0;
    const FRAME_UUID = 2;
    const FRAME_PROPS = 3;
    const FRAME_BODY = 4;
    const FRAMES = 5;

    private $_identity = "";

    private $_msg = array();

    private $_present = array();

    private function _get_present($frame){
        return $this->_present[$frame];
    }

    private function _set_present($frame, $bool)
    {
        $this->_present[$frame] = $bool;
    }

    public function __construct($sequence)
    {
        $this->_set_present(self::FRAME_KEY, 0);
        $this->_set_present(self::FRAME_SEQ, 0);
        $this->_set_present(self::FRAME_UUID, 0);
        $this->_set_present(self::FRAME_PROPS, 0);
        $this->_set_present(self::FRAME_BODY, 0);

        $this->set_sequence($sequence);

        //return $this;
    }

    public function identity()
    {
        return $this->_identity;
    }

    public function key()
    {
        if (!$this->_get_present(self::FRAME_KEY))
            return null;
        else
            return $this->_msg[self::FRAME_KEY];
    }

    public function set_key($key)
    {
        $this->_msg[self::FRAME_KEY] = $key;
        $this->_set_present(self::FRAME_KEY, 1);
        return $this;
    }

    public function fmt_key()
    {
        $args = func_get_args();
        $this->set_key(vsprintf(array_shift($args), $args));
        return $this;
    }

    public function body()
    {
        if (!$this->_get_present(self::FRAME_BODY))
            return null;
        else
            return $this->_msg[self::FRAME_BODY];
    }

    public function set_body($body)
    {
        $this->_msg[self::FRAME_BODY] = $body;
        $this->_set_present(self::FRAME_BODY, 1);
        return $this;
    }

    public function fmt_body()
    {
        $args = func_get_args();
        $this->set_body(vsprintf(array_shift($args), $args));
        return $this;
    }

    public function uuid()
    {

    }

    public function set_uuid()
    {
        $this->_msg[self::FRAME_UUID] = null;
        $this->_set_present(self::FRAME_UUID, 1);
        return $this;
    }

    public function set_props()
    {
        $this->_msg[self::FRAME_PROPS] = null;
        $this->_set_present(self::FRAME_PROPS, 1);
        return $this;
    }

    public function sequence()
    {
        if (!$this->_get_present(self::FRAME_SEQ)){
            return 0;
        }else {
            $sequence = 0;
            $source = $this->_msg[self::FRAME_SEQ];
            $sequence = ord($source[0]) << 56;
            $sequence += ord($source[1]) << 48;
            $sequence += ord($source[2]) << 40;
            $sequence += ord($source[3]) << 32;
            $sequence += ord($source[4]) << 24;
            $sequence += ord($source[5]) << 16;
            $sequence += ord($source[6]) << 8;
            $sequence += ord($source[7]);
        }
        return $sequence;
    }

    public function set_byte_sequence($byte)
    {
        $this->_msg[self::FRAME_SEQ] = $byte;
        $this->_set_present(self::FRAME_SEQ, 1);

        return $this->_msg;
    }

    public function set_sequence($sequence)
    {
        if ($this->_get_present(self::FRAME_SEQ))
            $this->_msg[self::FRAME_SEQ] = "";

        $source = '00000000';
        $source[0] = chr(($sequence >> 56) & 255);
        $source[1]= chr(($sequence >> 48) & 255);
        $source[2]= chr(($sequence >> 40) & 255);
        $source[3]= chr(($sequence >> 32) & 255);
        $source[4]= chr(($sequence >> 24) & 255);
        $source[5]= chr(($sequence >> 16) & 255);
        $source[6]= chr(($sequence >> 8) & 255);
        $source[7]= chr(($sequence) & 255);

        $this->_msg[self::FRAME_SEQ] = $source;
        $this->_set_present(self::FRAME_SEQ, 1);

        return $this->_msg;
    }

    public function size()
    {
        if ($this->_get_present(self::FRAME_BODY))
            return strlen($this->body());
        else
            return 0;
    }

    public function store(&$kvmap)
    {
        if($this->size() > 0)
            $kvmap[$this->key()] = $this->_msg;
    }

    public function send($socket)
    {
        $res = $socket->sendmulti($this->_msg, ZMQ::MODE_SNDMORE);
        return $res;
    }

    public function route_recv($socket)
    {

        $res = $socket->recvmulti();
        $this->_identity = $res[0];
        $this->set_key($res[self::FRAME_KEY+1]);
        $this->set_byte_sequence($res[self::FRAME_SEQ+1]);
        $this->set_body($res[self::FRAME_BODY+1]);

        return $this;
    }

    public function recv($socket)
    {
        $res = $socket->recvmulti(ZMQ::MODE_SNDMORE);
        $this->set_key($res[self::FRAME_KEY]);
        $this->set_byte_sequence($res[self::FRAME_SEQ]);
        $this->set_body($res[self::FRAME_BODY]);
        return $this;
    }

    public function dump()
    {
        $res = array();
        $res['key'] = $this->key();
        $res['seq'] = $this->sequence();
        $res['uuid'] = null;
        $res['props'] = null;
        $res['body'] = $this->body();
        $res['size'] = $this->size();

        var_dump($res);
    }

    public static function test()
    {
        $context = new ZMQContext();
        $output = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
        //$output->setSockOpt(ZMQ::SOCKOPT_IDENTITY,"identity");
        $output->bind("inproc://kvmsg_selftest");
        $input = new ZMQSocket($context, ZMQ::SOCKET_ROUTER);
        $input->connect("inproc://kvmsg_selftest");

        $kvmsg = new self(123);
        $kvmsg->set_key('key');
        //$kvmsg->set_sequence(1);
        $kvmsg->set_uuid();
        $kvmsg->set_props();
        $kvmsg->set_body('body');
        $kvmsg->dump();

        $kvmsg->send($output);

        $kvmsg_2 = new self(2);
        $kvmsg_2->route_recv($input);
        $kvmsg_2->dump();


    }

}