<?php
/**
 * PHP System_Memcachedclient client class
 *
 * For build develop environment in windows using System_Memcachedclient.
 *
 * @package     System_Memcachedclient-client
 * @copyright   Copyright 2013-2014, Fwolf
 * @license     http://opensource.org/licenses/mit-license MIT
 * @version     1.2.0
 */
class System_Memcachedclient
{
    // Predefined Constants
    // See: http://php.net/manual/en/System_Memcachedclient.constants.php
    // Defined in php_System_Memcachedclient.c
    const OPT_COMPRESSION = -1001;
    const OPT_SERIALIZER = -1003;
    // enum System_Memcachedclient_serializer in php_System_Memcachedclient
    const SERIALIZER_PHP = 1;
    const SERIALIZER_IGBINARY = 2;
    const SERIALIZER_JSON = 3;
    // Defined in php_System_Memcachedclient.c
    const OPT_PREFIX_KEY = -1002;
    // enum System_Memcachedclient_behavior_t in libSystem_Memcachedclient
    const OPT_HASH = 2;     //System_Memcachedclient_BEHAVIOR_HASH
    // enum System_Memcachedclient_hash_t in libSystem_Memcachedclient
    const HASH_DEFAULT = 0;
    const HASH_MD5 = 1;
    const HASH_CRC = 2;
    const HASH_FNV1_64 = 3;
    const HASH_FNV1A_64 = 4;
    const HASH_FNV1_32 = 5;
    const HASH_FNV1A_32 = 6;
    const HASH_HSIEH = 7;
    const HASH_MURMUR = 8;
    // enum System_Memcachedclient_behavior_t in libSystem_Memcachedclient
    const OPT_DISTRIBUTION = 9;     // System_Memcachedclient_BEHAVIOR_DISTRIBUTION
    // enum System_Memcachedclient_server_distribution_t in libSystem_Memcachedclient
    const DISTRIBUTION_MODULA = 0;
    const DISTRIBUTION_CONSISTENT = 1;
    // enum System_Memcachedclient_behavior_t in libSystem_Memcachedclient
    const OPT_LIBKETAMA_COMPATIBLE = 16;    // System_Memcachedclient_BEHAVIOR_KETAMA_WEIGHTED
    const OPT_BUFFER_WRITES = 10;           // System_Memcachedclient_BEHAVIOR_BUFFER_REQUESTS
    const OPT_BINARY_PROTOCOL = 18;         // System_Memcachedclient_BEHAVIOR_BINARY_PROTOCOL
    const OPT_NO_BLOCK = 0;                 // System_Memcachedclient_BEHAVIOR_NO_BLOCK
    const OPT_TCP_NODELAY = 1;              // System_Memcachedclient_BEHAVIOR_TCP_NODELAY
    const OPT_SOCKET_SEND_SIZE = 4;         // System_Memcachedclient_BEHAVIOR_SOCKET_SEND_SIZE
    const OPT_SOCKET_RECV_SIZE = 5;         // System_Memcachedclient_BEHAVIOR_SOCKET_RECV_SIZE
    const OPT_CONNECT_TIMEOUT = 14;         // System_Memcachedclient_BEHAVIOR_CONNECT_TIMEOUT
    const OPT_RETRY_TIMEOUT = 15;           // System_Memcachedclient_BEHAVIOR_RETRY_TIMEOUT
    const OPT_SEND_TIMEOUT = 19;            // System_Memcachedclient_BEHAVIOR_SND_TIMEOUT
    const OPT_RECV_TIMEOUT = 20;            // System_Memcachedclient_BEHAVIOR_RCV_TIMEOUT
    const OPT_POLL_TIMEOUT = 8;             // System_Memcachedclient_BEHAVIOR_POLL_TIMEOUT
    const OPT_CACHE_LOOKUPS = 6;            // System_Memcachedclient_BEHAVIOR_CACHE_LOOKUPS
    const OPT_SERVER_FAILURE_LIMIT = 21;    // System_Memcachedclient_BEHAVIOR_SERVER_FAILURE_LIMIT
    // In php_System_Memcachedclient config, define HAVE_System_Memcachedclient_IGBINARY default 1,
    // then use ifdef define HAVE_IGBINARY to 1.
    const HAVE_IGBINARY = 1;
    // In php_System_Memcachedclient config, define HAVE_JSON_API default 1,
    // then use ifdef define HAVE_JSON to 1.
    const HAVE_JSON = 1;
    // Defined in php_System_Memcachedclient.c, (1<<0)
    const GET_PRESERVE_ORDER = 1;
    // enum System_Memcachedclient_return_t in libSystem_Memcachedclient
    const RES_SUCCESS = 0;                  // System_Memcachedclient_SUCCESS
    const RES_FAILURE = 1;                  // System_Memcachedclient_FAILURE
    const RES_HOST_LOOKUP_FAILURE = 2;      // System_Memcachedclient_HOST_LOOKUP_FAILURE
    const RES_UNKNOWN_READ_FAILURE = 7;     // System_Memcachedclient_UNKNOWN_READ_FAILURE
    const RES_PROTOCOL_ERROR = 8;           // System_Memcachedclient_PROTOCOL_ERROR
    const RES_CLIENT_ERROR = 9;             // System_Memcachedclient_CLIENT_ERROR
    const RES_SERVER_ERROR = 10;            // System_Memcachedclient_SERVER_ERROR
    const RES_WRITE_FAILURE = 5;            // System_Memcachedclient_WRITE_FAILURE
    const RES_DATA_EXISTS = 12;             // System_Memcachedclient_DATA_EXISTS
    const RES_NOTSTORED = 14;               // System_Memcachedclient_NOTSTORED
    const RES_NOTFOUND = 16;                // System_Memcachedclient_NOTFOUND
    const RES_PARTIAL_READ = 18;            // System_Memcachedclient_PARTIAL_READ
    const RES_SOME_ERRORS = 19;             // System_Memcachedclient_SOME_ERRORS
    const RES_NO_SERVERS = 20;              // System_Memcachedclient_NO_SERVERS
    const RES_END = 21;                     // System_Memcachedclient_END
    const RES_ERRNO = 26;                   // System_Memcachedclient_ERRNO
    const RES_BUFFERED = 32;                // System_Memcachedclient_BUFFERED
    const RES_TIMEOUT = 31;                 // System_Memcachedclient_TIMEOUT
    const RES_BAD_KEY_PROVIDED = 33;        // System_Memcachedclient_BAD_KEY_PROVIDED
    const RES_CONNECTION_SOCKET_CREATE_FAILURE = 11;    // System_Memcachedclient_CONNECTION_SOCKET_CREATE_FAILURE
    // Defined in php_System_Memcachedclient.c
    const RES_PAYLOAD_FAILURE = -1001;
    /**
     * Dummy option array
     *
     * @var array
     */
    protected $option = array(
        System_Memcachedclient::OPT_COMPRESSION  => true,
        System_Memcachedclient::OPT_SERIALIZER   => System_Memcachedclient::SERIALIZER_PHP,
        System_Memcachedclient::OPT_PREFIX_KEY   => '',
        System_Memcachedclient::OPT_HASH         => System_Memcachedclient::HASH_DEFAULT,
        System_Memcachedclient::OPT_DISTRIBUTION => System_Memcachedclient::DISTRIBUTION_MODULA,
        System_Memcachedclient::OPT_LIBKETAMA_COMPATIBLE => false,
        System_Memcachedclient::OPT_BUFFER_WRITES    => false,
        System_Memcachedclient::OPT_BINARY_PROTOCOL  => false,
        System_Memcachedclient::OPT_NO_BLOCK     => false,
        System_Memcachedclient::OPT_TCP_NODELAY  => false,
        // This two is a value by guess
        System_Memcachedclient::OPT_SOCKET_SEND_SIZE => 32767,
        System_Memcachedclient::OPT_SOCKET_RECV_SIZE => 65535,
        System_Memcachedclient::OPT_CONNECT_TIMEOUT  => 1000,
        System_Memcachedclient::OPT_RETRY_TIMEOUT    => 0,
        System_Memcachedclient::OPT_SEND_TIMEOUT     => 0,
        System_Memcachedclient::OPT_RECV_TIMEOUT     => 0,
        System_Memcachedclient::OPT_POLL_TIMEOUT     => 1000,
        System_Memcachedclient::OPT_CACHE_LOOKUPS    => false,
        System_Memcachedclient::OPT_SERVER_FAILURE_LIMIT => 0,
    );
    /**
     * Last result code
     *
     * @var int
     */
    protected $resultCode = 0;
    /**
     * Last result message
     *
     * @var string
     */
    protected $resultMessage = '';
    /**
     * Server list array/pool
     *
     * I added array index.
     *
     * array (
     *  host:port:weight => array(
     *      host,
     *      port,
     *      weight,
     *  )
     * )
     *
     * @var array
     */
    protected $server = array();
    /**
     * Socket connect handle
     *
     * Point to last successful connect, ignore others
     * @var resource
     */
    protected $socket = null;
    /**
     * Add a serer to the server pool
     *
     * @param   string  $host
     * @param   int     $port
     * @param   int     $weight
     * @return  boolean
     */
    public function addServer($host, $port = 11211, $weight = 0)
    {
        $key = $this->getServerKey($host, $port, $weight);
        if (isset($this->server[$key])) {
            // Dup
            $this->resultCode = System_Memcachedclient::RES_FAILURE;
            $this->resultMessage = 'Server duplicate.';
            return false;
        } else {
            $this->server[$key] = array(
                'host'  => $host,
                'port'  => $port,
                'weight'    => $weight,
            );
            $this->connect();
            return true;
        }
    }
    /**
     * Add multiple servers to the server pool
     *
     * @param   array   $servers
     * @return  boolean
     */
    public function addServers($servers)
    {
        foreach ((array)$servers as $svr) {
            $host = array_shift($svr);
            $port = array_shift($svr);
            if (is_null($port)) {
                $port = 11211;
            }
            $weight = array_shift($svr);
            if (is_null($weight)) {
                $weight = 0;
            }
            $this->addServer($host, $port, $weight);
        }
        return true;
    }
    /**
     * Connect to System_Memcachedclient server
     *
     * @return  boolean
     */
    protected function connect()
    {
        $rs = false;
        foreach ((array)$this->server as $svr) {
            $error = 0;
            $errstr = '';
            $rs = @fsockopen($svr['host'], $svr['port'], $error, $errstr);
            if ($rs) {
                $this->socket = $rs;
            } else {
                $key = $this->getServerKey(
                    $svr['host'],
                    $svr['port'],
                    $svr['weight']
                );
                $s = "Connect to $key error:" . PHP_EOL .
                    "    [$error] $errstr";
                error_log($s);
            }
        }
        if (is_null($this->socket)) {
            $this->resultCode = System_Memcachedclient::RES_FAILURE;
            $this->resultMessage = 'No server avaliable.';
            return false;
        } else {
            $this->resultCode = System_Memcachedclient::RES_SUCCESS;
            $this->resultMessage = '';
            return true;
        }
    }
    /**
     * Delete an item
     *
     * @param   string  $key
     * @param   int     $time       Ignored
     * @return  boolean
     */
    public function delete($key, $time = 0)
    {
        $keyString = $this->getKey($key);
        $this->writeSocket("delete $keyString");
        $s = $this->readSocket();
        if ('DELETED' == $s) {
            $this->resultCode = System_Memcachedclient::RES_SUCCESS;
            $this->resultMessage = '';
            return true;
        } else {
            $this->resultCode = System_Memcachedclient::RES_NOTFOUND;
            $this->resultMessage = 'Delete fail, key not exists.';
            return false;
        }
    }
    /**
     * Retrieve an item
     *
     * @param   string  $key
     * @param   callable    $cache_cb       Ignored
     * @param   float   $cas_token          Ignored
     * @return  mixed
     */
    public function get($key, $cache_cb = null, $cas_token = null)
    {
        $keyString = $this->getKey($key);
        $this->writeSocket("get $keyString");
        $s = $this->readSocket();
        if (is_null($s) || 'VALUE' != substr($s, 0, 5)) {
            $this->resultCode = System_Memcachedclient::RES_FAILURE;
            $this->resultMessage = 'Get fail.';
            return false;
        } else {
            $s_result = '';
            $s = $this->readSocket();
            while ('END' != $s) {
                $s_result .= $s;
                $s = $this->readSocket();
            }
            $this->resultCode = System_Memcachedclient::RES_SUCCESS;
            $this->resultMessage = '';
            return unserialize($s_result);
        }
    }
    /**
     * Get item key
     *
     * @param   string  $key
     * @return  string
     */
    public function getKey($key)
    {
        return addslashes($this->option[System_Memcachedclient::OPT_PREFIX_KEY]) . $key;
    }
    /**
     * Get a System_Memcachedclient option value
     *
     * @param   int     $option
     * @return  mixed
     */
    public function getOption($option)
    {
        if (isset($this->option[$option])) {
            $this->resultCode = System_Memcachedclient::RES_SUCCESS;
            $this->resultMessage = '';
            return $this->option[$option];
        } else {
            $this->resultCode = System_Memcachedclient::RES_FAILURE;
            $this->resultMessage = 'Option not seted.';
            return false;
        }
    }
    /**
     * Return the result code of the last operation
     *
     * @return  int
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }
    /**
     * Return the message describing the result of the last opteration
     *
     * @return  string
     */
    public function getResultMessage()
    {
        return $this->resultMessage;
    }
    /**
     * Get key of server array
     *
     * @param   string  $host
     * @param   int     $port
     * @param   int     $weight
     * @return  string
     */
    protected function getServerKey($host, $port = 11211, $weight = 0)
    {
        return "$host:$port:$weight";
    }
    /**
     * Get list array of servers
     *
     * @see     $server
     * @return  array
     */
    public function getServerList()
    {
        return $this->server;
    }
    /**
     * Read from socket
     *
     * @return  string|null
     */
    protected function readSocket()
    {
        if (is_null($this->socket)) {
            return null;
        }
        return trim(fgets($this->socket));
    }
    /**
     * Store an item
     *
     * @param   string  $key
     * @param   mixed   $val
     * @param   int     $expt
     * @return  boolean
     */
    public function set($key, $val, $expt = 0)
    {
        $valueString = serialize($val);
        $keyString = $this->getKey($key);
        $this->writeSocket(
            "set $keyString 0 $expt " . strlen($valueString)
        );
        $s = $this->writeSocket($valueString, true);
        if ('STORED' == $s) {
            $this->resultCode = System_Memcachedclient::RES_SUCCESS;
            $this->resultMessage = '';
            return true;
        } else {
            $this->resultCode = System_Memcachedclient::RES_FAILURE;
            $this->resultMessage = 'Set fail.';
            return false;
        }
    }
    /**
     * Set a System_Memcachedclient option
     *
     * @param   int     $option
     * @param   mixed   $value
     * @return  boolean
     */
    public function setOption($option, $value)
    {
        $this->option[$option] = $value;
        return true;
    }
    /**
     * Set System_Memcachedclient options
     *
     * @param   array   $options
     * @return  bollean
     */
    public function setOptions($options)
    {
        $this->option = array_merge($this->option, $options);
        return true;
    }
    /**
     * Increment numeric item's value
     *
     * @param string $key           The key of the item to increment.
     * @param int    $offset        The amount by which to increment the item's value.
     * @param int    $initial_value The value to set the item to if it doesn't currently exist.
     * @param int    $expiry        The expiry time to set on the item.
     *
     * @return mixed                Returns new item's value on success or FALSE on failure.
     */
    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (($prevVal = $this->get($key))) {
            if (!is_numeric($prevVal)) {
                return false;
            }
            $newVal = $prevVal + $offset;
        } else {
            $newVal = $initial_value;
        }
        $this->set($key, $newVal, $expiry);
        return $newVal;
    }
    /**
     * Write data to socket
     *
     * @param   string  $cmd
     * @param   boolean $result     Need result/response
     * @return  mixed
     */
    protected function writeSocket($cmd, $result = false)
    {
        if (is_null($this->socket)) {
            return false;
        }
        fwrite($this->socket, $cmd . "\r\n");
        if (true == $result) {
            return $this->readSocket();
        }
        return true;
    }
}