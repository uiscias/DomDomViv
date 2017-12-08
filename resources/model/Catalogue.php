<?php
namespace domdomviv;

require_once(realpath(dirname(__FILE__) . "/Device.php"));

class Catalogue {

    private $config;
    private $devices;

    public function __construct($data)
    {
        $this->config = $data;

    }
    
    
    /**
     * Get a list of devices basic data
     * @return Array[Site]
     */    
    public function getDevicesList()
    {
     if(isset($this->devices)){
            return $this->devices;
		}

        $requete = \dbfactory::singleton()->query ('SELECT * FROM device', 'Devices list');

        while ($res = \dbfactory::singleton()->fetch_assoc ($requete)) {
            $this->devices[] = new Device($res, $this->config);
			
	}
//	print_r($this->devices[1]);
        return $this->devices;
    }  
      
    /**
     * Get a specific device by ID
     * @return Device
     */
    public function getDevice($id)
    {
        foreach ($this->getDevicesList() as $device){
            if($device->getID() == $id)
                return $device;
        }
    }

    /**
     * Get a specific device by ID
     * @return Device
     */
    public function getDeviceByName($name)
    {
        foreach ($this->getDevicesList() as $device){
            if($device->getName() == $name)
                return $device;
        }
    }

    public function logDatas(){
        foreach ($this->getDevicesList() as $device){
            $device->logSensorsDatas();
        }
    }

    public function checkTriggers(){
$i = 0;
	foreach ($this->getDevicesList() as $device){
  $device->checkTriggers();
	}
}
    public function generateGraphs(){
        foreach ($this->getDevicesList() as $device){
            foreach ($device->getSensorsList() as $sens){
                if ($sens->isTemperatureSensor() || $sens->isHumiditySensor())
                    Datalogger::getInstance(1)->generateHistoricalGraph($sens, Settings::singleton()->getConfig('graphPeriod'));
            }
        }
    }
   /*     try {
            $db = dbfactory::factory ($this->config['db']['calabrioMonit']['type'], $this->config['db']['calabrioMonit']['host'], $this->config['db']['calabrioMonit']['username'], $this->config['db']['calabrioMonit']['password'], $this->config['db']['calabrioMonit']['dbname']);
        }   catch (Exception $e) {
            die($e->getmessage());
        }
            
        $requete = $db->query ('SELECT * FROM tbl_site WHERE acronym = \''.$acronym.'\'', 'Site Detail');
        

        while ($res = $db->fetch_assoc ($requete)) {
            $site = new Site($res, $this->config);
        }
        if (!isset($site)) return -1;
       // sqlReleaseStmt($requete);
	   
	   */

/*
      public function getMetadataPercentage($dateStart, $dateEnd){
			try {
				$db = dbfactory::factory ($this->config['db']['calabrioSQMDB']['type'], $this->config['db']['calabrioSQMDB']['host'], $this->config['db']['calabrioSQMDB']['username'], $this->config['db']['calabrioSQMDB']['password'], $this->config['db']['calabrioSQMDB']['dbname']);
			}   catch (Exception $e) {
				die($e->getmessage());
			}

		
				$query = 'SELECT CAST (CASE WHEN Count(data) > 1 THEN 1 ELSE 0 END AS bit) as hasMetadata, ccr.id FROM [SQMDB].[dbo].[MetaData] as M RIGHT JOIN [SQMDB].[dbo].[Ccr] as ccr ON M.ccrFK = ccr.id   WHERE ccr.dnis LIKE \'8730%\' and ccr.startTime > \''.$dateStart. '\' and ccr.startTime < \''.$dateEnd.'\'  GROUP BY ccr.id'; 
				
				//echo $query;

  
			$requete = $db->query ($query);
			$sumMetadata  =0;
			$total =0;
			while ($res = $db->fetch_assoc ($requete)) {
				$sumMetadata += $res['hasMetadata'];
				$total++;
			}
			
			if ($total > 0) 
				$perc = round(($sumMetadata * 100 / $total), 2);
			else
				$perc = 100;
			return $perc;
	  }
	  
	  
	  	  
	  public function generateGraphSummaryOfTheMonth(){
		$lastmonth = mktime(0, 0, 0, date("m")-1, 1,   date("Y"));
		$lastmonthEnd = mktime(0, 0, 0, date("m"), date("d"),   date("Y")) - 60*60*25;

		$dateEnd = date("Ymd", $lastmonthEnd);
		$dateStart = date("Ymd", $lastmonth);
		try {
            $db = dbfactory::factory ($this->config['db']['calabrioMonit']['type'], $this->config['db']['calabrioMonit']['host'], $this->config['db']['calabrioMonit']['username'], $this->config['db']['calabrioMonit']['password'], $this->config['db']['calabrioMonit']['dbname']);
        }   catch (Exception $e) {
            die($e->getmessage());
        }
            
        $requete = $db->query ('SELECT date_of_check, 100 - (100 * SUM (missing_screen) / SUM (total)) as \'percentage_screen\', (SUM([generalMetadataPerc] * total / 100) *100 / SUM(total) ) as \'metadata\' 	FROM [calabrio_monit].[dbo].[stats] WHERE date_of_check < \''. $dateEnd .'\' and date_of_check > \''. $dateStart .'\' GROUP BY date_of_check', 'summary of the month');
//		return 1;
		$result = '';
        while ($res = $db->fetch_assoc ($requete)) {
			$result[] = $res;
        }
		 $DataSet = new pData;     
		 

		$dataToSet = '';
		$days = '';
		$metaData = '';
		
					

		foreach ($result as $tulp){
			$year = substr($tulp['date_of_check'], 0, 4);
			$mounth = substr($tulp['date_of_check'], 4, 2);
			$day = substr($tulp['date_of_check'], 6, 2);	
			$dateFormated = $day.'/'.$mounth;
			$dataToSet[] = $tulp['percentage_screen'];
			$days[] = $dateFormated;
			$metaData[] = round($tulp['metadata'], 2);
		}


		$DataSet->addPoints($dataToSet,"Screen Percentage");
		$DataSet->addPoints($metaData,"Metadatas Percentage");		
		$DataSet->addPoints($days,"Days");
		

	  }
	  
	  public function getSummaryOfTheMonthData(){
		$lastmonth = mktime(0, 0, 0, date("m")-1, 1,   date("Y"));
		$lastmonthEnd = mktime(0, 0, 0, date("m"), 1,   date("Y")) - 60*60*25;

		$dateEnd = date("Ymd", $lastmonthEnd);
		$dateStart = date("Ymd", $lastmonth);
		try {
            $db = dbfactory::factory ($this->config['db']['calabrioMonit']['type'], $this->config['db']['calabrioMonit']['host'], $this->config['db']['calabrioMonit']['username'], $this->config['db']['calabrioMonit']['password'], $this->config['db']['calabrioMonit']['dbname']);
        }   catch (Exception $e) {
            die($e->getmessage());
        }
            
        $requete = $db->query ('SELECT * FROM stats WHERE date_of_check < \''. $dateEnd .'\' and date_of_check > \''. $dateStart .'\'', 'Site Detail');
        
		$result = '';
        while ($res = $db->fetch_assoc ($requete)) {
			$result[] = $res;
        }
		
		
		$dataToSet = '';
		$days = '';
		$metaData = '';
		foreach ($result as $tulp){
			$year = substr($tulp['date_of_check'], 0, 4);
			$mounth = substr($tulp['date_of_check'], 4, 2);
			$day = substr($tulp['date_of_check'], 6, 2);	
			$dateFormated = $day.'/'.$mounth;
			$dataToSet[] = $tulp['percentage_screen'];
			$days[] = $dateFormated;
			$metaData[] = $tulp['generalMetadataPerc'];
		}
		
		
        return $result;
	  }
	  
	  */
}  
?>
