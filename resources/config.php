<?php
 
/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/
 
$config = array(
    "db" => array(
        "domdomviv" => array(
            "dbname" => "domdomViv",
            "username" => "root",
            "password" => "fade",
            "host" => "127.0.0.1",
            "type" => "mysql"
        )
    ),
    "urls" => array(
        "baseUrl" => "http://192.168.2.15/"
    ),
    "paths" => array(
        "resources" => $_SERVER["DOCUMENT_ROOT"] . "/../resources",
        "images" => array(
            "content" => $_SERVER["DOCUMENT_ROOT"] . "/images"
        )
    )
);

/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("BASE_URL")
or define("BASE_URL", 'http://192.168.2.15/');
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
defined("MODEL_PATH")
    or define("MODEL_PATH", realpath(dirname(__FILE__) . '/../resources/model'));     
defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/../pub_html/tmpl'));
defined("IMAGES_PATH")
    or define("IMAGES_PATH", realpath(dirname(__FILE__) . '/../pub_html/images'));	
defined("DEBUG")
    or define("DEBUG", true);
defined("MAILS")
    or define("MAILS", false);
defined("THRESHOLD_MAILS")
    or define("THRESHOLD_MAILS", true); 

/*
    Error reporting.
*/
ini_set("error_reporting", E_ALL);
error_reporting(E_ALL);
 
?>
