<?php
/**
 * Class ZMQSocket
 * @link http://php.zero.mq/class.zmqsocket.html
 */
class ZMQSocket
{
    /**
     * Constructs a ZMQSocket object. persistent_id parameter can be used to allocated a persistent socket. A persistent socket has to be allocated from a persistent context and it stays connected over multiple requests. The persistent_id parameter can be used to recall the same socket over multiple requests. The on_new_socket is called only when a new underlying socket structure is created.
     * @param ZMQContext $context ZMQContext object.
     * @param int $type The socket type. See ZMQ::SOCKET_* constants.
     * @param string $persistent_id If persistent_id is specified the socket will be persisted over multiple requests. If context is not persistent the socket falls back to non-persistent mode.
     * @param \callable $on_new_socket Callback function, which is executed when a new socket structure is created. This function does not get invoked if the underlying persistent connection is re-used.
     * @throws ZMQSocketException
     */
    public function __construct(ZMQContext $context, $type, $persistent_id = null, $on_new_socket = null) {}

    /**
     * Bind the socket to an endpoint. The endpoint is defined in format transport://address where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     * @link http://php.zero.mq/zmqsocket.bind.html
     * @param string $dsn
     * @param bool $force
     * return ZMQSocket
     * @throws ZMQSocketException
     */
    public function bind($dsn, $force = false) {}

    /**
     * Connect the socket to a remote endpoint. The endpoint is defined in format transport://address where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     * @link http://php.zero.mq/zmqsocket.connect.html
     * @param string $dsn The connect dsn, for example transport://address.
     * @param bool $force Tries to connect even if the socket has already been connected to given endpoint.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function connect($dsn, $force = false) {}

    /**
     * Returns a list of endpoints where the socket is connected or bound to.
     * @link http://php.zero.mq/zmqsocket.getendpoints.html
     * @return array
     */
    public function getEndpoints() {}

    /**
     * Returns the persistent id of the socket.
     * @link http://php.zero.mq/zmqsocket.getpersistentid.html
     * @return string
     */
    public function getPersistentId() {}

    /**
     * Gets the socket type.
     * @link http://php.zero.mq/zmqsocket.getsockettype.html
     * @return integer
     */
    public function getSocketType() {}

    /**
     * Returns the value of a socket option.
     * @link http://php.zero.mq/zmqsocket.getsockopt.html
     * @param string $key An integer representing the option. See the ZMQ::SOCKOPT_* constants.
     * @return mixed
     * @throws ZMQSocketException
     */
    public function getSockOpt($key) {}

    /**
     * Check whether the socket is persistent.
     * @link http://php.zero.mq/zmqsocket.ispersistent.html
     * @return boolean
     */
    public function isPersistent() {}

    /**
     * Receive a message from a socket. By default receiving will block until a message is available unless ZMQ::MODE_NOBLOCK flag is used. ZMQ::SOCKOPT_RCVMORE socket option can be used for receiving multi-part messages. See ZMQSocket::setSockOpt() for more information.
     * @link http://php.zero.mq/zmqsocket.recv.html
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.
     * @return string
     * @throws ZMQSocketException
     */
    public function recv($mode = 0) {}

    /**
     * Receive an array multipart message from a socket. By default receiving will block until a message is available unless ZMQ::MODE_NOBLOCK flag is used.
     * @link http://php.zero.mq/zmqsocket.recvmulti.html
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.
     * @return string
     * @throws ZMQSocketException
     */
    public function recvMulti($mode = 0) {}

    /**
     * Send a message using the socket. The operation can block unless ZMQ::MODE_NOBLOCK is used.
     * @link http://php.zero.mq/zmqsocket.send.html
     * @param string $message The message to send.
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function send($message, $mode = 0) {}

    /**
     * Send a multipart message using the socket. The operation can block unless ZMQ::MODE_NOBLOCK is used.
     * @link http://php.zero.mq/zmqsocket.sendmulti.html
     * @param array $message The message to send - an array of strings
     * @param int $mode Pass mode flags to receive multipart messages or non-blocking operation. See ZMQ::MODE_* constants.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function sendmulti(array $message, $mode = 0) {}

    /**
     * Sets a ZMQ socket option. The type of the value depends on the key. See ZMQ Constant Types for more information.
     * @link http://php.zero.mq/zmqsocket.setsockopt.html
     * @param int $key One of the ZMQ::SOCKOPT_* constants.
     * @param mixed $value The value of the parameter.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function setSockOpt($key, $value) {}

    /**
     * Unbind the socket from an endpoint. The endpoint is defined in format transport://address where transport is one of the following: inproc, ipc, tcp, pgm or epgm.
     * @link http://php.zero.mq/zmqsocket.unbind.html
     * @param string $dsn The previously bound dsn, for example transport://address.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function unbind($dsn) {}
}