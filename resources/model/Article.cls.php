<?php

	
class Article
{
	public $config;
	public $db;
    public $id;
    public $name;
    public $acronym;
    public $creationDate;
	public $activated;

	
    public function __construct($data, $config)
    {
        $this->id = $data['id'];
        $this->name = $data[S_NAME];
        $this->acronym = $data['acronym'];
        $this->creationDate = $data['creationDate'];
        $this->activated = $data['activated'];
        		
		$this->config = $config;
	
    }
	
	/*
	*	$date must be in the format Ymd
	*/
	public function getDataChart($date){
		 try {
             $db = dbfactory::factory ($this->config['db']['calabrioMonit']['type'], $this->config['db']['calabrioMonit']['host'], $this->config['db']['calabrioMonit']['username'], $this->config['db']['calabrioMonit']['password'], $this->config['db']['calabrioMonit']['dbname']);
         }   catch (Exception $e) {
             die($e->getmessage());
         }

		$requete = $db->query(''); 
		while ($res = $db->fetch_assoc ($requete)) {

		}
	}
	
	
	public function getID(){
		return $this->id;
	}
	    
    public function getName(){
        return $this->name;
    }
        
    public function getAcronym(){
        return $this->acronym;
    }
    	

}

?>