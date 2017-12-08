<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 12:28
 */

namespace domdomviv;
require_once(realpath(dirname(__FILE__) . "/Sensor.php"));
require_once(realpath(dirname(__FILE__) . "/Datalogger.php"));
require_once(realpath(dirname(__FILE__) . "/Trigger.php"));


class Device
{
    private $name;
    private $id;
    private $description;
    private $config;
    private $sensors;
    private $triggers;

    public function __construct($data, $config)
    {
        $this->id = $data['ID'];
        $this->name = $data['name'];
        $this->description = $data['description'];

        $this->config = $config;
    }
    
    public function getSensorsList(){
        if (isset($this->sensors)) return $this->sensors;

        $requete = \dbfactory::singleton()->query ('SELECT * FROM sensor WHERE deviceID = \''.$this->id.'\' ', 'Sensors list');
	//echo 'SELECT * FROM sensor WHERE deviceID = \''.$this->id.'\' ';

        while ($res = \dbfactory::singleton()->fetch_assoc ($requete)) {
            $this->sensors[] = Sensor::factory($res, $this->config, $this);
        }

        return $this->sensors;
    }

    public function getSensorByID($id){
        if (!isset($this->sensors))
            $this->getSensorsList();

	echo 'ID = ID = ID = '.$id."\n";

        foreach ($this->sensors as $sensor) {
            if ($sensor->getID() == $id){
         //      		echo $sensor->getName()." is the right one!!! \n"; 
			return $sensor;
		}else{
	//	echo $sensor->getName()."is not the right one \n";	
       } }
    }

    public function getTriggersList()
    {
        $nbr=0;
        if (isset($this->triggers)) return $this->triggers;

        $requete = \dbfactory::singleton()->query ('SELECT * FROM device_trigger WHERE deviceID = \''.$this->id.'\' and isEnabled = 1', 'trigger list');
        while ($res = \dbfactory::singleton()->fetch_assoc ($requete)) {
            $className = '\\domdomviv\\Trigger';
            $this->triggers[] = new $className ($res, $this);
            $nbr++;
        }
       // print_r($this->triggers);

        if($nbr >0)
            return $this->triggers;
        else return -1;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getID()
    {
        return $this->id;
    }

    public function checkTriggers(){
        echo $this->getName() . 'check tr';    
    $result = 0;
        if($this->getTriggersList() == -1) return -1;
        foreach ($this->getTriggersList() as $trigger){
            $result += $trigger->checkJob();
        }
        return $result;
    }

    public function logSensorsDatas()
    {
        foreach ($this->getSensorsList() as $sensor) {
            if($sensor->getType() == 'DHT22') {
                try {
                    Datalogger::getInstance($this->getID())->sendTemperatureAndHumidity($sensor->getID(), $sensor->getTemperature(), $sensor->getHumidity());
                } catch (Exception $e) {
                }
            }
        }
    }   


}
