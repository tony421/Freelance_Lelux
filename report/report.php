<?php
	require_once '../login/page-authentication.php';
	ob_start();
	
	require_once '../controller/PDF.php';
	require_once '../controller/ReportFunction.php';
	require_once '../controller/Utilities.php';
	
	require_once '../excel/PHPExcel.php';
	
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
		
				$htmlReportInfo = $reportFunction->getDailyCommissionReport($date);
		
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
		
				$htmlReportInfo = $reportFunction->getDailyIncomeReport($date);
		
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
		else if ($reportType == 'CLIENT_CONTACTS') {
			setExcelHtmlHeader('client_contacts');
			
			if (empty($_GET['year']) || empty($_GET['month']))
				$excelInfo = $reportFunction->getClientContactsExcel();
			else
				$excelInfo = $reportFunction->getClientContactsExcel($_GET['year'], $_GET['month']);
			
			sendExcelFile($excelInfo);
		}
		else if ($reportType == 'HICAP') {
			if (!empty($_GET['date_start'])
				&& !empty($_GET['date_end'])
				&& !empty($_GET['providers'])
				&& !empty($_GET['hicaps'])) 
			{
			
				$dateStart = $_GET['date_start'];
				$dateEnd = $_GET['date_end'];
				$providers = $_GET['providers'];;
				$hicaps = $_GET['hicaps'];
	
				Utilities::logInfo('Report | criteria_data[date_start]: '.$dateStart);
				Utilities::logInfo('Report | criteria_data[date_end]: '.$dateEnd);
				Utilities::logInfo('Report | criteria_data[providers]: '.$providers);
				Utilities::logInfo('Report | criteria_data[hicaps]: '.$hicaps);
	
				$htmlReportInfo = $reportFunction->getHicapReport($dateStart, $dateEnd, $providers, $hicaps);
	
				$pdf->show('hicap', $htmlReportInfo);
				//echo $htmlReportInfo;
			}
			else {
				echo 'Some of criteria data is missing.';
			}
		}
		else if ($reportType == 'REQUEST_AMOUNT') {
			if (!empty($_GET['date_start'])
					&& !empty($_GET['date_end'])
					&& !empty($_GET['therapists']))
			{
					
				$dateStart = $_GET['date_start'];
				$dateEnd = $_GET['date_end'];
				$therapists = $_GET['therapists'];;
		
				Utilities::logInfo('Report | criteria_data[date_start]: '.$dateStart);
				Utilities::logInfo('Report | criteria_data[date_end]: '.$dateEnd);
				Utilities::logInfo('Report | criteria_data[therapists]: '.$therapists);
		
				$htmlReportInfo = $reportFunction->getRequestAmtReport($dateStart, $dateEnd, $therapists);
		
				$pdf->show('request-amount', $htmlReportInfo);
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
	
	function setExcelHtmlHeader($fileName) 
	{
		//Redirect output to a client's web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=\"{$fileName}.xlsx\"");
		//header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
	}
	
	function sendExcelFile($excelInfo)
	{
		ob_end_clean(); // It is added to solve "the file format/extension is not valid" 
		$objWriter = PHPExcel_IOFactory::createWriter($excelInfo, 'Excel2007');
		$objWriter->save('php://output');
	}
	
	exit;
?>








