<?php
/**
 * Class ZMQDevice
 * @link http://php.zero.mq/class.zmqdevice.html
 */
class ZMQDevice
{
    /**
     * Call to this method will prepare the device. Usually devices are very long running processes so running this method from interactive script is not recommended.
     * @link http://php.zero.mq/zmqdevice.construct.html
     * @param ZMQSocket $frontend Frontend parameter for the devices. Usually where there messages are coming.
     * @param ZMQSocket $backend Backend parameter for the devices. Usually where there messages going to.
     * @throws ZMQDeviceException
     */
    public function __construct (ZMQSocket $frontend , ZMQSocket $backend) {}

    /**
     * Call to this method will block until the device is running. It is not recommended that devices are used from interactive scripts.
     * @link http://php.zero.mq/zmqdevice.run.html
     * @return void
     * @throws ZMQDeviceException
     */
    public function run() {}

    /**
     * Sets the idle callback function. If idle timeout is defined the idle callback function shall be called if the internal poll loop times out without events. If the callback function returns false or a value that evaluates to false the device is stopped.
     * @link http://php.zero.mq/zmqdevice.setidlecallback.html
     * @param \callable $cb_func Callback function
     * @return ZMQDevice
     */
    public function setIdleCallback($cb_func) {}

    /**
     * Sets the internal poll timeout value.
     * @link http://php.zero.mq/zmqdevice.setidletimeout.html
     * @param int $timeout The poll timeout value.
     * @return ZMQDevice
     */
    public function setIdleTimeout($timeout) {}
}