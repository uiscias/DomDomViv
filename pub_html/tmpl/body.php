
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    <!--
    google.load("visualization", "1", {packages:["gauge"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        <?php
        foreach ($variables['devices'] as $device){
            foreach ($device->getSensorsList() as $sensor){
                try{
                    if ($device->getID() == 1) {
                        if ($sensor->isTemperatureSensor()) {
                            echo "var data = google.visualization.arrayToDataTable([ ['Label', 'Value'],";
                            echo "['" . $sensor->getName() . " *C', " . $sensor->getTemperature() . '],]);';
                            echo "var options = {min:" . domdomviv\Settings::singleton()->getConfig('tempSensorMin') . ", max:" . domdomviv\Settings::singleton()->getConfig('tempSensorMax') . ",width: 200, height: 200,minorTicks: 5};";
                            echo "var chart = new google.visualization.Gauge(document.getElementById('" . $device->getName() . $sensor->getName() . "Temp_div'));";
                            echo "chart.draw(data, options);";
                        }
                        if ($sensor->isHumiditySensor()) {
                            echo "var data = google.visualization.arrayToDataTable([ ['Label', 'Value'],";
                            echo "['" . $sensor->getName() . " %h', " . $sensor->getHumidity() . '],]);';
                            echo "var options = {min:" . domdomviv\Settings::singleton()->getConfig('humSensorMin') . ", max:" . domdomviv\Settings::singleton()->getConfig('humSensorMax') . ",width: 200, height: 200,minorTicks: 5};";
                            echo "var chart = new google.visualization.Gauge(document.getElementById('" . $device->getName() . $sensor->getName() . "Hum_div'));";
                            echo "chart.draw(data, options);";
                        }
                    } else {
                        if ($sensor->isTemperatureSensor()) {
                            echo "var data = google.visualization.arrayToDataTable([ ['Label', 'Value'],";
                            echo "['" . $sensor->getName() . " *C', " . $sensor->getTemperature() . '],]);';
                            echo "var options = {min:" . domdomviv\Settings::singleton()->getConfig('tempSensorMin') . ", max:" . domdomviv\Settings::singleton()->getConfig('tempSensorMax') . ",width: 200, height: 200,redFrom: " . domdomviv\Settings::singleton()->getConfig('tempSensorRedStart') . ", redTo: " . domdomviv\Settings::singleton()->getConfig('tempSensorRedStop') . ", yellowFrom:" . domdomviv\Settings::singleton()->getConfig('tempSensorYellowStart') . ", yellowTo: " . domdomviv\Settings::singleton()->getConfig('tempSensorYellowStop') . ",minorTicks: 5};";
                            echo "var chart = new google.visualization.Gauge(document.getElementById('" . $device->getName() . $sensor->getName() . "Temp_div'));";
                            echo "chart.draw(data, options);";
                        }
                        if ($sensor->isHumiditySensor()) {
                            echo "var data = google.visualization.arrayToDataTable([ ['Label', 'Value'],";
                            echo "['" . $sensor->getName() . " %h', " . $sensor->getHumidity() . '],]);';
                            echo "var options = {min:" . domdomviv\Settings::singleton()->getConfig('humSensorMin') . ", max:" . domdomviv\Settings::singleton()->getConfig('humSensorMax') . ",width: 200, height: 200,redFrom: " . domdomviv\Settings::singleton()->getConfig('humSensorRedStart') . ", redTo: " . domdomviv\Settings::singleton()->getConfig('humSensorRedStop') . ", yellowFrom:" . domdomviv\Settings::singleton()->getConfig('humSensorYellowStart') . ", yellowTo: " . domdomviv\Settings::singleton()->getConfig('humSensorYellowStop') . ",minorTicks: 5};";
                            echo "var chart = new google.visualization.Gauge(document.getElementById('" . $device->getName() . $sensor->getName() . "Hum_div'));";
                            echo "chart.draw(data, options);";
                        }
                    }
                }catch (Exception $e){

                }
            }
        }

        ?>
    }
    -->
</script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]}); google.setOnLoadCallback(drawChart);function drawChart() {

        <?php
        foreach ($variables['devices'] as $device) {
            foreach ($device->getSensorsList() as $sensor) {
                try{
                    $datas = domdomviv\Datalogger::getInstance(1)->getHistoricalStatistics($sensor->getID(), domdomviv\Settings::singleton()->getConfig('graphPeriod'));

                    if ($sensor->isTemperatureSensor()) {
                        echo "var data = google.visualization.arrayToDataTable([['TIME', 'TEMP', ],\n\n";
                        foreach ($datas as $res) {
                            echo "['" . $res->dateTime . "', ";
                            echo " " . $res->temperature . " ],";
                        }
                        echo "]);\n\n";

                        echo "var options = {title: 'TEMP (C) ".domdomviv\Settings::singleton()->getConfig('graphPeriod')." HR', curveType: 'function',legend: { position: 'none' },hAxis: { textPosition: 'none', direction: '-1' },};";
                        echo "var chart = new google.visualization.LineChart(document.getElementById('" . $device->getName() . $sensor->getName() . "HistTemp_div'));";
                        echo "chart.draw(data, options);";
                    }
                    if ($sensor->isHumiditySensor()) {
                        echo "var data = google.visualization.arrayToDataTable([['TIME', 'HUM', ],\n\n";
                        foreach ($datas as $res) {
                            echo "['" . $res->dateTime . "', ";
                            echo " " . $res->humidity . " ],";
                        }
                        echo "]);\n\n";

                        echo "var options = {title: 'HUMIDITY (%) ".domdomviv\Settings::singleton()->getConfig('graphPeriod')." HR', curveType: 'function',legend: { position: 'none' },hAxis: { textPosition: 'none', direction: '-1' },};";
                        echo "var chart = new google.visualization.LineChart(document.getElementById('" . $device->getName() . $sensor->getName() . "HistHum_div'));";
                        echo "chart.draw(data, options);";
                    }
                }catch (Exception $e){

                }
            }
        }
        ?>
    }
</script>




<!-- Intro Header -->
<!-- Full Page Image Background Carousel Header -->
<header id="intro-carousel" class="carousel slide">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <div id="parallax-slide" class="fill"></div>
            <div class="carousel-caption">
                <h1 class="animated slideInDown">Current alerts</h1>

                <?php
                if ($variables['alerts'] == -1) echo '<p><b>There is currently no alert.</b></p>';
                else {
                    echo '<p><b>LAST UPDATE: '. $variables['alerts'][0]['dateTime'] . '</b></p>';
                    echo '<div class="citation" align="left" width="100%" height="100%">';

                    echo "<textarea>";
                    foreach ($variables['alerts'] as $alert){
                        if($alert['severity'] == 'WARNING' || $alert['severity'] == 'ERROR'){
                            echo '<b><font color="red">[', $alert['dateTime'], ' : ',$alert['severity'],'] ',$alert['message'],"</font></b>\n";
                        }
                        else {
                            echo '[', $alert['dateTime'], ' : ',$alert['severity'],'] ',$alert['message'],"\n";
                        }

                    }
                    echo "</textarea>";
                    echo '</div>';
                }
                ?>



            </div>
            <div class="overlay-detail"></div>
        </div><!-- /.item -->
    </div>
    <a href="#currcond"><div class="mouse"></div></a>
</header>




<!-- Current Conditions Section -->
<section id="currcond" class="team content-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Current conditions</h2>
                <h3 class="caption gray">
                    <?php
                    echo 'LAST UPDATE: '.date("Y-m-d H:i:s").'</h3></div><!-- /.col-md-12 -->';

                    echo '<div class="container"><div class="row">';

                    foreach ($variables['devices'] as $device) {
                        foreach ($device->getSensorsList() as $sensor) {
                            try{
                                if ($sensor->isRelay()) continue;
                                $emptyblob = false;

                                if ($sensor->isTemperatureSensor()) {
                                    $sensor->getTemperature();
                                    $emptyblob = true;
                                    echo "<div class=\"col-md-4\">";
                                    echo "<div class=\"team-member\"><figure><div id=\"" . $device->getName() . $sensor->getName() . "Temp_div"."\" align=\"center\"></div>";
                                    echo "<figcaption><p>Device: " . $device->getName() . "<br />Sensor : ".$sensor->getName()."&nbsp;&nbsp; (on pin: ".$sensor->getPinNumber().")<br />".$sensor->getDescription()."<br/>" . $sensor->getTemperature() . " °C</p></figcaption></figure>";
                                    echo " <h4>" . $device->getName() . " Temperature</h4></div><!-- /.team-member -->";
                                }
                                if ($sensor->isHumiditySensor()) {
                                    $sensor->getHumidity();
                                    if (!$emptyblob) echo "<div class=\"col-md-4\">";
                                    echo "<div class=\"team-member\"><figure><div id=\"" . $device->getName() . $sensor->getName() . "Hum_div" . "\" align=\"center\"></div>";
                                    echo "<figcaption><p>Device: " . $device->getName() . "<br />Sensor : ".$sensor->getName()."&nbsp;&nbsp; (on pin: ".$sensor->getPinNumber().")<br />".$sensor->getDescription()."<br/>" . $sensor->getHumidity() . " % humidity</p></figcaption></figure>";
                                    echo " <h4>" . $device->getName() . " Humidity</h4></div><!-- /.team-member --></div>";

                                } else if (!$emptyblob) echo '</div>';
                            }catch (Exception $e){
                                if ($sensor->isTemperatureSensor() || $sensor->isHumiditySensor()) {
                                    echo "<div class=\"col-md-4\">";
                                    echo "<div class=\"team-member\"><figure><img src=\"assets/images/gaugeError.png\" />";
                                    echo "<figcaption><p>Device: " . $device->getName() . "<br />Sensor : " . $sensor->getName() . "&nbsp;&nbsp; (on pin: " . $sensor->getPinNumber() . ")<br /> <b><big>Is not readable!</big></b><br/></p></figcaption></figure>";
                                    echo " <h4>" . $device->getName() . "</h4></div><!-- /.team-member --></div>";
                                }
                            }
                        }
                    }
                    ?>
            </div><!-- /.row -->
        </div><!-- /.container -->

    </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.Current Conditions -->






<!-- History Section -->
<section id="history" class="portfolio content-section parallax">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Historical reporting</h2>
                <h3 class="caption white">These graphics are based on last <?php echo (domdomviv\Settings::singleton()->getConfig('graphPeriod')); ?> hours.</h3>
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

    <div class="container project-container text-center">
        <div class="recent-project-carousel owl-carousel owl-theme popup-gallery">

            <?php
            foreach ($variables['devices'] as $device) {
                foreach ($device->getSensorsList() as $sensor) {
                    if ($sensor->isTemperatureSensor()) {
                        echo "\n<div class=\"item recent-project\"><div id=\"" . $device->getName() . $sensor->getName() . "HistTemp_div\"></div>";
                        echo "\n<div class=\"project - info\"><h4>Device: " . $device->getName() . " (Temp)<br />Sensor : " . $sensor->getName() . "&nbsp;&nbsp; (on pin: " . $sensor->getPinNumber() . ")</h4>";
                        echo "\n<ul class=\"project-meta\"><li><a href=\"#\">".$device->getName()." historical reporting</a></li></ul></div><!-- /.project-info -->";
                        echo "\n<div class=\"full-project\"><a href=\"". BASE_URL . "/images/charts/".$sensor->getID()."_".domdomviv\Settings::singleton()->getConfig('graphPeriod')."_temp.png\" title=\"" . $device->getName()." - ".$sensor->getName(). " historical\">" . $device->getName() ." - ".$sensor->getName(). " Temp<i class=\"fa fa-chevron-right\"></i></a></div><!-- /.full-project --></div><!-- /.item -->";
                      }
                    if ($sensor->isHumiditySensor()) {
                        echo "\n<div class=\"item recent-project\"><div id=\"" . $device->getName() . $sensor->getName() . "HistHum_div\"></div>";
                        echo "\n<div class=\"project - info\"><h4>Device: " . $device->getName() . " (Hum)<br />Sensor : " . $sensor->getName() . "&nbsp;&nbsp; (on pin: " . $sensor->getPinNumber() . ")</h4>";
                        echo "\n<ul class=\"project-meta\"><li><a href=\"#\">" . $device->getName() . " historical reporting</a></li></ul></div><!-- /.project-info -->";
                        echo "\n<div class=\"full-project\"><a href=\"". BASE_URL . "/images/charts/".$sensor->getID()."_".domdomviv\Settings::singleton()->getConfig('graphPeriod')."_hum.png\" title=\"" . $device->getName()." - ".$sensor->getName(). " historical\">" . $device->getName() ." - ".$sensor->getName(). " Hum<i class=\"fa fa-chevron-right\"></i></a></div><!-- /.full-project --></div><!-- /.item -->";
                    }
                }
            }

            ?>
        </div><!-- /.recent-project-carousel -->

        <div class="customNavigation project-navigation text-center">
            <a class="btn-prev"><i class="fa fa-angle-left fa-2x"></i></a>
            <a class="btn-next"><i class="fa fa-angle-right fa-2x"></i></a>
        </div><!-- /.project-navigation -->

    </div><!-- /.container -->
</section><!-- /.History -->






<!-- control Section -->
<section id="control" class="services content-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Manual Control</h2>
                <h3 class="caption gray">Be careful changes are made immediately.</h3>
            </div><!-- /.col-md-12 -->
            <div class="container">
                <div class="row text-center">
                    <?php
                    foreach ($variables['devices'] as $device) {
                        foreach ($device->getSensorsList() as $sensor) {
                            if ($sensor->isRelay()) {
                                echo "<div class=\"col-md-4\"><div class=\"row services-item sans-shadow text-center\">";
                                if($sensor->isOn()){
                                    echo "<img id='btn_".$sensor->getID()."' src='assets/images/".$sensor->getRelayType()."On.png' width=\"70%\" alt='on'/><br /><br />";
                                }else{
                                    echo "<img id='btn_".$sensor->getID()."' src='assets/images/".$sensor->getRelayType()."Off.png' width=\"70%\" alt='off'/><br /><br />";
                                }

                                echo "<h4>".$device->getName()." - ".$sensor->getName()."</h4>";
                                echo "<p>".$sensor->getDescription()."</p>";
                                echo "</div><!-- /.row --></div><!-- /.col-md-4 -->";
                            }
                        }
                    }
                    ?>
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.section -->

<script type="text/javascript">
    //this function sends and receives the pin's status
    function change_pin (device, sensor, status) {
//        //this is the http request
        var request = new XMLHttpRequest();
        request.open( "GET" , "index.php?page=changeStatus&sensor=" + sensor + "&status=" + status  + "&device=" + device );
        request.send(null);
        //receiving information
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                return (parseInt(request.responseText));
            }
            else if (request.readyState == 4 && request.status == 500) {
                alert ("server error");
                return ("fail");
            }
            else { return ("fail"); }
        }
        return ! status;
    }
    <?php

    foreach ($variables['devices'] as $device) {
        foreach ($device->getSensorsList() as $sensor) {
            if ($sensor->isRelay()) {
                echo "var btn_".$sensor->getID()." = document.getElementById(\"btn_".$sensor->getID()."\");\n";
                echo "btn_".$sensor->getID().".addEventListener(\"click\", function () { \n";
                echo "if ( btn_".$sensor->getID().".alt === \"off\" ) { \n";
                echo "var new_status = change_pin ( ".$sensor->getDevice()->getID().", ".$sensor->getID().", '1');\n";
                echo "if (new_status !== \"fail\") {\n";
                echo "btn_".$sensor->getID().".alt = \"on\";\n";
                echo "btn_".$sensor->getID().".src = \"assets/images/".$sensor->getRelayType()."On.png\";\n";
                echo "return 0;}}\n";

                echo "if ( btn_".$sensor->getID().".alt === \"on\" ) {\n";
                echo "var new_status = change_pin ( ".$sensor->getDevice()->getID().", ".$sensor->getID().", '0');\n";                
                echo "if (new_status !== \"fail\") {btn_".$sensor->getID().".alt = \"off\";\n";
                echo "btn_".$sensor->getID().".src = \"assets/images/".$sensor->getRelayType()."Off.png\";\n";
                echo "return 0;\n";
                echo "}}});\n";
            }
        }
    }
    ?>
</script>







<!-- Settings -->
<section id="Settings" class="our-clients content-section text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Settings</h2>
                <h3 class="caption white">All the basic configuration can be found here.</h3>
            </div><!-- /.col-md-12-->
        </div><!-- /.row -->

        <div class="row client-slider">
            <div class="item col-xs-4 col-md-2 i">
                <a href="#" title="#">
                    <br /><br /><br /><br /><br /><br />
                </a>
            </div>


        </div><!-- /.row -->

    </div><!-- /.container -->
</section><!-- /.Settings -->







<!-- Call to action - two section -->
<section id = "about" class="cta-two-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <h3>Have an idea?</h3>
                <p>We’re here to help you manage your work</p><br /><br /><br /><br /><br /><br /><br />
            </div>
            <div class="col-sm-3">
                <a href="#" class="btn btn-overcolor">Get in touch</a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.cta-two-section -->
