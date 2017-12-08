<?php
abstract class dbfactory {

    private static $instance;                   // Instance courante de la classe
    
    protected $_config;                         // Param?tres de configuration base de donn?e.
    protected $query;                           // Ressource de query
    
    public $history = array();                  // Historique des requ?tes
    public $query_id = 0;                       // Compteur de requ?tes.

    // Initialise les variables de connections et active la connection ? la base de donn?e.
    // Constructeur prot?g? permettant de n'avoir qu'une unique instance de la classe gr?ce ? la m?thode singleton
     protected function __construct ($host=NULL, $user=NULL, $passwd=NULL, $name=NULL) {
        if ( !is_array($this->default_cfg) ) {
         throw new Exception('Vous devez remplir les param?tres de la configuration par defaut de votre base de donn?e');
        }

        foreach ($this->default_cfg as $key=>$val ) {
             $this->_config[$key] = (isset($$key) ) ? $$key : $val;
        }
		
        unset($this->default_cfg); // Enl?ve les param?tres par defaut pour ?viter toute confusion possible.
        
		$this->connect();
    }
   
    // usinage : permet d'instancier la classe correcte en fonction de la db choisie
    public static function factory ($type, $host=NULL, $user=NULL, $passwd=NULL, $name=NULL) {
        if (class_exists ($type)) {
            $className = $type;
            if (!isset(self::$instance)) {
                self::$instance = new $className ($host, $user, $passwd, $name);
                return self::$instance;
            }
            return new $className ($host, $user, $passwd, $name);
        } else {
            throw new Exception ('Pas d\'implementation disponible pour ' . $type);
        }
    }
    public static function create ($connectionString){
        if (($driverEndPos = strpos ($connectionString, ':')) === false){
            throw new Exception ('Mauvaise chaine de connexion');
        }
        $type = (substr ($connectionString, 0, $driverEndPos));
        if (class_exists ($type)) {
            $className = $type;
            if (!isset(self::$instance)) {
                self::$instance = new $className (parse_url($connectionString, PHP_URL_HOST), parse_url($connectionString, PHP_URL_USER), parse_url($connectionString, PHP_URL_PASS), ltrim(parse_url($connectionString, PHP_URL_PATH),'/'));
                return self::$instance;
            }
            return new $className (parse_url($connectionString, PHP_URL_HOST), parse_url($connectionString, PHP_URL_USER), parse_url($connectionString, PHP_URL_PASS), ltrim(parse_url($connectionString, PHP_URL_PATH),'/'));
        } else {
            throw new Exception ('Pas d\'implementation disponible pour ' . $type);
        }
    }
    
    // n'autorise qu'une seule instance de la classe
    public static function singleton () {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    // on avertit le d?veloppeur qu'il n'a pas le droit de cloner l'objet instanci?
    public function __clone() {
       trigger_error('Le cl?nage n\'est pas autoris?.', E_USER_ERROR);
    }

    // m?thodes abstraites
    abstract protected function connect();
    abstract public function __destruct();
    abstract public function query($sql, $desc=NULL);
    abstract public function fetch_assoc($query=NULL);
    /*
    abstract public function num_rows($query=NULL);
    abstract public function fetch_row($query=NULL);
    abstract public function fetch_array($query=NULL);
    abstract public function free();
    */

}
?>
