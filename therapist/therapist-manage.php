<?php
	require_once '../login/page-authentication.php';
	
	Authentication::permissionCheck(basename($_SERVER['PHP_SELF']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Therapist - Therapist Management</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
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
				<div class="title-text">Therapist Management</div>
			</div>
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-2 col-sm-2 control-label text-nowrap">Therapist Name</label>
						<div class="col-xs-8 col-sm-2">
							<input type="text" id="txtName" class="form-control">
						</div>
						<label class="col-xs-4 col-sm-2 col-md-1 control-label">Password</label>
						<div class="col-xs-8 col-sm-2">
							<input type="text" id="txtPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<!--<label class="col-sm-offset-2 col-sm-2 control-label">Username</label>
						<div class="col-sm-2">
							<input type="text" id="txtUsername" class="form-control">
						</div>-->
						<label class="col-xs-4 col-sm-offset-2 col-sm-2 control-label">Guarantee</label>
						<div class="col-xs-8 col-sm-2">
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
							<button type="button" id="btnAdd" class="btn btn-primary btn-list">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Therapist
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning hidden">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Therapist
							</button>
							<button type="button" id="btnDelete" class="btn btn-danger hidden">
								<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
								Delete Therapist
							</button>
							<button type="button" id="btnCancel" class="btn btn-default hidden">Cancel</button>
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













