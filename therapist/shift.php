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
	    
	    <title>Daily Records - Therapists</title>
	    
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
	    
	    <script type="text/javascript" src="../js/main.js?<?php echo time(); ?>"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="../js/moment-round.js"></script>
	    <script type="text/javascript" src="shift.js?<?php echo time(); ?>"></script>
	    
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
						<label class="col-sm-offset-2 col-sm-2 control-label">Therapist</label>
						<div class="col-sm-2">
							<select id="ddlTherapist" class="form-control">
							</select>
							<span id="lblTherapist" class="form-control"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Shift</label>
						<div class="col-sm-2">
							<select id="ddlShift" class="form-control">
							</select>
						</div>
						<label class="col-sm-1 control-label">Start at</label>
						<div class="col-sm-2">
							<input type="text" id="txtTimeStart" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add
							</button>
							<button type="button" id="btnDeleteAll" class="btn btn-danger btn-lg">
								<span class="glyphicon glyphicon-floppy-remove"></span>
								Delete All
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-lg">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update
							</button>
							<button type="button" id="btnCancel" class="btn btn-default btn-lg">
								Cancel
							</button>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-8">
							<table id="tableShift" class="display" cellspacing="0" width="100%">
								<thead>
				            		<tr>
				            			<th>#</th>
				               			<th>Therapist</th>
				               			<th>Shift</th>
				               			<th>Start at</th>
				               			<th class="text-center">Status</th>
				               			<th></th>
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










