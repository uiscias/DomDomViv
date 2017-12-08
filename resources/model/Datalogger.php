<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 30/05/2016
 * Time: 16:52
 */

namespace domdomviv;
require_once(realpath(dirname(__FILE__) . "/Settings.php"));
require_once(LIBRARY_PATH . "/pDraw.class.php");
require_once(LIBRARY_PATH . "/pImage.class.php");
require_once(LIBRARY_PATH . "/pData.class.php");


class Datalogger
{
    private static $_instances = array ();
    private $name;

    private function __construct (){
    }

    public function sendAlertMessage($message, $severity='NOTICE'){
        $requete = \dbfactory::singleton()->query ('INSERT INTO datalogger (dateTime, type,message,severity,datalogger) VALUES (now(), \'SendAlert\', \''.$message.'\', \''.$severity.'\', \''.$this->getInstanceName().'\')', 'SendAlert to datalogger');
    }

    public function sendTemperature($sensorID, $value){
        $requete = \dbfactory::singleton()->query ('INSERT INTO datalogger (dateTime, type,datalogger,unread,sensorID,temperature) VALUES (now(), \'Temp\', \''.$this->getInstanceName().'\',\'0\',\''.$sensorID.'\', \''.$value.'\')', 'Send temperature to datalogger');
    }

    public function sendHumidity($sensorID, $value){
        $requete = \dbfactory::singleton()->query ('INSERT INTO datalogger (dateTime, type,datalogger,unread,sensorID,humidity) VALUES (now(), \'Hum\', \''.$this->getInstanceName().'\',\'0\',\''.$sensorID.'\', \''.$value.'\')', 'Send humidity to datalogger');
    }

    public function sendTemperatureAndHumidity($sensorID, $temp, $hum){
        $requete = \dbfactory::singleton()->query ('INSERT INTO datalogger (dateTime, type,datalogger,unread,sensorID,temperature,humidity) VALUES (now(), \'Temp/Hum\', \''.$this->getInstanceName().'\',\'0\',\''.$sensorID.'\', \''.$temp.'\', \''.$hum.'\')', 'Send humidity and temperature to datalogger');
    }

    public function getAlerts($nbr=50)
    {
        $limite = Settings::singleton()->getConfig('AlertDelay');
        $limiteAlertes = strtotime("-" . $limite . " minutes");
        $nb = 0;
        $requete = \dbfactory::singleton()->query('SELECT ID, dateTime, severity, message FROM datalogger WHERE (type = \'SendAlert\' OR type = \'Alert\') AND unread = 1 AND dateTime >= \'' . $limiteAlertes . '\' ORDER BY dateTime desc LIMIT 15', 'get Alertes');
        while ($res = \dbfactory::singleton()->fetch_assoc($requete)) {
            $datas[] = $res;
            $nb++;
        }
        if ($nb == 0) return -1;
        return $datas;
    }

    public function getHistoricalStatistics($sensorID, $period)
    {
               $q = "SELECT dateTime, sensorID, type, round(avg(temperature),2) as 'temperature', round(avg(humidity),2) as 'humidity' FROM datalogger ";
            $q .= "WHERE type = 'Temp/Hum' AND temperature IS NOT NULL AND humidity IS NOT NULL AND sensorID = ".$sensorID." AND dateTime > date_add(curdate(), interval -".$period." hour) ";
            $q .= "GROUP BY UNIX_TIMESTAMP(dateTime) DIV 1800, sensorID, type ORDER BY dateTime desc";
            $requete = \dbfactory::singleton()->query($q,'get historical stats ');
            $result = \dbfactory::singleton()->getArray();
            return $result;
 
/*        $result = array();
        for ($i = $period; $i >= 0; $i--) {
            $q = "select date_add(curdate(),interval $i hour) as 'dateTime','sensorID', round(avg(temperature),2) as 'temperature',round(avg(humidity),2) as 'humidity' ";
            $q = $q . "from datalogger ";
            $q = $q . "where sensorID = '$sensorID' ";
            $q = $q . "and dateTime >= date_add(curdate(),interval $i hour) ";
            $ii = $i + 1;
            $q = $q . "and dateTime < date_add(curdate(),interval $ii hour) order by dateTime asc";
            $requete = \dbfactory::singleton()->query($q,'get historical stats '.$i);
            $temp = \dbfactory::singleton()->fetch_assoc($requete);
            if (!isset($temp['temperature']) || !isset($temp['humidity'])) continue;
            $result[] = $temp;
        }
*/
        return $result;
    }

    public function generateHistoricalGraph($sensor, $period){
/*        $sensorID = $sensor->getID();
        $result = array();
        for ($i = 0; $i <= $period; $i++) {
            $q = "select date_add(curdate(),interval $i hour) as 'dateTime','sensorID', round(avg(temperature),2) as 'temperature',round(avg(humidity),2) as 'humidity' ";
            $q = $q . "from datalogger ";
            $q = $q . "where sensorID = '$sensorID' ";
            $q = $q . "and dateTime >= date_add(curdate(),interval $i hour) ";
            $ii = $i + 1;
            $q = $q . "and dateTime < date_add(curdate(),interval $ii hour) ";
            $requete = \dbfactory::singleton()->query($q,'get historical stats '.$i);
            $temp = \dbfactory::singleton()->fetch_assoc($requete);
            if (!isset($temp['temperature']) || !isset($temp['humidity'])) continue;
            $result[] = $temp;
        }
        $myDatasetHumAbs = Array();
        $myDatasetHum = Array();
        $myDatasetTempAbs = Array();
        $myDatasetTemp = Array();
        foreach ($result as $res){
            if (isset($res['temperature'])){
                $myDatasetTempAbs[] = $res['dateTime'];
                $myDatasetTemp[] = $res['temperature'];
            }
            if (isset($res['humidity'])){
                $myDatasetHumAbs[] = $res['dateTime'];
                $myDatasetHum[] = $res['humidity'];
            }
        }
*/

        $sensorID = $sensor->getID();
        $q = "SELECT dateTime, sensorID, type, round(avg(temperature),2) as 'temperature', round(avg(humidity),2) as 'humidity' FROM datalogger ";
        $q .= "WHERE type = 'Temp/Hum' AND temperature IS NOT NULL AND humidity IS NOT NULL AND sensorID = ".$sensorID." AND dateTime > date_add(curdate(), interval -".$period." hour) ";
        $q .= "GROUP BY UNIX_TIMESTAMP(dateTime) DIV 1800, sensorID, type ORDER BY dateTime desc";
        $requete = \dbfactory::singleton()->query($q,'get historical stats ');
        $result = \dbfactory::singleton()->getArray();

        $myDatasetHumAbs = Array();
        $myDatasetHum = Array();
        $myDatasetTempAbs = Array();
        $myDatasetTemp = Array();
        foreach ($result as $res){
            if (isset($res->temperature)){
                $myDatasetTempAbs[] = $res->dateTime;
                $myDatasetTemp[] = $res->temperature;
            }
            if (isset($res->humidity)){
                $myDatasetHumAbs[] = $res->dateTime;
                $myDatasetHum[] = $res->humidity;
            }
        }

        $myData = new \pData();
        $myDataHum = new \pData();

        $myData->addPoints($myDatasetTemp, "temperature");
        $myData->addPoints($myDatasetTempAbs, "time");

        $myData->setAbscissa("time");
        $myData->setAxisPosition(0,AXIS_POSITION_LEFT);
        $myData->setAxisName(0,"Températures");
        $myData->setAxisUnit(0,"°C");


        $myDataHum->addPoints($myDatasetHum, "humidity");
        $myDataHum->addPoints($myDatasetHumAbs, "time");

        $myDataHum->setAbscissa("time");
        $myDataHum->setAxisPosition(0,AXIS_POSITION_LEFT);
        $myDataHum->setAxisName(0,"Humidity");
        $myDataHum->setAxisUnit(0,"%");

        $myData->setPalette("temperature",
            array("R" => 240,"G" => 16, "B" =>16, "Alpha" => 100));
        $myDataHum->setPalette("humidity",
            array("R" => 16, "G" => 240, "B" => 16, "Alpha" => 100));

        $myImage = new \pImage(1000,400,$myData);

        $myImageHum = new \pImage(1000,400,$myDataHum);


        $myImage->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));
        $myImageHum->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

        $TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE, "R"=>0, "G"=>0, "B"=>0);
        $TextSettings2 = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE, "R"=>0, "G"=>0, "B"=>0);

        $myImage->drawText(500,25,"Températures",$TextSettings);
        $myImageHum->drawText(500,25,"Humidité",$TextSettings2);

        $myImage->setShadow(FALSE);
        $myImageHum->setShadow(FALSE);

        $myImage->setGraphArea(75,50,975,360);
        $myImageHum->setGraphArea(75,50,975,360);

        $myImage->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>LIBRARY_PATH . "/fonts/verdana.ttf","FontSize"=>10));
        $myImageHum->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>LIBRARY_PATH . "/fonts/verdana.ttf","FontSize"=>10));

        $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
        , "Mode"=>SCALE_MODE_FLOATING
        , "LabelingMethod"=>LABELING_ALL
        , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
        $Settings2 = array("Pos"=>SCALE_POS_LEFTRIGHT
        , "Mode"=>SCALE_MODE_FLOATING
        , "LabelingMethod"=>LABELING_ALL
        , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);

        $myImage->drawScale($Settings);
        $myImageHum->drawScale($Settings2);

        $myImage->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));
        $myImageHum->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

        $Config = "";
        $Config2 = "";
        $myImage->drawSplineChart($Config);
        $myImageHum->drawSplineChart($Config2);

        $Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>LIBRARY_PATH . "/fonts/Forgotte.ttf", "FontSize"=>12, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
        , "Mode"=>LEGEND_HORIZONTAL
        , "Family"=>LEGEND_FAMILY_LINE
        );
        $Config2 = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>LIBRARY_PATH . "/fonts/Forgotte.ttf", "FontSize"=>12, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
        , "Mode"=>LEGEND_HORIZONTAL
        , "Family"=>LEGEND_FAMILY_LINE
        );

        $myImage->drawLegend(837,16,$Config);
        $myImageHum->drawLegend(837,16,$Config2);


        $myImage->Render(IMAGES_PATH . '/charts/'.$sensorID.'_'.$period.'_temp.png');
        $myImageHum->Render(IMAGES_PATH . '/charts/'.$sensorID.'_'.$period.'_hum.png');
    }
    
    function cleanDatabase(){
        $requete = \dbfactory::singleton()->query('DELETE FROM datalogger WHERE dateTime < date_add(curdate(), interval -' . Settings::singleton()->getConfig('keepDataLoggerHistory') . ' hour)' , 'Clean Database');
    }
    public function getInstanceName(){
        return $this->name;
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    /**
     * Clones are forbidden
     */
    private function __clone (){
        throw new \Exception('Clones are forbidden');
    }

    /**
     * Creation method
     * @param string $pInstanceName name of the expected instance
     * @return Datalogger
     */
    public static function getInstance ($pInstanceName){
        if (! array_key_exists ($pInstanceName, self::$_instances)){
            self::$_instances[$pInstanceName] = new Datalogger ();
            self::$_instances[$pInstanceName]->setName($pInstanceName);
        }
        return self::$_instances[$pInstanceName];
    }
}
