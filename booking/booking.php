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
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="../css/main-id.css"/>
	    <link rel="stylesheet" href="../css/main-class.css"/>
	    <link rel="stylesheet" href="../css/messagebox.css"/>
	    <link rel="stylesheet" href="../css/loadingpanel.css"/>
	    <link rel="stylesheet" href="../css/jquery.dataTables.min.css"/>
	    <link rel="stylesheet" href="../css/jquery.bootstrap-touchspin.css"/>
	    <link rel="stylesheet" href="../vis-4.20.0/dist/vis-timeline-graph2d.min.css"/>
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/jquery.bootstrap-touchspin.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="../vis-4.20.0/dist/vis-timeline-graph2d.min.js"></script>
	    <script type="text/javascript" src="booking.js"></script>
	    <script type="text/javascript" src="booking-var.js"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
    		<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Minutes</label>
						<div class="col-sm-2">
							<input type="text" id="txtMinutes" class="form-control" maxlength="4">
						</div>
						<label class="col-sm-1 control-label">Time In</label>
						<div class="col-sm-2">
							<input type="text" id="txtTimeIn" class="form-control">
						</div>
						<label class="col-sm-1 control-label">Time Out</label>
						<div class="col-sm-2">
							<input type="text" id="txtTimeOut" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Client</label>
						<div class="col-sm-1">
							<input type="text" id="txtClient" class="form-control" maxlength="2">
						</div>
						<label class="col-sm-2 control-label text-nowrap">Single Room</label>
						<div class="col-sm-1">
							<input type="text" id="txtSingleRoom" class="form-control" maxlength="2">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-4 col-sm-2 control-label text-nowrap">Double Room</label>
						<div class="col-sm-1">
							<input type="text" id="txtDoubleRoom" class="form-control" maxlength="2">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Therapist</label>
						<div id="ddlTherapistContainer" class="col-sm-9" style="padding: 0;">
							<!-- Therapist dropdown will be added automatically according the number of client -->
							<div class="col-sm-2" style="padding-bottom: 5px;"> 
								<select id="ddlTherapist" class="form-control"></select>
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
		</div>
	</body>
</html>









