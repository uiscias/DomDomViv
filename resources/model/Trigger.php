<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 22:49
 */

namespace domdomviv;
require_once(realpath(dirname(__FILE__) . "/TriggerCondition.php"));
require_once(realpath(dirname(__FILE__) . "/TriggerAction.php"));


class Trigger
{
    private $name;
    private $id;
    private $description;
    private $device;
    private $isEnabled;
    private $conditions;
    private $actions;

    public function __construct($data, $device)
    {
        $this->id = $data['ID'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->device = $device;
        $this->conditions = $this->getTriggerConditionsList();
        $this->actions = $this->getTriggerActionsList();
    }

    public function checkJob(){
        $actionToBeDone = $this->evalConditions();
        if($actionToBeDone) {
            $result = $this->executeActions();
            if ($result <> 1) {
                return -1;
            } else {
                return 1;
            }
        }else
            return 0;
    }

    public function getTriggerConditionsList()
    {
        if (isset($this->conditions)) return $this->conditions;

        $requete = \dbfactory::singleton()->query ('SELECT * FROM triggercondition WHERE triggerID = \''.$this->id.'\'  order by orderInList asc', 'trigger condition list');

        while ($res = \dbfactory::singleton()->fetch_assoc ($requete)) {
            $this->conditions[] = new TriggerCondition($res, $this);
        }

        return $this->conditions;
    }

    public function getTriggerActionsList()
    {
        if (isset($this->actions)) return $this->actions;

        $requete = \dbfactory::singleton()->query ('SELECT * FROM triggeraction WHERE triggerID = \''.$this->id.'\' order by orderInList asc', 'trigger condition list');

        while ($res = \dbfactory::singleton()->fetch_assoc ($requete)) {
            $this->actions[] = new TriggerAction($res, $this);
        }

        return $this->actions;
    }

    public function evalConditions(){
        $result = 0;
        foreach ($this->getTriggerConditionsList() as $condition){
            $res = $condition->evaluate();
            if ($condition->isMandatory() && !$res){
		    return 0;
            }if ($res){
		    $result = $res;
       	    } 
	}
        return $result;
    }

    public function executeActions(){
        foreach ($this->getTriggerActionsList() as $action){
            $res = $action->perform();
        }
        return;
    }

    public function setDevice($device)
    {
        $this->device = $device;
    }

    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    public function getID() {
        return $this->id;
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
    public function isEnabled(){
        return $this->isEnabled;
    }
}
