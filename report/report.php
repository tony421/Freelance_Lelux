<?php
	require_once '../controller/PDF.php';
	require_once '../controller/ReportFunction.php';
	require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (!empty($_GET['report_type'])) {
		$reportType = $_GET['report_type'];
		Utilities::logInfo('Report | report_type: '.$reportType);
		
		$pdf = new PDF();
		$reportFunction = new ReportFunction();
		
		if ($reportType == 'CLIENT_REPORT') {
			if (!empty($_GET['client_id'])) {
				$clientID = $_GET['client_id'];
				Utilities::logInfo('Report | criteria_data[clientID]: '.$clientID);
				
				$htmlReportInfo = $reportFunction->getClientReport($clientID);
				
				$pdf->show('client-report', $htmlReportInfo);
				//echo $htmlReportInfo;
			}
			else {
				echo 'Some of criteria data is missing.';
			}
		}
		else if ($reportType == 'CLIENT_RECEIPT') {
			if (!empty($_GET['client_id']) 
					&& !empty($_GET['receipt_date']) 
					&& !empty($_GET['receipt_value'])
					&& !empty($_GET['provider_no'])) {
						
				$clientID = $_GET['client_id'];
				$receiptDate = $_GET['receipt_date'];
				$receiptValue = $_GET['receipt_value'];
				$providerNo = $_GET['provider_no'];
				
				Utilities::logInfo('Report | criteria_data[client_id]: '.$clientID);
				Utilities::logInfo('Report | criteria_data[receipt_date]: '.$receiptDate);
				Utilities::logInfo('Report | criteria_data[receipt_value]: '.$receiptValue);
				Utilities::logInfo('Report | criteria_data[provider_no]: '.$providerNo);
				
				$htmlReportInfo = $reportFunction->getClientReceipt($clientID, $receiptDate, $receiptValue, $providerNo);
				
				$pdf->show('client-receipt', $htmlReportInfo);
				//echo $htmlReportInfo;
			}
			else {
				echo 'Some of criteria data is missing.';
			}
		}
		else if ($reportType == 'COMMISSION_DAILY_REPORT') {
			if (!empty($_GET['date'])) {
				$date = $_GET['date'];
				Utilities::logInfo('Report | criteria_data[date]: '.$date);
		
				$htmlReportInfo = $reportFunction->getCommissionDailyReport($date);
		
				$pdf->show('commission-daily-report', $htmlReportInfo);
				//echo $htmlReportInfo;
			}
			else {
				echo 'Some of criteria data is missing.';
			}
		}
		else if ($reportType == 'INCOME_DAILY_REPORT') {
			if (!empty($_GET['date'])) {
				$date = $_GET['date'];
				Utilities::logInfo('Report | criteria_data[date]: '.$date);
		
				$htmlReportInfo = $reportFunction->getIncomeDailyReport($date);
		
				$pdf->show('income-daily-report', $htmlReportInfo);
				//echo $htmlReportInfo;
			}
			else {
				echo 'Some of criteria data is missing.';
			}
		}
		else if ($reportType == 'SALE_RECEIPT') {
			if (!empty($_GET['uid'])) {
				$uid = $_GET['uid'];
				Utilities::logInfo('Report | criteria_data[uid]: '.$uid);
		
				$htmlReportInfo = $reportFunction->getSaleReceipt($uid);
		
				$pdf->show('sale-receipt', $htmlReportInfo);
				//echo $htmlReportInfo;
			}
			else {
				echo 'Some of criteria data is missing.';
			}
		}
		else {
			echo 'Report type is not found.';
		}
	}
	else {
		echo 'Report type is empty.';
	}
	
	exit;
?>