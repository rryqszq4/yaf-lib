<?php
/**
 * Class ZMQPoll
 * @link http://php.zero.mq/class.zmqpoll.html
 */
class ZMQPoll
{
    /**
     * Adds a new item to the poll set and returns the internal id of the added item. The item can be removed from the poll set using the returned string id.
     * @link http://php.zero.mq/zmqpoll.add.html
     * @param mixed $entry ZMQSocket object or a PHP stream resource
     * @param int $type Defines what activity the socket is polled for. See ZMQ::POLL_IN and ZMQ::POLL_OUT constants.
     * @return string
     * @throws ZMQPollException
     */
    public function add ($entry, $type) {}

    /**
     * Clears all elements from the poll set.
     * @link http://php.zero.mq/zmqpoll.clear.html
     * @return ZMQPoll
     */
    public function clear () {}

    /**
     * Count the items in the poll set.
     * @link http://php.zero.mq/zmqpoll.count.html
     * @return integer
     */
    public function count() {}

    /**
     * Returns an array containing ids for the items that had errors in the last poll. Empty array is returned if there were no errors.
     * @link http://php.zero.mq/zmqpoll.getlasterrors.html
     * @return array
     */
    public function getLastErrors() {}

    /**
     * Polls the items in the current poll set. The readable and writable items are returned in the readable and writable parameters. ZMQPoll::getLastErrors() can be used to check if there were errors.
     * @link http://php.zero.mq/zmqpoll.poll.html
     * @param array $readable Array where readable ZMQSockets/PHP streams are returned. The array will be cleared at the beginning of the operation.
     * @param array $writable Array where writable ZMQSockets/PHP streams are returned. The array will be cleared at the beginning of the operation.
     * @param int $timeout Timeout for the operation. -1 means that poll waits until at least one item has activity. Please note that starting from version 1.0.0 the poll timeout is defined in milliseconds, rather than microseconds.
     * @return integer
     * @throws ZMQPollException
     */
    public function poll(array &$readable , array &$writable, $timeout = -1) {}

    /**
     * Remove item from the poll set. The item parameter can be ZMQSocket object, a stream resource or the id returned from ZMQPoll::add() method.
     * @link http://php.zero.mq/zmqpoll.remove.html
     * @param mixed $item The ZMQSocket object, PHP stream or string id of the item.
     * @return boolean
     */
    public function remove($item ) {}
}