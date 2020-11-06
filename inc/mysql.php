<?php
// Thaisigmaweb version 2.5
// Last Check : 11/09/2552 02:11

class mysql_database {
	var $db_link = null;
	var $query_count = 0;
	var $query_time = 0;
	var $last_query_time = 0;
	var $query_resource = null;
	var $persistant = false;
	var $command;
	
	// Constructer
	function mysql_database($db_host,$db_port = 3306,$db_user,$db_pass,$db_schema,$persistant = false){ 

		// Add port if available
		$db_connect = ($db_port > 0 ? $db_host.":".$db_port : $db_host);

		if (!$persistant){
			$this->db_link = mysql_connect($db_connect,$db_user,$db_pass);
		} else {
			$this->db_link = mysql_pconnect($db_connect,$db_user,$db_pass);
		}

		mysql_select_db($db_schema,$this->db_link);

		if ($this->db_link == false){
			exit;
			echo "Connection has problem";
		} else {
		    mysql_query("SET NAMES utf8");
			mysql_query("SET character_set_results=utf8");
			mysql_query("SET character_set_client='utf8'");
			mysql_query("SET character_set_connection='utf8'");
			
		}

		return $this->db_link;
	}

	// (resource) Query ����� sql
	function query($sql,$comment = ""){ 
		$start = $this->getmicrotime();
		$this->query_resource = mysql_query($sql,$this->db_link);
		$this->last_query_time = $this->getmicrotime() - $start;
		$i = ++$this->query_count;
		$this->query_time += $this->last_query_time;
		if ( DEBUG ){
			if ($this->query_resource){
				$fs = substr(ltrim($sql),0,1);
				$this->command[$i] = array(
					"i" => $i,
					"time" => $this->last_query_time(),
					"c" => ($fs == "S" ? $this->num_rows() : $this->affected_rows()),
					"query" => $sql,
					"color" => "sql_ok",
					"comment" => $comment,
				);

			} else {
				$this->command[$i] = array(
					"i" => $i,
					"time" => "xxxx",
					"c" => "0",
					"query" => $sql,
					"error" => "<br>".mysql_error(),
					"color" => "sql_fail",
					"comment" => $comment,
				);
			}
		}
		return $this->query_resource;
	}

	// (array) 
	function fetchs($result,$assoc = MYSQL_ASSOC){ 
		return mysql_fetch_array($result,$assoc);
	}

	// (array) 
	function fetch_query($sql,$comment = "",$assoc = MYSQL_ASSOC){
		return mysql_fetch_array($this->query($sql,$comment),$assoc);
	}

	// (int) 
	function num_rows(){ 	
		return mysql_num_rows($this->query_resource);
	}

	// (int) 
	function affected_rows(){ 	
		return mysql_affected_rows($this->db_link);
	}

	// (int) 
	function insert_id(){
		return mysql_insert_id($this->db_link);
	}

	// (string) 
	function last_query_time(){
		return sprintf("%01.5f",$this->last_query_time);
	}

	// (string) 
	function total_query_time(){ 	
		return sprintf("%01.5f",$this->query_time);
	}

	// (int) 
	function getmicrotime(){ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec);
	}
}

?>