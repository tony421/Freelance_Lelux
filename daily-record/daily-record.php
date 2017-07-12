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
	    
	    <title>Daily Record</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	    <link rel="stylesheet" href="../css/messagebox.css">
	    <link rel="stylesheet" href="../css/loadingpanel.css">
	    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="daily-record.js"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'daily-record'; require_once '../master-page/menu.php';?>
    	
    	<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Daily Records ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="option-container">
						<button type="button" id="btnCommissionReport" class="btn btn-success btn-lg">
							<span class="glyphicon glyphicon-print"></span>
							Commission Report
						</button>
						<button type="button" id="btnIncomeReport" class="btn btn-success btn-lg">
							<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
							Income Report
						</button>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<div id="dateInput" class="input-group date">
							    <input type="text" id="txtDate" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="panel with-nav-tabs panel-default">
				<div class="container">
	                <div class="panel-heading" style="padding-bottom: 0;">
                        <ul class="nav nav-tabs">
                        	<li><a name="frameTherapist" class="tab-title" href="#tab4" data-toggle="tab">Therapists</a></li>
                        	<li><a name="frameQueueing" class="tab-title" href="#tab5" data-toggle="tab">Queueing</a></li>
                        	<li class="active"><a name="frameBooking" class="tab-title" href="#tab6" data-toggle="tab">Booking</a></li>
                            <li><a name="frameMassage" class="tab-title" href="#tab1" data-toggle="tab">Massages</a></li>
                            <li><a name="frameSale" class="tab-title" href="#tab2" data-toggle="tab">Sales</a></li>
                            <li><a name="frameReception" class="tab-title" href="#tab3" data-toggle="tab">Receptions</a></li>
                            <!--<li class="dropdown">
                                <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#tab4default" data-toggle="tab">Default 4</a></li>
                                    <li><a href="#tab5default" data-toggle="tab">Default 5</a></li>
                                </ul>
                            </li>-->
                        </ul>
	                </div>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <div class="tab-content">
                    	<div class="tab-pane fade" id="tab4">
                        	<iframe name="frameTherapist" src="../therapist/shift.php" 
                        		frameborder="0" width="100%" height="525"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab5">
                        	<iframe name="frameQueueing" src="../queueing/queueing.php" 
                        		frameborder="0" width="100%" height="720""></iframe>
                        </div>
                        <div class="tab-pane fade in active" id="tab6">
                        	<iframe name="frameBooking" src="../booking/booking.php" 
                        		frameborder="0" width="100%" height="800""></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab1">
                        	<iframe name="frameMassage" src="../massage/massage-record.php" 
                        		frameborder="0" width="100%" height="826""></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                        	<iframe name="frameSale" src="../sale/sale.php" 
                        		frameborder="0" width="100%" height="987"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab3">
                        	<iframe name="frameReception" src="../reception/reception.php" 
                        		frameborder="0" width="100%" height="731"></iframe>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div id="footer"></div>
		
		<!-- Modal : Booking Details -->
		<div class="modal fade" id="modalBookingDetails" tabindex="-1" role="dialog" aria-labelledby="modalBookingDetails">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="bookingDetails">Booking Details</h4>
						<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
					</div>
					<div class="modal-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Time</label>
								<div class="col-md-7">
									<span id="lblBookingTime" class="form-control" style="font-size: 1.2em"><span class="text-mark">60</span> minutes from <span class="text-mark">14:00</span> to <span class="text-mark">15:15</span></span>
									<!-- <input type="text" id="txtBookingTime" class="form-control"> -->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Room</label>
								<div class="col-md-7">
									<span id="lblBookingRoom" class="form-control" style="font-size: 1.2em"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Therapist</label>
								<div class="col-md-7">
									<span id="lblBookingTherapist" class="form-control" style="font-size: 1.2em; height: 60px;"/>
									<!-- <input type="text" id="txtBookingTime" class="form-control"> -->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Client Amount</label>
								<div class="col-md-7">
									<span id="lblBookingClientAmt" class="form-control" style="font-size: 1.2em"/>
									<!-- <input type="text" id="txtBookingClient" class="form-control"> -->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Client Name</label>
								<div class="col-md-7">
									<input type="text" id="txtBookingClientName" class="form-control" maxlength="20">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Client Tel</label>
								<div class="col-md-7">
									<input type="text" id="txtBookingClientTel" class="form-control">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnAddBooking" class="btn btn-primary btn-lg">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Add Booking
						</button>
						<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

















