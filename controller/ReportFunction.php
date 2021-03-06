<?php
	require_once '../config/shop_config.php';
	require_once '../config/client_config.php';
	require_once '../controller/ClientFunction.php';
	require_once '../controller/MassageFunction.php';
	require_once '../controller/SaleFunction.php';
	require_once '../controller/ReportDataMapper.php';
	
	require_once '../excel/PHPExcel.php';
	
	class ReportFunction
	{
		private $_dataMapper;
		
		private $_SEPARATE_LINE = "<br><br>";
		private $_CSS = <<<EOF
<style>
	.caption
	{
		font-weight: bold;
	}
	
	.text
	{
		border-bottom: 1px solid #000;
		/*text-decoration: underline;*/
	}
				
	.border
	{
		border: 1px solid #000;
	}
</style>
EOF;
		
		public function ReportFunction()
		{
			$this->_dataMapper = new ReportDataMapper();
		}
		
		public function getClientReport($clientID)
		{
			$getCheckboxSymbol = function($val){
				return $val ? '<img src="../image/checked-box.png" width="16" height="16" />' 
						: '<img src="../image/unchecked-box.png" width="16" height="16" />';
				//return $val ? "checked" : "";
			};
			
			$clientInfoHeader = <<<header
			<h1 style="text-align: center;">Client Confidential Information</h1>
header;
			$clientReportHeader = <<<header
			<br><br><br>
			<hr>
			<h2 style="text-align: center;">Client Reports</h2>
header;
			
			$clientFunction = new ClientFunction();
			
			$clientInfo = $clientFunction->getClientInfo($clientID)['result'];
			$reportInfo = $clientFunction->getReports($clientID)['result'];
			
			if (!empty($clientInfo[CLIENT_EMER_CON_NAME]))
				$clientInfo[CLIENT_EMER_CON_NAME] = $clientInfo[CLIENT_EMER_CON_NAME].' - ';
			//echo var_dump($clientInfo['client_conditions'][0], true);
			
			$clientHtmlOutput = <<<client
<table cellspacing="2" cellpadding="1" border="0">
	<tbody>
		<tr>
			<td width="15%" class="caption">Health Fund:</td>
			<td width="45%" class="text">{$clientInfo["health_fund_name"]}</td>
		</tr>
		<tr>
			<td width="18%" class="caption">Membership No:</td>
			<td width="42%" class="text">{$clientInfo["client_membership_no"]}</td>
			<td width="13%" class="caption">Patient ID:</td>
			<td width="22%" class="text">{$clientInfo["client_patient_id"]}</td>
		</tr>
		<tr>
			<td width="8%" class="caption">Name:</td>
			<td width="52%" class="text">{$clientInfo["client_first_name"]} {$clientInfo["client_last_name"]}</td>
			<td width="10%" class="caption">Gender:</td>
			<td width="25%" class="text">{$clientInfo["client_gender_desc"]}</td>
		</tr>
		<tr>
			<td width="10%" class="caption">Address:</td>
			<td width="50%" class="text">{$clientInfo["client_address"]}</td>
			<td width="11%" class="caption">Postcode:</td>
			<td width="24%" class="text">{$clientInfo["client_postcode"]}</td>
		</tr>
		<tr>
			<td width="8%" class="caption">Email:</td>
			<td width="52%" class="text">{$clientInfo["client_email"]}</td>
		</tr>
		<tr>
			<td width="13%" class="caption">Contact No:</td>
			<td width="47%" class="text">{$clientInfo["client_contact_no"]}</td>
		</tr>
		<tr>
			<td width="15%" class="caption">Date of Birth:</td>
			<td width="45%" class="text">{$clientInfo["client_birthday"]}</td>
		</tr>
		<tr>
			<td width="13%" class="caption">Occupation:</td>
			<td width="47%" class="text">{$clientInfo["client_occupation"]}</td>
		</tr>
		<tr>
			<td width="13%" class="caption">Finding us:</td>
			<td width="80%">
				{$getCheckboxSymbol($clientInfo["client_findings"][0]["client_finding_checked"])} {$clientInfo["client_findings"][0]["finding_type_name"]}
				{$getCheckboxSymbol($clientInfo["client_findings"][1]["client_finding_checked"])} {$clientInfo["client_findings"][1]["finding_type_name"]}
				{$getCheckboxSymbol($clientInfo["client_findings"][2]["client_finding_checked"])} {$clientInfo["client_findings"][2]["finding_type_name"]}
				{$getCheckboxSymbol($clientInfo["client_findings"][3]["client_finding_checked"])} {$clientInfo["client_findings"][3]["finding_type_name"]}
				{$getCheckboxSymbol($clientInfo["client_findings"][4]["client_finding_checked"])} {$clientInfo["client_findings"][4]["finding_type_name"]}
				{$getCheckboxSymbol($clientInfo["client_findings"][5]["client_finding_checked"])} {$clientInfo["client_findings"][5]["finding_type_name"]}
			</td>
		</tr>
		<tr>
			<td width="13%"></td>
			<td width="32%">
				{$getCheckboxSymbol($clientInfo["client_findings"][6]["client_finding_checked"])} {$clientInfo["client_findings"][6]["finding_type_name"]}
				{$getCheckboxSymbol($clientInfo["client_findings"][7]["client_finding_checked"])} {$clientInfo["client_findings"][7]["finding_type_name"]}
			</td>
			<td width="20%" class="text">
				{$clientInfo["client_findings"][7]["client_finding_remark"]}
			</td>
		</tr>
		<tr>
			<td width="18%" class="caption">Sports/Activities:</td>
			<td width="52%" class="text">{$clientInfo["client_sports"]}</td>
		</tr>
		<tr>
			<td width="20%" class="caption">Conditions Apply:</td>
			<td width="10%">{$getCheckboxSymbol($clientInfo["client_conditions"][0]["client_condition_checked"])} {$clientInfo["client_conditions"][0]["condition_type_name"]}</td>
			<td width="30%" class="text">{$clientInfo["client_conditions"][0]["client_condition_remark"]}</td>
			<td width="11%">{$getCheckboxSymbol($clientInfo["client_conditions"][1]["client_condition_checked"])} {$clientInfo["client_conditions"][1]["condition_type_name"]}</td>
			<td width="29%" class="text">{$clientInfo["client_conditions"][1]["client_condition_remark"]}</td>
		</tr>
		<tr>
			<td width="20%"></td>
			<td width="12%">{$getCheckboxSymbol($clientInfo["client_conditions"][2]["client_condition_checked"])} {$clientInfo["client_conditions"][2]["condition_type_name"]}</td>
			<td width="28%" class="text">{$clientInfo["client_conditions"][2]["client_condition_remark"]}</td>
			<td width="13%">{$getCheckboxSymbol($clientInfo["client_conditions"][3]["client_condition_checked"])} {$clientInfo["client_conditions"][3]["condition_type_name"]}</td>
			<td width="27%" class="text">{$clientInfo["client_conditions"][3]["client_condition_remark"]}</td>
		</tr>
		<tr>
			<td width="20%"></td>
			<td width="20%">{$getCheckboxSymbol($clientInfo["client_conditions"][4]["client_condition_checked"])} {$clientInfo["client_conditions"][4]["condition_type_name"]}</td>
			<td width="20%" class="text">{$clientInfo["client_conditions"][4]["client_condition_remark"]}</td>
			<td width="17%">{$getCheckboxSymbol($clientInfo["client_conditions"][5]["client_condition_checked"])} {$clientInfo["client_conditions"][5]["condition_type_name"]}</td>
			<td width="23%" class="text">{$clientInfo["client_conditions"][5]["client_condition_remark"]}</td>
		</tr>
		<tr>
			<td width="20%"></td>
			<td width="29%">{$getCheckboxSymbol($clientInfo["client_conditions"][6]["client_condition_checked"])} {$clientInfo["client_conditions"][6]["condition_type_name"]}</td>
			<td width="11%" class="text">{$clientInfo["client_conditions"][6]["client_condition_remark"]}</td>
			<td width="21%">{$getCheckboxSymbol($clientInfo["client_conditions"][7]["client_condition_checked"])} {$clientInfo["client_conditions"][7]["condition_type_name"]}</td>
			<td width="19%" class="text">{$clientInfo["client_conditions"][7]["client_condition_remark"]}</td>
		</tr>
		<tr>
			<td width="20%"></td>
			<td width="29%">{$getCheckboxSymbol($clientInfo["client_conditions"][8]["client_condition_checked"])} {$clientInfo["client_conditions"][8]["condition_type_name"]}</td>
			<td width="11%" class="text">{$clientInfo["client_conditions"][8]["client_condition_remark"]}</td>
			<td width="34%">{$getCheckboxSymbol($clientInfo["client_conditions"][9]["client_condition_checked"])} {$clientInfo["client_conditions"][9]["condition_type_name"]}</td>
			<td width="6%" class="text">{$clientInfo["client_conditions"][9]["client_condition_remark"]}</td>
		</tr>
		<tr>
			<td width="20%"></td>
			<td width="28%">{$getCheckboxSymbol($clientInfo["client_conditions"][10]["client_condition_checked"])} {$clientInfo["client_conditions"][10]["condition_type_name"]}</td>
			<td width="12%" class="text">{$clientInfo["client_conditions"][10]["client_condition_remark"]}</td>
			<td width="14%">{$getCheckboxSymbol($clientInfo["client_conditions"][11]["client_condition_checked"])} {$clientInfo["client_conditions"][11]["condition_type_name"]}</td>
			<td width="26%" class="text">{$clientInfo["client_conditions"][11]["client_condition_remark"]}</td>
		</tr>
		<tr>
			<td width="20%" class="caption">Other Conditions:</td>
			<td width="50%" class="text">{$clientInfo["client_other_conditions"]}</td>
		</tr>
		<tr>
			<td width="22%" class="caption">Emergency Contact:</td>
			<td width="48%" class="text">{$clientInfo["client_emergency_contact_name"]}{$clientInfo["client_emergency_contact_no"]}</td>
		</tr>
	</tbody>
</table>
client;
		
			$reportHtmlOutput = <<<report
report;
			
			if (count($reportInfo) > 0) {
				$reportHtmlOutput .= $clientReportHeader;
				
				foreach ($reportInfo as $item) {
					$reportItem = <<<reportItem
<table cellspacing="2" cellpadding="1" border="0">
	<tbody>
		<tr>
			<td width="20%" class="caption">Client Report on:</td>
			<td width="20%" class="text">{$item["report_date"]}</td>
		</tr>
		<tr>
			<td width="20%" class="caption">Therapist:</td>
			<td width="20%" class="text">{$item["provider_name"]}</td>
		</tr>
		<tr>
			<td width="19%" class="caption">Massage Details:</td>
			<td width="71%" class="text">{$item["report_detail"]}</td>
		</tr>
		<tr>
			<td width="20%" class="caption">Recommendations:</td>
			<td width="70%" class="text">{$item["report_recommendation"]}</td>
		</tr>
		<!--<tr>
			<td width="10%" class="caption">Remark:</td>
			<td width="30%" class="text">{$item["therapist_name"]}</td>
		</tr>-->
	</tbody>
</table>
reportItem;
					$reportHtmlOutput .= $reportItem.$this->_SEPARATE_LINE;
				}	
			}
					
			return $this->_CSS.$clientInfoHeader.$clientHtmlOutput.$reportHtmlOutput;
		} // getClientReport
	
		public function getClientReceipt($clientID, $receiptDate, $receiptValue, $providerNo)
		{
			$shopDetails = SHOP_PROVIDER_DETAILS;
			$shopConNo = SHOP_CONTACT_NO;
			$shopAddr = SHOP_ADDRESS;
			$shopService = SHOP_SERVICE;
			
			$clientFunction = new ClientFunction();
			$clientInfo = $clientFunction->getClientInfo($clientID)['result'];
			
			$receiptSeparator = "<br><br>------------------------------------------------------------------------------------------------------------------------------<br><br>";
			
			$receiptHeader = <<<header
			<tr>
				<th colspan="2">
					<h1 style="text-align: center;">Remedial Massage Receipt, Lelux Thai Massage</h1>
				</th>
			</tr>
header;
			$receiptHeaderCopy = <<<header
			<tr>
				<th colspan="2">
					<h1 style="text-align: center;">Remedial Massage Receipt, Lelux Thai Massage (Copy)</h1>
				</th>
			</tr>
header;
			
			/*<tr>
				<td class="caption" width="30%">Provided by</td>
				<td width="70%">{$shopDetails}</td>
			</tr>*/
			$receiptBody = <<<body
			<tr>
				<td class="caption" width="30%">Service Provided</td>
				<td width="70%">{$shopService}</td>
			</tr>
			<tr>
				<td class="caption">Remedial Provider No</td>
				<td>{$providerNo}</td>
			</tr>
			<tr>
				<td class="caption">Date</td>
				<td>{$receiptDate}</td>
			</tr>
			<tr>
				<td class="caption">Phone</td>
				<td>{$shopConNo}</td>
			</tr>
			<tr>
				<td class="caption">Address</td>
				<td>{$shopAddr}</td>
			</tr>
			<tr>
				<td class="caption">Customer Name</td>
				<td>{$clientInfo[CLIENT_FNAME]} {$clientInfo[CLIENT_LNAME]}</td>
			</tr>
			<tr>
				<td class="caption">Customer Membership No</td>
				<td>{$clientInfo[CLIENT_MEM_NO]}</td>
			</tr>
			<tr>
				<td class="caption">Health Fund</td>
				<td>{$clientInfo[HF_NAME]}</td>
			</tr>
			<tr>
				<td class="caption">Value</td>
				<td>{$receiptValue} Inc Gst.</td>
			</tr>
			<tr>
				<td class="caption">Signed:</td>
				<td><br><br></td>
			</tr>
body;
				
			$receiptTable = <<<table
			<table cellspacing="0" cellpadding="5" border="1">
				{$receiptHeader}
				{$receiptBody}
			</table>
table;
				$receiptTableCopy = <<<table
			<table cellspacing="0" cellpadding="5" border="1">
				{$receiptHeaderCopy}
				{$receiptBody}
			</table>
table;
			
			return $this->_CSS.$receiptTable.$receiptSeparator.$receiptTableCopy;
		} // getClientReceipt
		
		public function getDailyCommissionReport($date)
		{
			//$massagFunction = new MassageFunction();
			//$reportInfo = $massagFunction->getCommissionDailyReport($date);
			$reportInfo = $this->_dataMapper->getDailyCommission($date);
			
			$fullDate = Utilities::convertDateForFullDisplay($date);
			
			$reportHeader = <<<header
			<h1 style="text-align: center;">Commission Report</h1>
			<h2 style="text-align: center;">on {$fullDate}</h2>
header;
			
			$columnHeaders = <<<colHeader
			<thead>
				<tr style="text-align: center; font-weight: bold;">
					<th>Staff</th>
					<th>Standard Commission</th>
					<th>Extra Commission</th>
					<th>Total</th>
				</tr>
			</thead>
colHeader;
			
			
			
			$summaryRow = $this->getSumDailyCommissionReport($reportInfo);
			array_push($reportInfo, $summaryRow);
			
			Utilities::logDebug(var_export($summaryRow));
			
			$reportRows = "";
			foreach ($reportInfo as $row) {
				$commission = $row['massage_record_commission'] != '' ? '$'.$row['massage_record_commission'] : '';
				$reward = $row['massage_record_request_reward'] != '' ? '$'.$row['massage_record_request_reward'] : '';
				
				// check guaranteed commission
				$total = (float)$row['massage_record_commission_total'];
				$guarantee = (float)$row['therapist_guarantee'];
				
				$com_total = '$'.number_format($total, 2);
				// ***Enable this when staff shift control is built
				if ($guarantee > $total)
					$com_total = '(*) $'.number_format($guarantee, 2);
				else
					$com_total = '$'.number_format($total, 2);
				
				// calculate massage duration for each staff
				if ($row['massage_record_minutes'] == '') {
					$duration = '';
				} else {
					$hours = (int)((float)$row['massage_record_minutes'] / 60);
					$minutes = (float)$row['massage_record_minutes'] % 60;
					
					if ($hours > 0)
						$minutes > 0 ? $duration = $hours.'hr '.$minutes.'m' : $duration = $hours.'hr';
					else
						$duration = $minutes.'m';
					
					$duration = '('.$duration.')';
				}
				//$duration = $row['massage_record_minutes'];
				
				$reportRows .= <<<row
				<tr><td><b>{$row['therapist_name']} {$duration}</b></td><td style="text-align: right;">{$commission}</td><td style="text-align: right;">$reward</td><td style="text-align: right;"><b>{$com_total}</b></td></tr>
row;
			}
				
			$reportTable = <<<table
			<table cellspacing="0" cellpadding="5" border="1">
				{$columnHeaders}
				<tbody>
					{$reportRows}
				</tbody>
				<tfoot>(*) Guarantee Commission</tfoot>
			</table>
table;
				
			return $reportHeader.$reportTable;
		} // getCommissionDailyReport
		
		private function getSumDailyCommissionReport($rows) 
		{
			$total = 0.0;
			foreach ($rows as $row) {
				$comTotal = (float)$row['massage_record_commission_total'];
				$guarantee = (float)$row['therapist_guarantee'];
				
				if ($guarantee > $comTotal)
					$comTotal = $guarantee;
					
				$total += $comTotal;
			}
			
			$summaryRow = array();
			$summaryRow['seq'] = 99;
			$summaryRow['therapist_id'] = '';
			$summaryRow['therapist_name'] = 'Total';
			$summaryRow['therapist_guarantee'] = '';
			$summaryRow['massage_record_minutes'] = '';
			$summaryRow['massage_record_commission'] = '';
			$summaryRow['massage_record_request_reward'] = '';
			$summaryRow['massage_record_commission_total'] = '';
			$summaryRow['massage_record_commission_total'] = $total;
			
			return $summaryRow;
		}
		
		public function getDailyIncomeReport($date)
		{
			//$massagFunction = new MassageFunction();
			//$reportInfo = $massagFunction->getIncomeDailyReport($date);
			$reportInfo = $this->_dataMapper->getDailyIncome($date);
			$relatedInfo = $this->_dataMapper->getDailyRelatedIncomeInfo($date);
			
			$fullDate = Utilities::convertDateForFullDisplay($date);
			
			$reportHeader = <<<header
			<h1 style="text-align: center;">Income Report</h1>
			<h2 style="text-align: center;">on {$fullDate}</h2>
header;
			
			$columnHeaders = <<<colHeader
			<thead>
				<tr style="text-align: center; font-weight: bold;">
					<th></th>
					<th class="border">Paid By</th>
					<th class="border">Amount</th>
					<th></th>
				</tr>
			</thead>
colHeader;
			
			$reportRows = "";
			foreach ($reportInfo as $row) {
				/*if ($row['paid_by'] != 'Free Stamp') {
					$amount = '$'.$row['amount'];
				}
				else {
					$amount = $row['amount'] > 1 ? (int)$row['amount'].' minutes' : (int)$row['amount'].' minute';
				}*/
			
				$reportRows .= <<<row
				<tr><td></td><td class="border"><b>{$row['paid_by']}</b></td><td class="border" style="text-align: right;">\${$row['amount']}</td><td></td></tr>
row;
			}
				
			$reportTable = <<<table
			<table cellspacing="0" cellpadding="5" border="0">
				{$columnHeaders}
				<tbody>
					{$reportRows}
				</tbody>
			</table>
table;
				
			$relatedInfoRows = "";
			foreach ($relatedInfo as $row) {
				/*if ($row['info'] != 'Used Free Stamp') {
					$amount = '$'.$row['amount'];
				}
				else {
					$amount = $row['amount'] > 1 ? (int)$row['amount'].' minutes' : (int)$row['amount'].' minute';
				}*/
				
				$relatedInfoRows .= <<<row
				<tr><td></td><td class="border"><b>{$row['info']}</b></td><td class="border" style="text-align: right;">{$row['amount']} minutes</td><td></td></tr>
row;
			}
			
			$raltedInfoTable = <<<table
			<table cellspacing="0" cellpadding="5" border="0">
				<thead>
					<tr style="text-align: center; font-weight: bold;">
						<th></th>
						<th class="border">Related Info.</th>
						<th class="border">Amount</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{$relatedInfoRows}
				</tbody>
			</table>
table;
			
			
			// Do not show free stamp and voucher amount. 
			return $this->_CSS.$reportHeader.$reportTable;
			//return $this->_CSS.$reportHeader.$reportTable.$this->_SEPARATE_LINE.$this->_SEPARATE_LINE.$raltedInfoTable;
		} // getIncomeDailyReport
		
		public function getSaleReceipt($uid)
		{
			$saleFunction = new SaleFunction();
			$sale = $saleFunction->getSaleReceipt($uid);
			
			$fullDate = Utilities::convertDateForFullDisplay($sale['sale_date']);
			
			$reportHeader = <<<header
			<h1 style="text-align: center;">Receipt No. {$sale['sale_id']}</h1>
			<h3 style="text-align: center;">on {$fullDate} at {$sale['sale_time']}</h3>
header;
			$columnHeaders = <<<colHeader
			<thead>
				<tr style="text-align: center; font-weight: bold;">
					<th></th>
					<th class="text">Description</th>
					<th class="text" style="text-align: right;">$$</th>
					<th></th>
				</tr>
			</thead>
colHeader;
			$reportRows = "";
			foreach ($sale['sale_items'] as $row) {
				if ($row['sale_item_amount'] > 1)
					$row['product_name'] = $row['product_name'].'<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$row['sale_item_amount'].' @ $'.$row['sale_item_price'].' each'; 
				
				$reportRows .= <<<row
				<tr><td></td><td class="text">{$row['product_name']}</td><td class="text" style="text-align: right;">\${$row['sale_item_total']}</td><td></td></tr>
row;
			}
				
			// add receipt TOTAL
			$reportRows .= <<<row
			<tr><td></td><td style="text-align: right"><b>Total</b></td><td style="text-align: right;">\${$sale['sale_total']}</td><td></td></tr>
row;
			$reportTable = <<<table
			<table cellspacing="0" cellpadding="5" border="0">
				{$columnHeaders}
				<tbody>
					{$reportRows}
				</tbody>
			</table>
table;
			
			return $this->_CSS.$reportHeader.$reportTable;
		} // getSaleReceipt
		
		public function getHicapReport($dateStart, $dateEnd, $providers, $hicaps)
		{
			$reportInfo = $this->_dataMapper->getHicapReport($dateStart, $dateEnd, $providers, $hicaps);
				
			$fullDateStart = Utilities::convertDateFullMonthDisplay($dateStart);
			$fullDateEnd = Utilities::convertDateFullMonthDisplay($dateEnd);
				
			$reportHeader = <<<header
			<h1 style="text-align: center;">HICAP Report</h1>
			<h2 style="text-align: center;">from <u>{$fullDateStart}</u> to <u>{$fullDateEnd}</u></h2>
header;
				
			$columnHeaders = <<<colHeader
			<thead>
				<tr style="text-align: center; font-weight: bold;">
					<th>Date</th>
					<th>Provider</th>
					<th>HICAP</th>
					<th>Mem No <br>(Patient ID)</th>
					<th>Client Name</th>
					<th>Staff</th>
				</tr>
			</thead>
colHeader;
			
			$reportRows = "";
			$prevDate = ""; $prevProvider = ""; $prevHicap = "";
			
			foreach ($reportInfo as $row) {
				$date = $row['report_date'];
				$provider = $row['provider_name'];
				$hicap = $row['health_fund_name'];
				
				if ($date == $prevDate) {
					$date = "";
					if ($provider == $prevProvider) {
						$provider = "";
						if ($hicap == $prevHicap) {
							//$hicap = "";
						}
					} else {
						$providerClaimAmt = $this->getHicapProviderClaimAmt($reportInfo, $row['report_date'], $provider);
						$provider = $provider.'<br>('.$providerClaimAmt.' claims)';
					}
				} else {
					$totalClaimAmt = $this->getHicapTotalClaimAmt($reportInfo, $date);
					$date = Utilities::convertDateFullMonthDisplay($date).'<br>('.$totalClaimAmt.' claims)';
					
					// Due to new provider may occur while starting new date, gotta check this condition again
					if ($row['report_date'] == $prevDate && $provider == $prevProvider) {
						$provider = "";
					} else {
						$providerClaimAmt = $this->getHicapProviderClaimAmt($reportInfo, $row['report_date'], $provider);
						$provider = $provider.'<br>('.$providerClaimAmt.' claims)';
					} 
				}
				
				$reportRows .= <<<row
				<tr>
					<td><b>{$date}</b></td>
					<td><b>{$provider}</b></td>
					<td>{$hicap}</td>
					<td>{$row['client_membership_no']} ({$row['client_patient_id']})</td>
					<td>{$row['client_name']}</td>
					<td>{$row['therapist_name']}</td>
				</tr>
row;
				
				$prevDate = $row['report_date'];
				$prevProvider = $row['provider_name'];
				$prevHicap = $row['health_fund_name'];
			}
			
			$reportTable = <<<table
			<table cellspacing="0" cellpadding="5" border="1">
				{$columnHeaders}
				<tbody>
					{$reportRows}
				</tbody>
			</table>
table;

			return $reportHeader.$reportTable;
		} // getHicapReport()
		
		private function getHicapTotalClaimAmt($hicapInfo, $date)
		{
			$count = 0;
			
			foreach ($hicapInfo as $i) {
				if ($i['report_date'] == $date)
					$count++;
			}
			
			return $count;
		}
		
		private function getHicapProviderClaimAmt($hicapInfo, $date, $provider)
		{
			$count = 0;
				
			foreach ($hicapInfo as $i) {
				if ($i['report_date'] == $date) {
					if ($i['provider_name'] == $provider) {
						$count++;
					}
				}
			}
				
			return $count;
		}
		
		public function getRequestAmtReport($dateStart, $dateEnd, $therapists)
		{
			$reportInfo = $this->_dataMapper->getRequestAmtReport($dateStart, $dateEnd, $therapists);
			
			$fullDateStart = Utilities::convertDateFullMonthDisplay($dateStart);
			$fullDateEnd = Utilities::convertDateFullMonthDisplay($dateEnd);
			
			$reportHeader = <<<header
			<h1 style="text-align: center;">Request Amount Report</h1>
			<h2 style="text-align: center;">from <u>{$fullDateStart}</u> to <u>{$fullDateEnd}</u></h2>
header;
			
			$columnHeaders = <<<colHeader
			<thead>
				<tr style="text-align: center; font-weight: bold;">
					<th></th>
					<th class="border">Name</th>
					<th class="border">Date</th>
					<th class="border">Request Amount</th>
					<th></th>
				</tr>
			</thead>
colHeader;
			
			$reportRows = "";
			$prevName = "";
				
			foreach ($reportInfo as $row) {
				$name = $row['therapist_name'];
			
				if ($name == $prevName) {
					$name = "";
				}
			
				$reportRows .= <<<row
				<tr>
					<td></td>
					<td class="text border"><b>{$name}</b></td>
					<td class="text border">{$row['date']}</td>
					<td class="text border">{$row['request_amt']}</td>
					<td></td>
				</tr>
row;
			
				$prevName = $row['therapist_name'];
			}
			
			$reportTable = <<<table
			<table cellspacing="0" cellpadding="5" border="0">
				{$columnHeaders}
				<tbody>
					{$reportRows}
				</tbody>
			</table>
table;
			
			return $this->_CSS.$reportHeader.$reportTable;
		} // getRequestAmtReport()
		
		public function getDailyIncomeSummary($date)
		{
			$result['shop_income'] = $this->_dataMapper->getDailyIncomeSummary($date)[0]['amount']; 
			
			return Utilities::getResponseResult(true, '', $result); 
		}
		
		public function getClientYearOption()
		{
			$result['years'] = $this->_dataMapper->getClientYearOption();
				
			return Utilities::getResponseResult(true, '', $result);
		}
		
		private function initExcelExporter($subject)
		{
			$excel = new PHPExcel();
			$excel->getProperties()->setCreator("Lelux Thai Massage")
							 ->setTitle("Lelux Thai Massage - Report")
							 ->setSubject($subject);
			return $excel;
		}
		
		public function getClientContactsExcel($year = null, $month = null)
		{
			$excel = $this->initExcelExporter('Client Contacts');
			$clients = $this->_dataMapper->getClientContacts($year, $month);
			
			$headerStyles = array(
					'font' => array(
							'bold' => true,
					),
					'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => 'FFC000')
					)
			);
			$styles = array(
					'borders' => array(
							'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
					)
			);
			$sheetRow = 1;
			
			// Add report details
			if ($year != null && $month != null) {
				$monthName = date('F', strtotime("1999-$month-01"));
				$excel->setActiveSheetIndex(0)->setCellValue("A{$sheetRow}", count($clients)." clients found in {$monthName}, {$year}");
				$excel->getActiveSheet()->getStyle("A$sheetRow")->applyFromArray($headerStyles);
				$sheetRow++;
			}
			
			// Add headers
			$excel->setActiveSheetIndex(0)
            		->setCellValue("A$sheetRow", 'Client Name')
            		->setCellValue("B$sheetRow", 'Contact No.')
            		->setCellValue("C$sheetRow", 'Email');
			// Set headers style
			$excel->getActiveSheet()->getStyle("A$sheetRow:C$sheetRow")->applyFromArray($headerStyles);
            //$excel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            	
            Utilities::logDebug("Client Amount : ".count($clients));
            
            // Add contents
            $sheetRow++;
            for ($i = 0; $i < count($clients); $i++, $sheetRow++) {
            	$excel->setActiveSheetIndex(0)
            			->setCellValue("A{$sheetRow}", $clients[$i]['client_name'])
            			->setCellValue("B{$sheetRow}", $clients[$i]['client_contact_no'])
            			->setCellValue("C{$sheetRow}", $clients[$i]['client_email']);
            }
            // Set style to all cells
            $sheetRow--;
            $excel->getActiveSheet()->getStyle("A2:C{$sheetRow}")->applyFromArray($styles);
            
            // Set worksheet name
            $excel->getActiveSheet()->setTitle('Client Contacts');
            // Set auto width to columns
            $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            
            return $excel;
		}
	}
?>










