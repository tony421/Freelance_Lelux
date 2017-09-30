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
	    <link rel="stylesheet" href="../css/jquery.dataTables.min.css"/>
	    <link rel="stylesheet" href="../css/jquery.bootstrap-touchspin.css">
	    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
	    
	    <script type="text/javascript" src="../js/main.js?<?php echo time(); ?>"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/jquery.bootstrap-touchspin.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="../js/moment-round.js"></script>
	    <script type="text/javascript" src="../js/bootstrap-datepicker.js?<?php echo time(); ?>"></script>
	    <script type="text/javascript" src="daily-record.js?<?php echo time(); ?>"></script>
	    
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
                        <ul id="tabDailyRecords" class="nav nav-tabs">
                        	<li class="active"><a name="frameTherapist" class="tab-title" href="#tab4" data-toggle="tab">Therapists</a></li>
                        	<li><a name="frameQueueing" class="tab-title" href="#tab5" data-toggle="tab">Walk-In</a></li>
                        	<li><a name="frameBooking" class="tab-title" href="#tab6" data-toggle="tab">Booking</a></li>
                            <li><a name="frameMassage" class="tab-title" href="#tab1" data-toggle="tab">Record Details</a></li>
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
                    	<div class="tab-pane fade in active" id="tab4">
                        	<iframe name="frameTherapist" src="../therapist/shift.php" 
                        		frameborder="0" width="100%" height="575"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab5">
                        	<iframe name="frameQueueing" src="../queueing/queueing.php" 
                        		frameborder="0" width="100%" height="800"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab6">
                        	<iframe name="frameBooking" src="../booking/booking.php" 
                        		frameborder="0" width="100%" height="900"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab1">
                        	<iframe name="frameMassage" src="../massage/massage-record.php" 
                        		frameborder="0" width="100%" height="870"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                        	<iframe name="frameSale" src="../sale/sale.php" 
                        		frameborder="0" width="100%" height="1000"></iframe>
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
				<div class="modal-content panel-info">
					<div class="modal-header panel-heading">
						<h4 class="modal-title panel-title"><b>Booking Details</b></h4>
					</div>
					<div class="modal-body panel-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Time</label>
								<div class="col-md-7">
									<span id="lblBookingTime" class="form-control form-control-lable"><span class="text-mark">60</span> minutes from <span class="text-mark">14:00</span> to <span class="text-mark">15:15</span></span>
									<!-- <input type="text" id="txtBookingTime" class="form-control"> -->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Room</label>
								<div class="col-md-7">
									<span id="lblBookingRoom" class="form-control form-control-lable" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Client Amount</label>
								<div class="col-md-7">
									<span id="lblBookingClientAmt" class="form-control form-control-lable" />
									<!-- <input type="text" id="txtBookingClient" class="form-control"> -->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Massage</label>
								<div class="col-md-7">
									<span id="lblBookingTherapist" class="form-control form-control-lable" style="height: 90px; overflow: auto;"/>
									<!-- <input type="text" id="txtBookingTime" class="form-control"> -->
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
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Remark</label>
								<div class="col-md-7">
									<textarea type="text" id="txtBookingRemark" class="form-control"
										rows="2" maxlength="100"></textarea>
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
		<!-- Modal : Booking Details -->
		
		<!-- Modal : Booking Queue -->
		<div class="modal fade" id="modalBookingQueue" tabindex="-1" role="dialog" aria-labelledby="modalBookingQueue">
			<div class="modal-dialog" role="document">
				<div class="modal-content panel-info">
					<div class="modal-header panel-heading">
						<h4 class="modal-title panel-title"><b>Booking Queue</b></h4>
					</div>
					<div class="modal-body panel-body" style="padding: 10px 0 0 0;">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">Client</label>
								<div class="col-sm-3">
									<input type="text" id="txtBookingQueueClientName" class="form-control" maxlength="25">
								</div>
								<div class="col-sm-5">
									<input type="text" id="txtBookingQueueClientTel" class="form-control">
								</div>
								<!--
								<div class="col-sm-2">
									<span id="lblBookingQueueClient" class="form-control form-control-lable"><span class="text-mark">John</span>, xxxx-xxx-xxx (1 of 2)</span>
								</div>
								-->
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Amount</label>
								<div class="col-md-8">
									<span id="lblBookingQueueAmount" class="form-control form-control-lable"><span class="text-mark">2</span> people <span class="text-mark">2</span> single rooms</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Therapist</label>
								<div class="col-md-8">
									<span id="lblBookingQueueTherapist" class="form-control form-control-lable"><span class="text-mark">Thai Massage</span> with <span class="text-mark">[Any]</span></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Time</label>
								<div class="col-sm-3">
									<input type="text" id="txtBookingQueueMinutes" class="form-control" maxlength="4">
								</div>
								<div class="col-sm-2" style="padding-right: 0;">
									<input type="text" id="txtBookingQueueTimeIn" class="form-control">
								</div>
								<label class="col-sm-1 control-label"
									style="text-align: center; padding-left: 0; padding-right: 0;">to</label>
								<div class="col-sm-2" style="padding-left: 0;">
									<input type="text" id="txtBookingQueueTimeOut" class="form-control" disabled>
								</div>
								<div style="display: none;" class="col-md-7">
									<span id="lblBookingQueueTime" class="form-control form-control-lable"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Remark</label>
								<div class="col-md-8">
									<textarea type="text" id="txtBookingQueueRemark" class="form-control"
										rows="1" maxlength="100"></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12 text-center">
									<button type="button" id="btnBookingQueueUpdate" class="btn btn-warning">
										<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
										Update
									</button>
									<button type="button" id="btnBookingQueueDelete" class="btn btn-danger">
										<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
										Delete
									</button>
								</div>
							</div>
						</form>
						<div class="form-group">
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class="panel-title-sub">
											<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
											Therapists
										</div>
									</div>
									<div class="panel-body" style="padding: 0 0 5px 0;">
										<div class="form-group">
											<div class="col-sm-12">
												<table id="tableBookingQueueTherapist" class="display" cellspacing="0" width="100%">
													<thead>
														<tr>
											                <th></th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class="panel-title-sub">
											<span class="glyphicon glyphicon-modal-window" aria-hidden="true"></span>
											Rooms
										</div>
									</div>
									<div class="panel-body" style="padding: 0 0 5px 0;">
										<div class="form-group">
											<div class="col-sm-12">
												<table id="tableBookingQueueRoom" class="display" cellspacing="0" width="100%">
													<thead>
														<tr>
											                <th></th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnBookingQueueRecord" class="btn btn-primary">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Record
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal : Booking Queue -->
		
		<!-- Modal : Massage Recording -->
		<div class="modal fade" id="modalMassageRecord" tabindex="-1" role="dialog" aria-labelledby="modalMassageRecord">
			<div class="modal-dialog" role="document">
				<div class="modal-content panel-info">
					<div class="modal-header panel-heading">
						<h4 class="modal-title panel-title"><b>Massage Record</b></h4>
					</div>
					<div class="modal-body panel-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">Time</label>
								<div class="col-sm-3">
									<input type="text" id="txtRecordMinutes" class="form-control" maxlength="4">
								</div>
								<div class="col-sm-2" style="padding-right: 0;">
									<input type="text" id="txtRecordTimeIn" class="form-control">
								</div>
								<label class="col-sm-1 control-label"
									style="text-align: center; padding-left: 0; padding-right: 0;">to</label>
								<div class="col-sm-2" style="padding-left: 0;">
									<input type="text" id="txtRecordTimeOut" class="form-control" disabled>
								</div>
								<div style="display: none;" class="col-md-7">
									<span id="lblRecordTime" class="form-control form-control-lable"><span class="text-mark">60</span> minutes from <span class="text-mark">14:00</span> to <span class="text-mark">15:15</span></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Therapist</label>
								<div class="col-sm-2">
									<span id="lblRecordTherapist" class="form-control form-control-lable"></span>
								</div>
								<div class="col-sm-2">
									<label class="checkbox-inline">
										<input type="checkbox" id="cbRecordRequested"> Requested
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Room</label>
								<div class="col-sm-2">
									<span id="lblRecordRoom" class="form-control form-control-lable"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Massage Type</label>
								<div class="col-sm-4">
									<select id="ddlRecordMassageType" class="form-control">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Free Stamp</label>
								<div class="col-sm-2">
									<input type="text" id="txtRecordStamp" class="form-control">
								</div>
								<label class="col-sm-2 control-label" style="padding-left: 0px; font-weight: normal; text-align: left;">minutes</label>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Paid by</label>
								<div class="col-sm-4">
									<div class="input-group">
									    <span class="input-group-addon">Cash</span>
									    <input type="text" id="txtRecordCash" class="form-control" value="0">
								    </div>
								</div>
								<div class="col-sm-4">
									<label class="checkbox-inline">
										<input type="checkbox" id="cbRecordPromo"> Promotion Price
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-4">
									<div class="input-group">
									    <span class="input-group-addon">Credit</span>
									    <input type="text" id="txtRecordCredit" class="form-control" value="0">
								    </div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
									    <span class="input-group-addon">HICAPS</span>
									    <input type="text" id="txtRecordHICAPS" class="form-control" value="0">
								    </div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-5">
									<div class="input-group">
									    <span class="input-group-addon">Redeemed Voucher</span>
									    <input type="text" id="txtRecordVoucher" class="form-control" value="0">
								    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Paid Total</label>
								<div class="col-sm-3">
									<input type="text" id="txtRecordTotal" class="form-control" value="0" disabled>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnAddRecord" class="btn btn-primary btn-lg">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Add Record
						</button>
						<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal : Massage Recording -->
	</body>
</html>

















