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

    const FRAME_KEY = 0;
    const FRAME_SEQ = 1;
    const FRAME_UUID = 2;
    const FRAME_PROPS = 3;
    const FRAME_BODY = 4;
    const FRAMES = 5;

    private $_msg = array();

    private $_present = array();

    private function _get_present($frame){
        return $this->_present[$frame];
    }

    private function _set_present($frame, $bool)
    {
        $this->_present[$frame] = $bool;
    }

    public function __construct()
    {
        $this->_set_present(self::FRAME_KEY, 0);
        $this->_set_present(self::FRAME_SEQ, 0);
        $this->_set_present(self::FRAME_UUID, 0);
        $this->_set_present(self::FRAME_PROPS, 0);
        $this->_set_present(self::FRAME_BODY, 0);
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

    public function sequence()
    {
        if (!$this->_get_present(self::FRAME_SEQ)){
            return 0;
        }else {
            $sequence = 0;
            $source = $this->_msg[self::FRAME_SEQ];
            $sequence = $source[0] << 56;
            $sequence += $source[1] << 48;
            $sequence += $source[2] << 40;
            $sequence += $source[3] << 32;
            $sequence += $source[4] << 24;
            $sequence += $source[5] << 16;
            $sequence += $source[6] << 8;
            $sequence += $source[7];
        }
        return $sequence;
    }

    public function set_sequence($sequence)
    {
        if ($this->_get_present(self::FRAME_SEQ))
            $this->_msg[self::FRAME_SEQ] = "";

        $source = array();
        $source[0] = (($sequence >> 56) & 255);
        $source[1]= (($sequence >> 48) & 255);
        $source[2]= (($sequence >> 40) & 255);
        $source[3]= (($sequence >> 32) & 255);
        $source[4]= (($sequence >> 24) & 255);
        $source[5]= (($sequence >> 16) & 255);
        $source[6]= (($sequence >> 8) & 255);
        $source[7]= (($sequence) & 255);

        $this->_msg[self::FRAME_SEQ] = $source;
        $this->_set_present(self::FRAME_SEQ, 1);

        return $this->_msg;
    }

}