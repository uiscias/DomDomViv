<?php
class mssql extends dbfactory {

    protected $default_cfg = array(
             'host'   => 'localhost',
             'user'   => 'root',
             'passwd' => '',
             'name'   => 'test');

// connection à la base
    protected function connect () {

        $mssql_server = $this->_config['host'];
        $mssql_data = array("UID" => $this->_config['user'],
                        "PWD" => $this->_config['passwd'],
                        "Database" => $this->_config['name']);
        if(! ISSET ($dbconnect)){
            $this->_config['link'] = sqlsrv_connect($mssql_server, $mssql_data);
        }
        if(! $this->_config['link']){
            throw new Exception('Erreur lors de la connection vers : '.$this->_config['host'].'. ' . print_r( sqlsrv_errors(), true));
        }else{
//            echo "conn succeeed";

            
            
        }
    }

    // Fermeture de la base de données au moment de la destruction de la classe.
    public function __destruct() {
   	    
        sqlsrv_close($this->_config['link']);
    }

     public function query ($sql, $desc=NULL) {
        $start = microtime(true);
        $this->query = @sqlsrv_query( $this->_config['link'], $sql);
        $query_time = microtime (true) - $start;
        if ($this->query) {
            $this->query_id++;
            $this->history[$this->query_id] = array ('desc' => $desc,
                                                    'query' => $sql,
                                                    'time' => $query_time);
    	

            return $this->query;
        } else {
            throw new Exception("Error in executing query: $sql".print_r( sqlsrv_errors(), true));
            return false;
        }
        
                

    }
    public function has_rows ($query=NULL) {
        if (isset ($query)) {
            $this->query = $query;
        }
		if (sqlsrv_has_rows($this->query)){
			return 1;
		} else {
			return 0;
		}
		// return 0;
	}
	
	public function getLastInsertedIndex(){
	
		$this->query = @sqlsrv_query( $this->_config['link'], 'SELECT SCOPE_IDENTITY() AS ins_id');

        if ($this->query) {
			$id = sqlsrv_fetch_array ($this->query, SQLSRV_FETCH_ASSOC);
            return $id['ins_id'];
        } else {
            throw new Exception("Error in executing query: $sql".print_r( sqlsrv_errors(), true));
            return false;
        }
	}
	
    public function fetch_assoc ($query=NULL) {
        if (isset ($query)) {
            $this->query = $query;
        }
		//if ($this->has_rows($query)){
		//	echo $this->has_rows($query);
			$array = sqlsrv_fetch_array ($query, SQLSRV_FETCH_ASSOC);
//		var_dump($this->history);
			//var_dump($array);
//			echo '<br><br>';
			return $array;
		 // }else{ 
			// return NULL;
		 // }
    }
}
?>