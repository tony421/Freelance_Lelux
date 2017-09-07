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
	    
	    <title>Product</title>
	    
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
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="product.js"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Product ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-offset-3 col-sm-2 control-label">Product Name</label>
						<div class="col-sm-3">
							<input type="text" id="txtName" class="form-control" maxlength="30">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-3 col-sm-2 control-label">Product Price</label>
						<div class="col-sm-2">
							<input type="text" id="txtPrice" class="form-control">
						</div>
						<div class="col-sm-2">
							<label class="checkbox-inline" title="The price can be changed when recording sales">
								<input type="checkbox" id="cbChangeable"> Changeable
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Product
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-lg hidden">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Product
							</button>
							<button type="button" id="btnDelete" class="btn btn-danger btn-lg hidden">
								<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
								Delete Product
							</button>
							<button type="button" id="btnCancel" class="btn btn-default btn-lg hidden">Cancel</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<table id="tableProduct" class="display" cellspacing="0" width="100%">
			            	</table>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- #content -->
	</body>
</html>