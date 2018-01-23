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
	    
	    <title>Daily Records - Booking</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-timeline.php';?>
	    
	    <script type="text/javascript" src="booking.js?<?php echo time(); ?>"></script>
	    <script type="text/javascript" src="booking-var.js?<?php echo time(); ?>"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
    		<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-1 col-sm-2 control-label">Minutes</label>
						<div class="col-xs-5 col-sm-2">
							<input type="text" id="txtMinutes" class="form-control" maxlength="4">
						</div>
						<label class="col-xs-4 col-sm-1 control-label">Time In</label>
						<div class="col-xs-5 col-sm-2">
							<input type="text" id="txtTimeIn" class="form-control">
						</div>
						<label class="col-xs-4 col-sm-1 control-label">Time Out</label>
						<div class="col-xs-5 col-sm-2">
							<input type="text" id="txtTimeOut" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-1 col-sm-2 control-label">Client</label>
						<div class="col-xs-5 col-sm-1">
							<input type="text" id="txtClient" class="form-control" maxlength="2">
						</div>
						<label class="col-xs-4 col-sm-2 control-label text-nowrap">Single Room</label>
						<div class="col-xs-5 col-sm-1">
							<input type="text" id="txtSingleRoom" class="form-control" maxlength="2">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-4 col-sm-2 control-label text-nowrap">Double Room</label>
						<div class="col-xs-5 col-sm-1">
							<input type="text" id="txtDoubleRoom" class="form-control" maxlength="2">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-1 col-sm-2 control-label">Therapist</label>
						<div id="ddlTherapistContainer" class="col-xs-5 col-sm-9" style="padding: 0;">
							<!-- Therapist dropdown will be added automatically according the number of client -->
							<div class="col-sm-2" style="padding-bottom: 5px;"> 
								<select id="ddlTherapist0" class="form-control"></select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-1 col-sm-2 control-label">Massage Type</label>
						<div id="ddlMassageTypeContainer" class="col-xs-5 col-sm-9" style="padding: 0;">
							<!-- MassageType dropdown will be added automatically according the number of client -->
							<div class="col-sm-2" style="padding-bottom: 5px;"> 
								<select id="ddlMassageType0" class="form-control"></select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnSearch" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								Search
							</button>
						</div>
					</div>
				</form>
			</div>
			
			<div class="container-fluid">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="panel-title">
										<span class="glyphicon glyphicon-book" aria-hidden="true"></span>
										<b>Booking Timeline</b>
									</div>
								</div>
								<div class="panel-body">
									<div id="bookingTimeline"></div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			
			<!-- Popover Prototype -->
			<!--  
			<div class="popover popover-bottom" style="top: 0px; left: 0px; position: absolute; transform: translateX(100px) translateY(100px) translateZ(0px);">
				<h3 class="popover-title">Popover bottom</h3>
				<div class="popover-content">
					<p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
				</div>
			</div>
			-->
			<ul id="popupContextMenu" class="dropdown-menu" style="display: none;" data-record-id>
			    <li><a id="contextMenuShowRecord" style="cursor: pointer;">Show Record</a></li>
			</ul>
		</div>
	</body>
</html>









