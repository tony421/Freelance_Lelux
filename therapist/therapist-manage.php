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
	    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
	    
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="therapist-manage.js"></script>
	    
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
    </head>
    <body>
    	<div id="header">
			<div id="header-logo"></div>
		</div><!-- header -->
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Therapist Management ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Name</label>
						<div class="col-sm-2">
							<input type="text" id="txtName" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Username</label>
						<div class="col-sm-2">
							<input type="text" id="txtUsername" class="form-control">
						</div>
						<label class="col-sm-1 control-label">Password</label>
						<div class="col-sm-2">
							<input type="text" id="txtPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-info btn-lg">Add Therapist</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-lg hidden">Update Therapist</button>
							<button type="button" id="btnCancel" class="btn btn-default btn-lg hidden">Cancel</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<table id="tableTherapist" class="display" cellspacing="0" width="100%">
			            	</table>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- #content -->
	</body>
</html>













