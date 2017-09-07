<?php
require_once '../controller/Utilities.php';

class FieldSorter {
	public $field;

	function __construct($field) {
		$this->field = $field;
	}

	function compare($a, $b) {
		$result;
		
		if ($a[$this->field] == $b[$this->field])
			$result = 0;
		
		if ($a[$this->field] > $b[$this->field])
			$result = 1;
		else
			$result = -1;
		
		//Utilities::logDebug("a[{$this->field}] = {$a[$this->field]} | b[{$this->field}] = {$b[$this->field]} | Result: ".$result);
		
		return $result;
	}
}
?>