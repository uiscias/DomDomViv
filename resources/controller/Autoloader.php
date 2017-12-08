<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 29/05/2016
 * Time: 17:08
 */

namespace domdomviv;

class Autoloader{

    /**
     * Enregistre notre autoloader
     */
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload($class){
        if (file_exists(MODEL_PATH.'/'.$class . '.php') == false)
            return false;
        require_once (MODEL_PATH.'/'.$class . '.php');
    }

}