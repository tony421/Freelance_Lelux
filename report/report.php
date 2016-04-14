<?php
	require_once '../controller/PDF.php';
	require_once '../controller/ReportFunction.php';
	require_once '../controller/Utilities.php';
	
	if (!empty($_GET['report_type'])) {
		$reportType = $_GET['report_type'];
		Utilities::logInfo('Report | report_type: '.$reportType);
		
		if (!empty($_GET['criteria_data'])) {
			$pdf = new PDF();
			$reportFunction = new ReportFunction();
			
			if ($reportType == 'CLIENT_REPORT') {
				$clientID = $_GET['criteria_data'];
				Utilities::logInfo('Report | criteria_data[clientID]: '.$clientID);
				
				$htmlReportInfo = $reportFunction->getClientReport($clientID);
				
				$pdf->show('client-report', $htmlReportInfo);
				
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