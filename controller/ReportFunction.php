<?php
	require_once '../controller/ClientFunction.php';
	
	class ReportFunction
	{
		private $_SEPARATE_LINE = "<br><br><br>";
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
				
	.span-text
	{
		text-decoration: underline;
	}
				
	.table-report
	{
		
	}
		
	.table-report tr.header
	{
		text-align: center;
		font-weight: bold;
	}
		
	.table-report tr.footer
	{
		font-weight: bold;
	}
		
	.table-report td
	{
		width: 120px;
	}
</style>
EOF;
		public function getClientReport($clientID)
		{
			$getCheckboxSymbol = function($val){
				return $val ? '<img src="../image/checked-box.png" width="16" height="16" />' 
						: '<img src="../image/unchecked-box.png" width="16" height="16" />';
				//return $val ? "checked" : "";
			};
			
			$clientFunction = new ClientFunction();
			
			$clientInfo = $clientFunction->getClientInfo($clientID)['result'];
			$reportInfo = $clientFunction->getReports($clientID)['result'];
			
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
			<td width="48%" class="text">{$clientInfo["client_emergency_contact_name"]} - {$clientInfo["client_emergency_contact_no"]}</td>
		</tr>
	</tbody>
</table>
client;
		
			$reportHtmlOutput = <<<report
report;
			$reportItems = array();
				
			foreach ($reportInfo as $item) {
				/*$reportItem = <<<reportItem
				<p>
					<span class="caption">Client Report on:</span> <div>{$item["report_date"]}</div>
					<br>
					<span class="caption">Massage Details:</span> <div>{$item["report_detail"]}</div>
					<br>
					<span class="caption">Recommendations:</span> <span>{$item["report_recommendation"]}</span>
					<br>
					<span class="caption">Remark:</span> <span>{$item["therapist_id"]}</span>
				<p>
reportItem;*/
				$reportItem = <<<reportItem
<table cellspacing="2" cellpadding="1" border="0">
	<tbody>
		<tr>
			<td width="20%" class="caption">Client Report on:</td>
			<td width="20%" class="text">{$item["report_date"]}</td>
		</tr>
		<tr>
			<td width="19%" class="caption">Massage Details:</td>
			<td width="71%" class="text">{$item["report_detail"]}</td>
		</tr>
		<tr>
			<td width="20%" class="caption">Recommendations:</td>
			<td width="70%" class="text">{$item["report_recommandation"]}</td>
		</tr>
		<tr>
			<td width="10%" class="caption">Remark:</td>
			<td width="30%" class="text">{$item["therapist_name"]}</td>
		</tr>
	</tbody>
</table>
reportItem;
				$reportHtmlOutput .= $reportItem.$this->_SEPARATE_LINE;
			}
					
			return $this->_CSS.$clientHtmlOutput.$this->_SEPARATE_LINE.$reportHtmlOutput;
		} // getClientReport
	}
?>










