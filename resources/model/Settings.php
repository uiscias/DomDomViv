<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 30/05/2016
 * Time: 18:35
 */

namespace domdomviv;


class Settings
{
    private static $instance;                   // Instance courante de la classe
    private $_config;

    protected function __construct () {
        $requete = \dbfactory::singleton()->query ('SELECT * FROM settings', 'Get Config');

        while ($res = \dbfactory::singleton()->fetch_assoc ($requete)) {
            $this->_config[$res['name']] = $res['value'];
        }
    }

    public function getConfig($key){
        if (isset($this->_config[$key]))
            return $this->_config[$key];
        throw new \Exception('This config key does not exists.');
    }

    // n'autorise qu'une seule instance de la classe
    public static function singleton () {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

}