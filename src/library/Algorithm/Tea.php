<?php

/**
 * Class Algorithm_Tea
 *
 * TEA coder encrypt 64 bits value, by 128 bits key,
    QQ do 16 round TEA.
    To see:
    http://www.ftp.cl.cam.ac.uk/ftp/papers/djw-rmn/djw-rmn-tea.html .
    TEA 加密,  64比特明码, 128比特密钥, qq的TEA算法使用16轮迭代
    具体参看
    http://www.ftp.cl.cam.ac.uk/ftp/papers/djw-rmn/djw-rmn-tea.html
 *
 *
 */
class Algorithm_Tea {

    //Private
    var $key;

    // CBC or ECB Mode
    // normaly, CBC Mode would be the right choice
    var $cbc = 1;

    public $op = 0xffffffff;

    //Verschluesseln
    function encrypt($text, $k) {
        $n = strlen($text);
        #if($n%8 != 0) $lng = ($n+(8-($n%8)));
        #else $lng = 0;

        $lng = (8-($n+2))%8 + 2 + 8;

        $fills = '';
        for ($i = 0; $i < $lng; $i++){
            $fills .= chr(rand(0,0xff));
        }
        #DebugTools::print_r($fills);
        #DebugTools::print_r(chr(($lng-2)|0xF8));
        $v = chr(($lng - 2)|0xF8) . $fills . $text;
        #$v = str_pad($v, $lng+7, );
        #$v = sprintf("%-304s",$v);
        $tmp_l = strlen($v)+7;
        $v = pack("a{$tmp_l}",$v);

        #DebugTools::print_r($v."___".strlen($v));

        #DebugTools::print_r($k);

        $tr = pack("a8", '');
        $to = pack("a8", '');
        $r = '';
        $o = pack("a8", '');
        for ($i=0; $i < strlen($v); $i = $i+8){
            $o = $this->_xor(substr($v,$i,8), $tr);
            $tr = $this->_xor($this->block_encrypt($o, $k), $to);
            $to = $o;
            $r .= $tr;
        }

        return $r;
    }

    function _xor($a, $b){
        $a = $this->_str2long($a);
        $a1 = $a[0];
        $a2 = $a[1];
        $b = $this->_str2long($b);
        $b1 = $b[0];
        $b2 = $b[1];

        return $this->_long2str(($a1 ^ $b1) & $this->op).
            $this->_long2str(($a2 ^ $b2) & $this->op);
    }

    /***********************************
    Some internal functions
     ***********************************/
    function block_encrypt($v, $z) {
        $s=0;
        $delta=0x9e3779b9;
        $n = 16;

        $k = $this->_str2long($z);
        $v = $this->_str2long($v);
        $z = $v[1];
        $y = $v[0];
        #DebugTools::print_r($k);
        #DebugTools::print_r($y."___".$z);
        /* start cycle */
        for ($i=0; $i<$n; $i++)
        {
            $s += $delta;
            $y += ($this->op &($z<<4)) + $k[0] ^ $z + $s ^ ($this->op&($z>>5))+$k[1];
            $y &= $this->op;
            $z += ($this->op &($y<<4)) + $k[2] ^ $y + $s ^ ($this->op&($y>>5))+$k[3];
            $z &= $this->op;

        }

        /* end cycle */
        return $this->_long2str($y).$this->_long2str($z);

    }

    function block_decrypt($y, $z) {
        $delta=0x9e3779b9;
        $sum=0xC6EF3720;
        $n=16;
    }

    //Einen Text in Longzahlen umwandeln
    //Covert a string into longinteger
    function _str2long($data) {
        $n = strlen($data);
        $tmp = unpack('N*', $data);
        $data_long = array();
        $j = 0;
        foreach ($tmp as $value) {
            $data_long[$j++] = $value;
            if ($j >= 4) break;
        }
        return $data_long;
    }

    //Longzahlen in Text umwandeln
    //Convert a longinteger into a string
    function _long2str($l){
        return pack('N', $l);
    }

    static public function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/=', '*-_');
    }


    static public function base64_url_decode($input) {
        return base64_decode(strtr($input, '*-_', '+/='));
    }

}

?>