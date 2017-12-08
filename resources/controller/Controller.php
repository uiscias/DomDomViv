<?php
namespace domdomviv;
require_once (MODEL_PATH.'/Catalogue.php');

class Controller {  
     public $catalogue;
	 
     public $config;
	 public $db;
  
     public function __construct($configParam)    
     {
         Autoloader::register();

         $this->config = $configParam;
         try {
             $this->db = \dbfactory::factory ($this->config['db']['domdomviv']['type'], $this->config['db']['domdomviv']['host'], $this->config['db']['domdomviv']['username'], $this->config['db']['domdomviv']['password'], $this->config['db']['domdomviv']['dbname']);
         }   catch (Exception $e) {
             die($e->getmessage());
         }

         $this->config = $configParam;

         $this->catalogue = new Catalogue($this->config);
     }   
     
	 public function getCatalogue(){
		return $this->catalogue;
	}
	 
     public function invoke()  
     {
          if (!isset($_GET['page']))  
          {
			$datas = $this->getGeneralOverview();
			
            renderLayoutWithContentFile("body.php", $datas, $this);
          } 
          else 
          {
			switch (strip_tags($_GET['page'])){
				case 'pushDatas':
                    $this->getCatalogue()->logDatas();
				    break;
                case 'checkTriggers':
                    $this->getCatalogue()->checkTriggers();
                    break;
                case 'fullCheck':
                    $this->getCatalogue()->logDatas();
                    $this->getCatalogue()->checkTriggers();
                    $this->getCatalogue()->generateGraphs();
                    Datalogger::getInstance(1)->cleanDatabase();
                    break;
                case 'generateGraphs':
                    $this->getCatalogue()->generateGraphs();
                    break;
                case 'changeStatus':
                    if (isset($_GET['sensor']) && isset($_GET['status']) && isset($_GET['device'])) {
                        if ($this->getCatalogue()->getDevice($_GET['device'])->getSensorByID($_GET['sensor'])->isRelay()){
                            if ($_GET['status'] == 0){
                                $this->getCatalogue()->getDevice($_GET['device'])->getSensorByID($_GET['sensor'])->setOff();
                            }else{
                                $this->getCatalogue()->getDevice($_GET['device'])->getSensorByID($_GET['sensor'])->setOn();
                            }
                        }
                    }
                    break;

				case 'readmore':
					$datas = '';
					renderLayoutWithContentFile("readMore.php", $datas, $this);

				
			
			default:
				$datas = 'no value';
				renderLayoutWithContentFile("error.php", $datas, $this);
			}
          }  
     }
	 	 
	 function getGeneralOverview(){
         foreach ($this->getCatalogue()->getDevicesList() as $device){
             $datas['devices'][] = $device;
         }
         $datas['alerts'] = Datalogger::getInstance(1)->getAlerts();
         return $datas;

     }
	 function sendemail($siteName, $subject, $data, $to){
	 
		if (ini_set("SMTP","mapper.mail.fedex.com")){}
		if (ini_set("smtp_port","25")){}
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: Calabrio Monitoring <calabrio.monitoring@fedex.com>"."\r\n";
		$test = mail($to, $subject, $data, $headers);


		if($test) {
			echo "<br />Messages de rapports envoyes !<br />To: ".$to."<br />";
		}
		else {
			echo "Code retour = ".$test." - ERREUR !";
		}

	}
}  

?>