<?php

class DataAccess
{
	private $_conn_strings = array(
		0 => array("server" => "localhost", "user" => "root", "password" => "", "db" => "lelux") // dev
		, 1 => array("server" => "localhost", "user" => "leluxtha_sup1", "password" => "leluxsup1", "db" => "leluxtha_support") // production
		, 2 => array("server" => "localhost", "user" => "id2726991_test1", "password" => "test1", "db" => "id2726991_massage") // portfolio
	);
	
	//private $_server = "localhost";
	//private $_user = "leluxtha_sup1";
	//private $_password = "leluxsup1";
	//private $_db_name = "leluxtha_support";
	
	private $_conn;
	private $_db;
	
	private function openConnection()
	{
		try {
			$_conn_string = $this->_conn_strings[0];
	
			$_server = $_conn_string["server"];
			$_user = $_conn_string["user"];
			$_password = $_conn_string["password"];
			$_db_name = $_conn_string["db"];
	
			//$mysqli = new mysqli($this->_server, $this->_user, $this->_password, $this->_db_name);
			
			/* check connection */
			//if ($mysqli->connect_errno) {
				//throw new Exception('mysqli error => '.$mysqli->connect_error);
			//}
			
			$this->_conn = mysqli_connect($_server, $_user, $_password, $_db_name);
			// Setting character set to utf8 to support Thai language
			//
			//mysqli_query($this->_conn, "SET NAMES utf8");
			// *** But doing this way affect the function of upper the first letter of words
			
			//if (mysqli_connect_error()) throw new Exception('errorrr');
			
			//$this->_conn = mysql_connect($this->_server, $this->_user, $this->_password);// or die ('Unable to connect to MySQL');
			//$this->_db = mysql_select_db($this->_db_name, $this->_conn);// or die ('Unable to select database');
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	private function closeConnection()
	{
		try {
			mysqli_close($this->_conn);
			//mysql_close($this->_conn);
		} catch (Exception $e) {
			
		}
	}
	
	public function select($sql)
	{
		try {
			Utilities::logDebug($sql);
			
			$this->openConnection();
			
			$result = mysqli_query($this->_conn, $sql);
			//$result = mysql_query($sql, $this->_conn);// or die('Unable to query SQL');
			$result_array = array();
			
			while ($row = mysqli_fetch_assoc($result))
			{
				$result_array[] = array_change_key_case($row, CASE_LOWER);
			}
			
			$this->closeConnection();
			
			return $result_array;
		} catch (Exception $e) {
			throw $e;
		} 
	}
	
	private function manipulate($sql)
	{
		try {
			Utilities::logDebug($sql);
			
			$this->openConnection();
			
			$result = mysqli_multi_query($this->_conn, $sql);
			//mysql_query($sql, $this->_conn) or die('Unable to manipulate data in database');
			$affectedRows = mysqli_affected_rows($this->_conn);
			
			$this->closeConnection();
			
			return $affectedRows;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function insert($sql)
	{
		try {
			return $this->manipulate($sql);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function update($sql)
	{
		try {
			return $this->manipulate($sql);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function delete($sql)
	{
		try {
			return $this->manipulate($sql);
		} catch (Exception $e) {
			throw $e;
		}
	}
}

?>




