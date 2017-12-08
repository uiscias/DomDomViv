<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 10/06/2016
 * Time: 15:57

 */
namespace domdomviv;

ini_set('display_errors', 1);
ini_set('error_log', 'C:\inetpub\wwwroot\log.log' );
/*
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

require_once(LIBRARY_PATH . "/templateFunctions.php");


//    Now you can handle all your php logic outside of the template
//    file which makes for very clean code!


$setInIndexDotPhp = "Hey! I was set in the index.php file.";

// Must pass in variables (as an array) to use in template
$variables = array(
'setInIndexDotPhp' => $setInIndexDotPhp
);

renderLayoutWithContentFile("home.php", $variables);
 */
include_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(realpath(dirname(__FILE__) . "/../controller/Controller.php"));
require_once(realpath(dirname(__FILE__) . "/../controller/Autoloader.php"));
include_once(LIBRARY_PATH . "/templateFunctions.php");

Autoloader::register();

$controller = new Controller($config);
//$controller->getCatalogue()->generateGraphs();
Datalogger::getInstance(1)->cleanDatabase();
\dbfactory::singleton()->__destruct();

?>
