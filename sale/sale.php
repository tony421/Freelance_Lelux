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
	    
	    <title>Sale - Sale Record</title>
	    
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
	    <script type="text/javascript" src="sale.js"></script>	    
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
			<!--<div class="title-container">
				<div class="title-text">~:: Sale Record ::~</div>
			</div>-->
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Product</label>
						<div class="col-sm-2">
							<select id="ddlProduct" class="form-control">
							</select>
						</div>
						<label class="col-sm-1 control-label">Price</label>
						<div class="col-sm-2">
							<input type="text" id="txtPrice" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Amount</label>
						<div class="col-sm-2">
							<input type="text" id="txtAmt" class="form-control" maxlength="4">
						</div>
						<label class="col-sm-1 control-label">Total</label>
						<div class="col-sm-2">
							<input type="text" id="txtTotal" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="button" id="btnAddCart" class="btn btn-info">
							<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
							Add to Cart
						</button>
						<button type="button" id="btnUpdateCart" class="btn btn-warning">
							<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
							Update to Cart
						</button>
						<button type="button" id="btnCancelEditCart" class="btn btn-default">
							Cancel
						</button>
					</div>			
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="panel-title">
										<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
										Cart
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<div class="col-sm-12">
											<table id="tableCart" class="display" cellspacing="0" width="100%">
												<thead>
								            		<tr>
								               			<th>Product</th>
								               			<th>Amount</th>
								               			<th>$</th>
								               			<th></th>
								            		</tr>
								          		</thead>
								          		<tfoot>
										            <tr>
										                <th colspan="2" class="text-right" style="padding-right: 0px;">Total:</th>
										                <th></th>
										                <th></th>
										            </tr>
										        </tfoot>
								          </table>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6">
											<div class="input-group">
											    <span class="input-group-addon">Cash</span>
											    <input type="text" id="txtCash" class="form-control" value="0">
										    </div>
										</div>
										<div class="col-sm-6">
											<div class="input-group">
											    <span class="input-group-addon">Credit</span>
											    <input type="text" id="txtCredit" class="form-control" value="0">
										    </div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="button" id="btnAdd" class="btn btn-primary btn-lg">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Add Sale
						</button>
						<button type="button" id="btnUpdate" class="btn btn-warning btn-lg">
							<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
							Update Sale
						</button>
						<button type="button" id="btnDelete" class="btn btn-danger btn-lg">
							<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
							Delete Sale
						</button>
						<button type="button" id="btnCancelEdit" class="btn btn-default btn-lg">Cancel</button>
					</div>
					
					<div class="form-group">
						<div class="col-sm-12">
							<table id="tableSale" class="display" cellspacing="0" width="100%">
								<thead>
				            		<tr>
				            			<th rowspan="2">#</th>
				               			<th rowspan="2">Receipt No. (Date/Time)</th>
				               			<th rowspan="2">Product</th>
				               			<th rowspan="2">$</th>
				               			<th rowspan="2" style="border-right: 1px solid #000;">Total</th>
				               			<th colspan="2" class="text-center" style="border-right: 1px solid #000;">Paid By</th>
				               			<th rowspan="2"></th>
				            		</tr>
				            		<tr>
				            			<th>Cash</th>
				            			<th style="border-right: 1px solid #000;">Credit</th>
				            		</tr>
				          		</thead>
				          </table>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>













