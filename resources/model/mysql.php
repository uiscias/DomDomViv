<?php
class mysql extends dbfactory {

    protected $default_cfg = array(
             'host'   => 'localhost',
             'user'   => 'root',
             'passwd' => '',
             'name'   => 'domdomviv');

    // connection . la base
    protected function connect () {
        $this->_config['link'] = @mysqli_connect($this->_config['host'],
                                              $this->_config['user'],
                                              $this->_config['passwd'],
                                              $this->_config['name']);
        if ($this->_config['link'] === false ) {
            throw new Exception('Erreur lors de la connection DB : '.mysqli_connect_error().'.');
        }


    //    echo 'connection reussie avec '.__CLASS__;
    }

    // Fermeture de la base de donn.es au moment de la destruction de la classe.
    public function __destruct() {
       if (isset($this->_config['link'])) 
	@mysqli_close($this->_config['link']);
    }

    // cr.ation d'une requ.te et historisation
    public function query ($sql, $desc=NULL) {
        $start = microtime (true);
        $this->query = @mysqli_query ($this->_config['link'], $sql );
        $query_time = microtime (true) - $start;
        if ($this->query) {
            $this->query_id++;
            $this->history[$this->query_id] = array('desc' => $desc,
                                                    'query' => $sql,
                                                    'time' => $query_time);
            return $this->query;
    } else {
        throw new Exception (mysqli_connect_error());
        return false;
        }

    }

    // r.cup.re les r.sultats dans un tableau associatif
    public function fetch_assoc ($query=NULL) {
        if (isset($query)) {
            $this->query = $query;
        }
        return mysqli_fetch_assoc ($this->query);
    }

    public function free_result(){
        mysqli_free_result($this->query);
    }

    public function getArray(){
        $results = '';
        while ( $row = $this->query->fetch_object() ) {
            $results[] = $row;
        }

        $this->free_result();

        return $results;
    }
}
?>

