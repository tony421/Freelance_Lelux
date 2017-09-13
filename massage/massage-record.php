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
	    
	    <title>Massage - Massage Record</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	    <link rel="stylesheet" href="../css/messagebox.css">
	    <link rel="stylesheet" href="../css/loadingpanel.css">
	    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../css/jquery.bootstrap-touchspin.css">
	    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
	    
	    <script type="text/javascript" src="../js/main.js?<?php echo time(); ?>"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/jquery.bootstrap-touchspin.js"></script>
	    <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="../js/moment-round.js"></script>
	    <script type="text/javascript" src="massage-record.js?<?php echo time(); ?>"></script>	    
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
    	<div id="content">
			<!--<div class="title-container">
				<div class="title-text">~:: Massage Record ::~</div>
			</div>-->
			<div class="container">
				<form class="form-horizontal">
					<!--<div class="option-container">
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
						<div class="col-sm-offset-3 col-sm-4">
							<div id="dateInput" class="input-group date">
							    <input type="text" id="txtDate" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
					</div>-->
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Therapist</label>
						<div class="col-sm-2">
							<select id="ddlTherapist" class="form-control">
							</select>
						</div>
						<div class="col-sm-2">
							<label class="checkbox-inline">
								<input type="checkbox" id="cbRequested"> Requested
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Massage Type</label>
						<div class="col-sm-3">
							<select id="ddlMassageType" class="form-control">
							</select>
						</div>
						<label class="col-sm-1 control-label">Room</label>
						<div class="col-sm-1">
							<select id="ddlRoom" class="form-control">
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Minutes</label>
						<div class="col-sm-2">
							<input type="text" id="txtMinutes" class="form-control">
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
						<label class="col-sm-offset-1 col-sm-2 control-label">Free Stamp (Minute)</label>
						<div class="col-sm-2">
							<input type="text" id="txtStamp" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Paid by</label>
						<div class="col-sm-2">
							<div class="input-group">
							    <span class="input-group-addon">Cash</span>
							    <input type="text" id="txtCash" class="form-control" value="0">
						    </div>
						</div>
						<div class="col-sm-2">
							<label class="checkbox-inline">
								<input type="checkbox" id="cbPromotionPrice"> Promotion Price
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-2">
							<div class="input-group">
							    <span class="input-group-addon">Credit</span>
							    <input type="text" id="txtCredit" class="form-control" value="0">
						    </div>
						</div>
						<div class="col-sm-2">
							<div class="input-group">
							    <span class="input-group-addon">HICAPS</span>
							    <input type="text" id="txtHICAPS" class="form-control" value="0">
						    </div>
						</div>
						<div class="col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Redeemed Voucher</span>
							    <input type="text" id="txtVoucher" class="form-control" value="0">
						    </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Paid Total</label>
						<div class="col-sm-2">
							<input type="text" id="txtPaidTotal" class="form-control" value="0" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-1 col-sm-2 control-label">Commission</label>
						<div class="col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Standard Commission</span>
							    <input type="text" id="txtStdCommission" class="form-control" disabled>
						    </div>
						</div>
						<div class="col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Extra Commission</span>
							    <input type="text" id="txtReqReward" class="form-control" value="0">
						    </div>
						</div>
						<div class="col-sm-2">
							<div class="input-group">
							    <span class="input-group-addon">Total</span>
							    <input type="text" id="txtCommissionTotal" class="form-control" disabled>
						    </div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Record
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-lg">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Record
							</button>
							<button type="button" id="btnDelete" class="btn btn-danger btn-lg">
								<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
								Delete Record
							</button>
							<button type="button" id="btnCancelEdit" class="btn btn-default btn-lg">Cancel</button>
						</div>
					</div>
				</form>
			</div>
			<div class="container-fluid">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-12">
							<table id="tableRecord" class="display" cellspacing="0" width="100%">
								<thead>
						            <tr>
						                <th rowspan="2">#</th>
										<th rowspan="2">Name</th>
						                <th rowspan="2">Therapist</th>
						                <!--<th rowspan="2">Req.</th>-->
						                <th rowspan="2">Type</th>
						                <th rowspan="2" class="text-center">Time In/Out<br>(minutes)</th>
						                <th rowspan="2">Room</th>
						                <th rowspan="2" class="text-center" style="border-right: 1px solid #000;">Stamp<br>(minutes)</th>
						                <th colspan="5" class="text-center" style="border-right: 1px solid #000;">Paid By</th>
						                <!--<th colspan="3" class="text-center">Commission</th>-->
						                <th rowspan="2" class="text-center">Commission</th>
						            </tr>
						            <tr>
						            	<th>Cash</th>
						            	<!--<th>Promo. Price</th>-->
						            	<th>Credit</th>
						            	<th>HICAPS</th>
						            	<th>Voucher</th>
						            	<th style="border-right: 1px solid #000;">Total</th>
						            	<!--
						            	<th>Standard</th>
						            	<th>Extra</th>
						            	<th>Total</th>
						            	-->
						            </tr>
						        </thead>
				            </table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>










