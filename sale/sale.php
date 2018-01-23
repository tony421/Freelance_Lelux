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
	    
	    <title>Sale - Sale Record</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
	    <script type="text/javascript" src="sale.js?<?php echo time(); ?>"></script>	    
	    
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
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-2 col-sm-2 control-label">Product</label>
						<div class="col-xs-8 col-sm-2">
							<select id="ddlProduct" class="form-control">
							</select>
						</div>
						<label class="col-xs-4 col-sm-1 control-label">Price</label>
						<div class="col-xs-6 col-sm-2">
							<input type="text" id="txtPrice" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-2 col-sm-2 control-label">Amount</label>
						<div class="col-xs-6 col-sm-2">
							<input type="text" id="txtAmt" class="form-control" maxlength="4">
						</div>
						<label class="col-xs-4 col-sm-1 control-label">Total</label>
						<div class="col-xs-6 col-sm-2">
							<input type="text" id="txtTotal" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="col-sm-12">
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
					</div>			
					<div class="form-group">
						<div class="col-xs-12 col-sm-offset-3 col-sm-6">
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
						<button type="button" id="btnAdd" class="btn btn-primary btn-list">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Add Sale
						</button>
						<button type="button" id="btnUpdate" class="btn btn-warning btn-list">
							<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
							Update Sale
						</button>
						<button type="button" id="btnDelete" class="btn btn-danger btn-list">
							<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
							Delete Sale
						</button>
						<button type="button" id="btnCancelEdit" class="btn btn-default btn-list">Cancel</button>
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













