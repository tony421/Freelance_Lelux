<?php
	//ob_start();
	
	require_once '../controller/authentication.php';

	Authentication::authenticateUser();
	
	//ob_end_flush();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Reception - Reception Record</title>
	    
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
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/jquery.bootstrap-touchspin.js"></script>
	    <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="reception.js"></script>	    
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Reception Record ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label">Reception</label>
						<div class="col-sm-2">
							<select id="ddlReception" class="form-control">
							</select>
						</div>
						<div class="col-sm-4">
							<label class="checkbox-inline">
								<input type="checkbox" id="cbLateNightWork"> Work after 9.30 PM
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Working</label>
						<div class="col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radDay" name="wokring" value="1" checked="checked"> Whole Day
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radHour" name="wokring" value="1"> Half Day
							</label>
						</div>
						<div class="col-sm-1">
							<input type="text" id="txtHour" class="form-control" maxlength="2" disabled>
						</div>
						<label class="col-sm-1 control-label" style="text-align: left;">hours</label>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Shop Income</label>
						<div class="col-sm-2">
							<input type="text" id="txtIncome" class="form-control" value="0" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Commission</label>
						<div class="col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Standard Commission</span>
							    <input type="text" id="txtStdCom" class="form-control" value="0" disabled>
						    </div>
						</div>
						<div class="col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Extra Commission</span>
							    <input type="text" id="txtExtraCom" class="form-control" value="0" disabled>
						    </div>
						</div>
						<div class="col-sm-2">
							<div class="input-group">
							    <span class="input-group-addon">Total</span>
							    <input type="text" id="txtTotalCom" class="form-control" value="0" disabled>
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
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-10">
							<table id="tableRecord" class="display" cellspacing="0" width="100%">
								<thead>
						            <tr>
						                <th rowspan="2">#</th>
						                <th rowspan="2">Reception</th>
						                <th rowspan="2">Working</th>
						                <th rowspan="2" style="border-right: 1px solid #000;">Shop Income</th>
						                <th colspan="3" class="text-center" style="border-bottom: 1px solid #000;">Commission</th>
						            </tr>
						            <tr>
						            	<th>Standard</th>
						            	<th>Extra</th>
						            	<th>Total</th>
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














