<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 15:08
 */

namespace domdomviv;
include_once(realpath(dirname(__FILE__) . "/DHT22.php"));
require_once(realpath(dirname(__FILE__) . "/Relay.php"));


abstract class Sensor
{

    private $name;
    private $type;
    private $id;
    protected $config;
    private $device;
    private $pinNumber;
    private $relayType;

    public function __construct($data, $config, $device)
    {
        $this->id = $data['ID'];
        $this->name = $data['name'];
        $this->type = $data['sensorType'];
        $this->device = $device;
        $this->pinNumber = $data['pinNumber'];
        $this->config = $config;

        switch ($this->type){
            case 'DHT22':
                 $this->description = 'Humidity and temperature sensor';
                 break;
            case 'DS18B20':
                $this->description = 'Waterproof temperature sensor';
                break;
            case 'Relay':
                if(isset($data['relayType'])) {
                    switch ($data['relayType']) {
                        case 'light':
                            $this->description = 'Lights controlled by the raspberry';
                            break;
                        case 'night':
                            $this->description = 'Night lights controlled by the raspberry';
                            break;
                        case 'fan':
                            $this->description = 'Fans controlled by the raspberry';
                            break;
                        case 'brumisator':
                            $this->description = 'Brumisator controlled by the raspberry';
                            break;
                    }
                    $this->relayType = $data['relayType'];
                }else{
                    $this->description = 'Switch controlled by the raspberry';
                }
                break;
        }

    }

    public function getRelayType()
    {
        if (isset($this->relayType)) return $this->relayType;
        else return -1;
    }

    public static function factory ($data, $config, $device) {
         if (class_exists ('\\domdomviv\\'.$data['sensorType'])) {

//        if (class_exists ('\\\\domdomviv\\'.$data['sensorType'])) {
            $className = '\\domdomviv\\'.$data['sensorType'];
            return new $className ($data, $config, $device);
        } else {
            throw new \Exception ('Pas d\'implementation disponible pour ' . $data['sensorType']);
        }
    }

    public function getID() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getDescription(){
        return $this->description;
    }
    public function setDescription($description){
        $this->description = $description;
    }
    public function getDevice(){
        return $this->device;
    }
    public function getPinNumber(){
        return $this->pinNumber;
    }
    protected function getConfig(){
        return $this->config;
    }
    abstract public function read();
    abstract public function toString();
    abstract public function isRelay();
    abstract public function isTemperatureSensor();
    abstract public function isHumiditySensor();

}