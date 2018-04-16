<?php
	require_once '../login/page-authentication.php';
	
	Authentication::permissionCheck(basename($_SERVER['PHP_SELF']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Report - Request Amount</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-select.php';?>
	    
	   	<script type="text/javascript">
	   		var $dateStart, $dateEnd;
	   		var $ddlTherapist, $btnGetReport;
	   		
	    	$(document).ready(function(){
	    		initPage();
		    });

		    function initPage() {
		    	main_ajax_success_hide_loading();

		    	$dateStart = $('#dateStartInput');
		    	$dateEnd = $('#dateEndInput');
		    	$ddlTherapist = $('#ddlTherapist');
				$btnGetReport = $('#btnGetReport');

				$btnGetReport.click(function(){
					//main_open_new_tab('../report/report.php?report_type=HICAP');
					dateStart = getDatepickerValue($dateStart);
					dateEnd = getDatepickerValue($dateEnd);
					therapists = getSelectpickerValues($ddlTherapist);

					if (therapists != null) {
						reportConditions = 'date_start=' + dateStart + '&date_end=' + dateEnd + '&therapists=' + therapists;
						main_open_new_tab('../report/report.php?report_type=REQUEST_AMOUNT&' + reportConditions);
					} else {
						main_alert_message('Please select at least 1 therapist!', function (){});
					}
				});

				initDatePickers();
				initTherapist();
		    }

		    function initDatePickers() {
			    initDatepickerInput($dateStart, DATE_PICKER_SHORT_FORMAT);
			    initDatepickerInput($dateEnd, DATE_PICKER_SHORT_FORMAT);

			    setDatepickerInputValue($dateStart, new Date());
			    setDatepickerInputValue($dateEnd, new Date());

			    $($dateStart).datepicker().on('changeDate', function(e){
				    resetDateEnd(getDatepickerValue(this), getDatepickerValue($dateEnd));
				});

			    $($dateEnd).datepicker().on('changeDate', function(e){
				    resetDateStart(getDatepickerValue($dateStart), getDatepickerValue(this));
				});
		    }

		    function resetDateEnd(dateStart, dateEnd) {
			    dateStart = new Date(dateStart);
			    dateEnd = new Date(dateEnd);

			    if (dateStart > dateEnd) {
				    setDatepickerInputValue($dateEnd, dateStart);
			    }
		    }

		    function resetDateStart(dateStart, dateEnd) {
			    dateStart = new Date(dateStart);
			    dateEnd = new Date(dateEnd);

			    if (dateEnd < dateStart) {
				    setDatepickerInputValue($dateStart, dateEnd);
			    }
		    }  

		    function initTherapist() {
		    	main_request_ajax('../therapist/therapist-boundary.php', 'GET_THERAPIST', {}, function(response){
		    		if (response.success) {
			    		$.each(response.result, function (i, item){
							option = "<option value='" + item['therapist_id'] + "'>" + item['therapist_name'] + "</option>";
							
							$ddlTherapist.append(option);
						});

						initSelectpicker($ddlTherapist, true);
			    	}
			    });
		    }
	    </script>
	</head>
	
	<body>
		<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'report'; require_once '../master-page/menu.php';?>
    	
		<div id="content">
			<div class="title-container">
				<div class="title-text">Report - Request Amount</div>
			</div>
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-2 col-sm-offset-1 col-sm-2 control-label">Date</label>
						<div class="col-xs-10 col-sm-3">
							<div id="dateStartInput" class="input-group date">
							    <input type="text" id="txtDateStart" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
						<div class="col-xs-2 col-sm-1 text-center">
							<label class="control-label">To</label>
						</div>
						<div class="col-xs-10 col-sm-3">
							<div id="dateEndInput" class="input-group date">
							    <input type="text" id="txtDateEnd" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-2 col-sm-offset-1 col-sm-2 control-label">Therapist</label>
						<div class="col-xs-9 col-sm-3">
							<select id="ddlTherapist" class="form-control" multiple></select>
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















