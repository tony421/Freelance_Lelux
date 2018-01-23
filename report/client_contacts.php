<?php
	require_once '../login/page-authentication.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Report - Client Contacts</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    
	   	<script type="text/javascript">
	   		var $radAll, $radSome, $ddlYear, $ddlMonth, $btnGetReport;
	   		
	    	$(document).ready(function(){
	    		initPage();
		    });

		    function initPage() {
		    	main_ajax_success_hide_loading();
		    	
				$radAll = $('#radAll');
				$radSome = $('#radSome');
				$ddlYear = $('#ddlYear');
				$ddlMonth = $('#ddlMonth');
				$btnGetReport = $('#btnGetReport');

				$radAll.change(function(){
					turnOffSomeClientOption();
				});

				$radSome.change(function(){
					turnOnSomeClientOption();
				});

				$btnGetReport.click(function(){
					if ($radAll.is(':checked')) { 
						main_open_new_tab('../report/report.php?report_type=CLIENT_CONTACTS');
					} else {
						year = $ddlYear.val();
						month = $ddlMonth.val();
						main_open_new_tab('../report/report.php?report_type=CLIENT_CONTACTS&year=' + year + '&month=' + month);
					}
				});
				
				initYear();
				turnOffSomeClientOption();
				setCurrentMonthOption();
		    }

		    function initYear() {
		    	main_request_ajax('../report/report-option-boundary.php', 'GET_CLIENT_YEAR_OPTION', {}, onInitYearDone);
		    }

		    function onInitYearDone(response) {
		    	if (response.success) {
		    		$.each(response.result['years'], function (i, item){
						option = "<option value='" + item['year'] + "'>" + item['year'] + "</option>";
						
						$ddlYear.append(option);
					});
		    	}
		    }

		    function setCurrentMonthOption() {
				$ddlMonth.val(moment(new Date()).format('MM'));
		    }

		    function turnOnSomeClientOption() {
			    main_enable_control($ddlYear);
			    main_enable_control($ddlMonth);
		    }

		    function turnOffSomeClientOption() {
			    main_disable_control($ddlYear);
			    main_disable_control($ddlMonth);
		    }
	    </script>
	</head>
	
	<body>
		<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'report'; require_once '../master-page/menu.php';?>
    	
		<div id="content">
			<div class="title-container">
				<div class="title-text">Report - Client Contacts</div>
			</div>
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-2 col-sm-offset-1 col-sm-2 control-label">Option</label>
						<div class="col-xs-4 col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radAll" name="option" value="1" checked> All Clients
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-offset-2 col-xs-3 col-sm-offset-3 col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radSome" name="option" value="2"> Some Clients
							</label>
						</div>
						<div class="col-xs-4 col-sm-2">
							<select id="ddlYear" class="form-control"></select>
						</div>
						<div class="col-xs-4 col-sm-2">
							<select id="ddlMonth" class="form-control">
								<option value="01">January</option>
								<option value="02">February</option>
								<option value="03">March</option>
								<option value="04">April</option>
								<option value="05">May</option>
								<option value="06">June</option>
								<option value="07">July</option>
								<option value="08">August</option>
								<option value="09">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnGetReport" class="btn btn-success btn-lg">
								<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
								Get Report
							</button>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- #content -->
	</body>
</html>















