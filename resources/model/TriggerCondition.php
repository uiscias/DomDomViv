<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 22:39
 */

namespace domdomviv;


class TriggerCondition
{
    private $value;
    private $id;
    private $sensorID;
    private $trigger;
    private $comparator;
    private $orderInList;
    private $isMandatory;


    public function __construct($data, $trigger)
    {
        $this->id = $data['ID'];
        $this->trigger = $trigger;
        $this->comparator = $data['comparator'];
        $this->sensorID = $data['sensorID'];
        $this->value = $data['value'];
        $this->dateTime = $data['dateTime'];
        $this->dateTime2 = $data['dateTime2'];
        $this->orderInList = $data['orderInList'];
        $this->isMandatory = $data['isMandatory'];
    }

    public function evaluate(){
try{
        switch ($this->getComparator()) {
            case 't<':
                //we first test to be sure to be in presense of a temperature sensor
                if(! $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->isTemperatureSensor())
                    throw new \Exception('#'.$this->sensorID.' '.$this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getName().' is not a temperature sensor!');
                //perform the eval
                $temperature = $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getTemperature();
                return ($temperature < $this->getValue() );
                break;
            case 't>':
                if (DEBUG)
                    echo 't>';
                //we first test to be sure to be in presense of a temperature sensor
                if(! $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->isTemperatureSensor())
                    throw new \Exception('#'.$this->sensorID.' '.$this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getName().' is not a temperature sensor!');
                //perform the eval
                $temperature = $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getTemperature();
                return (round($temperature,2) > round($this->getValue(),2));
                break;
            case 'h<':
                //we first test to be sure to be in presense of a temperature sensor
                if(! $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->isHumiditySensor())
                    throw new \Exception('#'.$this->sensorID.' '.$this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getName().' is not a humidity sensor!');
                //perform the eval
                $humidity = $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getHumidity();
                return ($humidity < $this->getValue() );
                break;
            case 'h>':
                //we first test to be sure to be in presense of a temperature sensor
                if(! $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->isHumiditySensor())
                    throw new \Exception('#'.$this->sensorID.' '.$this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getName().' is not a humidity sensor!');
                //perform the eval
                $humidity = $this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getHumidity();
                return ($humidity > $this->getValue() );
                break;
            case 'd>':
                $now = new \DateTime('now');
                $value = $this->getDateTime();
                return  $now >= $value;
                break;
            case 'd<':
                $now = new \DateTime('now');
                $value = $this->getDateTime();
                return $now <= $value ;
                break;
            case 'in':
                $now = new \DateTime('now');
                $value = $this->getDateTime();
                $value2 = $this->getDateTime2();
                $hn = $now->format('H');
                $mn = $now->format('i');
                $hv = $value->format('H');
                $mv = $value->format('i');
                $hv2 = $value2->format('H');
                $mv2 = $value2->format('i');
                
		if ((($hn > $hv) || ($hn == $hv && $mn >= $mv)) && (($hn < $hv2) || ($hn == $hv2 && $mn <= $mv2)) ){
                    return true;
                }else{
                    return false;
                }
                break;
            case 'dh>':
                $now = new \DateTime('now');
                $value = $this->getDateTime();
                $hn = date('h', $now);
                $mn = date('i', $now);
                $hv = date('h', $value);
                $mv = date('i', $value);
                if (($hn > $hv) || ($hn == $hv && $mn > $mv)){
                    return true;
                }else{
                    return false;
                }
                break;
            case 'dh<':
                $now = new \DateTime('now');
                $value = $this->getDateTime();
                $hn = date('h', $now);
                $mn = date('i', $now);
                $hv = date('h', $value);
                $mv = date('i', $value);
                if (($hn < $hv) || ($hn == $hv && $mn < $mv)){
                    return true;
                }else{
                    return false;
                }
                break;
            default:
                return false;
        }
  	}catch (Exception $e){
		echo 'Error: '.$e.' ON '.$this->getTrigger()->getDevice()->getSensorByID($this->sensorID)->getName();
	} 
	 }

    public function getID() {
        return $this->id;
    }

    public function getTrigger() {
        return $this->trigger;
    }

    public function getComparator(){
        return $this->comparator;
    }
    public function getSensorID(){
        return $this->sensorID;
    }
    public function getValue(){
        return $this->value;
    }
    public function getDateTime(){
        return new \DateTime($this->dateTime);
    }
    public function getDateTime2(){
        return new \DateTime($this->dateTime2);
    }
    public function getOrder(){
        return $this->orderInList;
    }
    public function isMandatory(){
        return $this->isMandatory;
    }

}
