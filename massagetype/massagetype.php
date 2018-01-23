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
	    
	    <title>Massage Type</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
	    <script type="text/javascript" src="massagetype.js?<?php echo time(); ?>"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
			<div class="title-container">
				<div class="title-text">Massage Type</div>
			</div>
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-3 col-sm-2 control-label">Massage Type Name</label>
						<div class="col-xs-4 col-sm-3">
							<input type="text" id="txtName" class="form-control" maxlength="30">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-3 col-sm-2 control-label">Extra Commission</label>
						<div class="col-xs-4 col-sm-3">
							<input type="text" id="txtComm" class="form-control">
						</div>						
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-primary btn-list">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Massage Type
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-list hidden">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Massage Type
							</button>
							<button type="button" id="btnDelete" class="btn btn-danger btn-list hidden">
								<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
								Delete Massage Type
							</button>
							<button type="button" id="btnCancel" class="btn btn-default btn-list hidden">Cancel</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<table id="tableMassageType" class="display" cellspacing="0" width="100%">
			            	</table>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- #content -->
	</body>
</html>