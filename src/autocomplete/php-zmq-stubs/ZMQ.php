<?php
/**
 * Class ZMQ
 * @link http://php.zero.mq/class.zmq.html
 */
class ZMQ
{
    const SOCKET_PAIR = 0;
    const SOCKET_PUB = 1;
    const SOCKET_SUB = 2;
    const SOCKET_XSUB = 10;
    const SOCKET_XPUB = 9;
    const SOCKET_REQ = 3;
    const SOCKET_REP = 4;
    const SOCKET_XREQ = 5;
    const SOCKET_XREP = 6;
    const SOCKET_PUSH = 8;
    const SOCKET_PULL = 7;
    const SOCKET_DEALER = 5;
    const SOCKET_ROUTER = 6;
    const SOCKET_UPSTREAM = 7;
    const SOCKET_DOWNSTREAM = 8;
    const POLL_IN = 1;
    const POLL_OUT = 2;
    const MODE_SNDMORE = 2;
    const MODE_NOBLOCK = 1;
    const MODE_DONTWAIT = 1;
    const DEVICE_FORWARDER = 2;
    const DEVICE_QUEUE = 3;
    const DEVICE_STREAMER = 1;
    const ERR_INTERNAL = -99;
    const ERR_EAGAIN = 11;
    const ERR_ENOTSUP = 156384713;
    const ERR_EFSM = 156384763;
    const ERR_ETERM = 156384765;
    const LIBZMQ_VER = null;
    const SOCKOPT_HWM = 201;
    const SOCKOPT_SNDHWM = 23;
    const SOCKOPT_RCVHWM = 24;
    const SOCKOPT_AFFINITY = 4;
    const SOCKOPT_IDENTITY = 5;
    const SOCKOPT_RATE = 8;
    const SOCKOPT_RECOVERY_IVL = 9;
    const SOCKOPT_SNDBUF = 11;
    const SOCKOPT_RCVBUF = 12;
    const SOCKOPT_LINGER = 17;
    const SOCKOPT_RECONNECT_IVL = 18;
    const SOCKOPT_RECONNECT_IVL_MAX = 21;
    const SOCKOPT_BACKLOG = 19;
    const SOCKOPT_MAXMSGSIZE = 22;
    const SOCKOPT_SUBSCRIBE = 6;
    const SOCKOPT_UNSUBSCRIBE = 7;
    const SOCKOPT_TYPE = 16;
    const SOCKOPT_RCVMORE = 13;
    const SOCKOPT_FD = 14;
    const SOCKOPT_EVENTS = 15;
    const SOCKOPT_SNDTIMEO = 28;
    const SOCKOPT_RCVTIMEO = 27;

    /**
     * @link http://php.zero.mq/zmq.construct.html
     */
    private function __construct() {}
}