<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 22:39
 */

namespace domdomviv;


class TriggerAction
{
    private $message;
    private $id;
    private $relayID;
    private $trigger;
    private $actionType;
    private $orderInList;


    public function __construct($data, $trigger)
    {
        $this->id = $data['ID'];
        $this->trigger = $trigger;
        $this->actionType = $data['actionType'];
        $this->relayID= $data['relayID'];
        $this->message = $data['message'];
        $this->timeToLive = $data['timeToLive'];
        $this->orderInList = $data['orderInList'];
    }

    public function perform(){
        $now = new \DateTime('now');

        switch ($this->getActionType()) {
            case 'SendAlert':
                Datalogger::getInstance($this->getTrigger()->getDevice()->getID())->sendAlertMessage($this->getMessage(), 'USER');
                break;
            case 'RelayON':
echo 'set relay on '.$this->getRelayID().'@@' ;
                $relay = $this->getTrigger()->getDevice()->getSensorByID($this->getRelayID());
                $relay->setOn();
                if ($this->getTimeToLive() > 0){
                    sleep($this->getTimeToLive());
                    $relay->setOFF();
                    Datalogger::getInstance($this->getTrigger()->getDevice()->getID())->sendAlertMessage("Set Relay ".$relay->toString()." OFF due to TTL", 'INFO');
                }
                Datalogger::getInstance($this->getTrigger()->getDevice()->getID())->sendAlertMessage("Set Relay ".$relay->toString()." ON", 'INFO');
                break;
            case 'RelayOFF':
                $relay = $this->getTrigger()->getDevice()->getSensorByID($this->getrelayID());
                $relay->setOff();
                Datalogger::getInstance($this->getTrigger()->getDevice()->getID())->sendAlertMessage("Set Relay ".$relay->toString()." OFF", 'INFO');
                break;
            default:
                throw new \Exception('This kind of action is not yet handled.');
        }
    }

    public function getActionType()
    {
        return $this->actionType;
    }

    public function getOrderInList()
    {
        return $this->orderInList;
    }

    public function getRelayID()
    {
        return $this->relayID;
    }

    public function getTimeToLive()
    {
        return $this->timeToLive;
    }

    public function setActionType($actionType)
    {
        $this->actionType = $actionType;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setOrderInList($orderInList)
    {
        $this->orderInList = $orderInList;
    }

    public function setRelayID($relayID)
    {
        $this->relayID = $relayID;
    }

    public function setTimeToLive($timeToLive)
    {
        $this->timeToLive = $timeToLive;
    }

    public function setTrigger($trigger)
    {
        $this->trigger = $trigger;
    }

    public function getID() {
        return $this->id;
    }

    public function getTrigger() {
        return $this->trigger;
    }

    public function getMessage(){
        return $this->message;
    }
    public function getOrder(){
        return $this->orderInList;
    }

}
