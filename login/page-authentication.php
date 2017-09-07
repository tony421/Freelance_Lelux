<?php
	ob_start();
	
	require_once '../controller/Authentication.php';

	Authentication::authenticateUser();
	
	ob_end_clean();
?>