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
	    
	    <title>Therapist - Therapist Management</title>
	    
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
	    
	    <script type="text/javascript" src="../js/main.js?<?php echo time(); ?>"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="therapist-manage.js?<?php echo time(); ?>"></script>
	    
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
    </head>
    <body>
    	<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'therapist-manage'; require_once '../master-page/menu.php';?>
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Therapist Management ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Therapist Name</label>
						<div class="col-sm-2">
							<input type="text" id="txtName" class="form-control">
						</div>
						<label class="col-sm-1 control-label">Password</label>
						<div class="col-sm-2">
							<input type="text" id="txtPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<!--<label class="col-sm-offset-2 col-sm-2 control-label">Username</label>
						<div class="col-sm-2">
							<input type="text" id="txtUsername" class="form-control">
						</div>-->
						<label class="col-sm-offset-2 col-sm-2 control-label">Guarantee</label>
						<div class="col-sm-2">
							<input type="text" id="txtGuarantee" class="form-control">
						</div>
					</div>
					<!--<div id="rowActiveInput" class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Currently Working</label>
						<div class="col-sm-3">
							<label class="radio-inline text-success">
								<input type="radio" id="radActiveYes" name="active" value="1" checked="checked"> <b>Yes</b>
							</label>
							<label class="radio-inline text-danger">
								<input type="radio" id="radActiveNo" name="active" value="0"> <b>No</b>
							</label>
						</div>
					</div>-->
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Therapist
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-lg hidden">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Therapist
							</button>
							<button type="button" id="btnDelete" class="btn btn-danger btn-lg hidden">
								<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
								Delete Therapist
							</button>
							<button type="button" id="btnCancel" class="btn btn-default btn-lg hidden">Cancel</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<table id="tableTherapist" class="display" cellspacing="0" width="100%">
			            	</table>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- #content -->
		
		<div id="footer">
		</div> <!-- footer -->
	</body>
</html>













