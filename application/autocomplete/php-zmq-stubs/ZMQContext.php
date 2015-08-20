<?php
/**
 * Class ZMQContext
 * @link http://php.zero.mq/class.zmqcontext.html
 */
class ZMQContext
{
    /**
     * Constructs a new ZMQ context. The context is used to initialize sockets. A persistent context is required to initialize persistent sockets.
     * @link http://php.zero.mq/zmqcontext.construct.html
     * @param int $io_threads Number of io-threads in the context.
     * @param bool $is_persistent Whether the context is persistent. Persistent context is stored over multiple requests and is a requirement for persistent sockets.
     * @throws ZMQContextException
     */
    public function __construct ($io_threads = 1, $is_persistent = true) {}

    /**
     * Returns the value of a context option.
     * @link http://php.zero.mq/zmqcontext.getopt.html
     * @param string $key An integer representing the option. See the ZMQ::CTXOPT_* constants.
     * @return string|integer
     * @throws ZMQContextException
     */
    public function getOpt($key) {}

    /**
     * Shortcut for creating new sockets from the context. If the context is not persistent the persistent_id parameter is ignored and the socket falls back to being non-persistent. The on_new_socket is called only when a new underlying socket structure is created.
     * @link http://php.zero.mq/zmqcontext.getsocket.html
     * @param integer $type ZMQ::SOCKET_* constant to specify socket type.
     * @param string $persistent_id If persistent_id is specified the socket will be persisted over multiple requests.
     * @param \callback $on_new_socket Callback function, which is executed when a new socket structure is created. This function does not get invoked if the underlying persistent connection is re-used. The callback takes ZMQSocket and persistent_id as two arguments.
     * @return ZMQSocket
     * @throws ZMQSocketException
     */
    public function getSocket ($type, $persistent_id = null, $on_new_socket = null) {}

    /**
     * Whether the context is persistent. Persistent context is needed for persistent connections as each socket is allocated from a context.
     * @link http://php.zero.mq/zmqcontext.ispersistent.html
     * @return boolean
     */
    public function isPersistent() {}

    /**
     * Sets a ZMQ context option. The type of the value depends on the key.
     * See ZMQ Constant Types for more information.
     * @link http://php.zero.mq/zmqcontext.setopt.html
     * @param integer $key One of the ZMQ::CTXOPT_* constants.
     * @param mixed $value The value of the parameter.
     * @return ZMQContext
     * @throws ZMQContextException
     */
    public function setOpt($key, $value) {}
}