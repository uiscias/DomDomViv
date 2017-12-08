<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 15:12
 */

namespace domdomviv;


class DHT22 extends Sensor
{

    public function read()
    {
//        return 'Humidity = 52.90 % Temperature = 20.90 *C';
        $output = array();
        $return_var = 0;
        $i=1;
        $res = exec('sudo /usr/local/bin/loldht '.$this->getPinNumber(). ' 5', $output, $return_var);
        if (isset($output) && $output <> '' && $res <> '') {
            while (isset($output[$i+1]) && substr($output[$i], 0, 1) != "H" && $i < 5) {
                $i++;
            }
            if (substr($output[$i], 0, 1) != "H")
                throw new \Exception('No data');
            return $output[$i];
        }else{
            throw new \Exception('Error : No data.');
        }
//
    }

    public function logData(){
        $requete = \dbfactory::singleton()->query ('INSERT INTO datalogger (\'dateTime\', \'sensorID\', \'temperature\', \'humidity\') VALUES (\''.now().'\', \''.$this->getID().'\', \''.$this->getTemperature().'\', \''.$this->getHumidity().'\')', 'LogData');
        return ;
    }

    public function getTemperature(){
        return substr($this->read(),33,5);
    }

    public function getHumidity(){
        return substr($this->read(),11,5);
    }

    public function toString()
    {
        return '#'.$this->getID().': '.$this->getName().' on '.$this->getPinNumber().' :'.$this->read();
    }

    public function isRelay()
    {
        return false;
    }

    public function isTemperatureSensor()
    {
        return true;
    }

    public function isHumiditySensor()
    {
        return true;
    }
}
