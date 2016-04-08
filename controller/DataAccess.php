<?php

class DataAccess
{
	private $_server = "localhost";
	private $_user = "root";
	private $_password = "";
	private $_db_name = "lelux";
	
	//private $_server = "localhost";
	//private $_user = "aya20540";
	//private $_password = "P-FgY39eDx";
	//private $_db_name = "aya20540_massage";
	
	private $_conn;
	private $_db;
	
	private function openConnection()
	{
		try {
			//$mysqli = new mysqli($this->_server, $this->_user, $this->_password, $this->_db_name);
			
			/* check connection */
			//if ($mysqli->connect_errno) {
				//throw new Exception('mysqli error => '.$mysqli->connect_error);
			//}
			
			$this->_conn = mysqli_connect($this->_server, $this->_user, $this->_password, $this->_db_name);
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




