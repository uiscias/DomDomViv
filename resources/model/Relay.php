<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 15:12
 */

namespace domdomviv;


class Relay extends Sensor
{
    private $isOn = true;

    public function read()
    {
        // TODO: Implement read() method.
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }

    public function isRelay()
    {
        return true;
    }

    public function isOn(){
        $return = '';
        exec ("gpio read ".$this->getPinNumber(), $status, $return );

        return !$status[0];
    }

    public function setOff(){
        echo 'set relay off';
//        exec('/usr/local/bin/gpio mode '.$this->getPinNumber().' out');
        exec('/usr/local/bin/gpio write '.$this->getPinNumber().' 1');
        exec('/usr/local/bin/gpio mode '.$this->getPinNumber().' in');
        exec('/usr/local/bin/gpio write '.$this->getPinNumber().' 1');
        return $this->isOn();
    }

    public function setOn(){
echo 'set on ';
        exec('/usr/local/bin/gpio mode '.$this->getPinNumber().' out');
        exec('/usr/local/bin/gpio write '.$this->getPinNumber().' 0');

        return $this->isOn();
    }

    public function isTemperatureSensor()
    {
        return false;
    }

    public function isHumiditySensor()
    {
        return false;
    }
}
