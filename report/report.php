<?php
	require_once '../controller/PDF.php';
	require_once '../controller/ReportFunction.php';
	require_once '../controller/Utilities.php';
	
	if (!empty($_GET['report_type'])) {
		$reportType = $_GET['report_type'];
		
		if (!empty($_GET['criteria_data'])) {
			$pdf = new PDF("Lelux Thai Massage", "Client Confidential Information");
			$reportFunction = new ReportFunction();
			
			if ($reportType == 'CLIENT_REPORT') {
				$clientID = $_GET['criteria_data'];
				
				$htmlReportInfo = $reportFunction->getClientReport($clientID);
				
				$name = "Tony";
				$age = "99";
				
				$x = <<<sad
				<table>
						<tr><td>Name: </td><td>{$name}</td></tr>
						<tr><td>Age: </td><td>{$age}</td></tr>
								<tr><td><input type="checkbox" name="product1" value="Electrician" checked></td></tr>
								<tr><td><input type="checkbox" name="product1" value="Electrician" checked="true"></td></tr>
								<tr><td><input type="checkbox" name="product1" value="Electrician" checked="false"></td></tr>
								<tr><td><input type="checkbox" name="product1" value="Electrician" checked="1"></td></tr>
								<tr><td><input type="checkbox" name="product1" value="Electrician" checked="0"></td></tr>
						</table>
sad;
				
				$pdf->show('client-report', $htmlReportInfo);
				//$pdf->show('client-report', $x);
				
				//echo $htmlReportInfo;
			}
			else {
				echo 'Report type is not found.';
			}
		}
		else {
			echo 'Report data is empty.';
		}
	}
	else {
		echo 'Report type is empty.';
	}
	
	exit;
?>