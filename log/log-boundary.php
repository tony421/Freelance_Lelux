<?php
	require_once '../controller/Utilities.php';
	
	$errorMsg = $_POST['data'];
	Utilities::logError($errorMsg);
	
	echo json_encode([]); // if return by "" (empty string) will cause infinite loop
	exit;
?>