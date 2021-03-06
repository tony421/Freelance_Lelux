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
	    
	    <title>Daily Records - Queueing</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
	    <script type="text/javascript" src="queueing.js?<?php echo time(); ?>"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
    	<div id="content">
    		<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-1 col-sm-2 control-label">Minutes</label>
						<div class="col-xs-5 col-sm-2">
							<input type="text" id="txtMinutes" class="form-control" maxlength="4">
						</div>
						<label class="col-xs-4 col-sm-1 control-label">Time In</label>
						<div class="col-xs-5 col-sm-2">
							<input type="text" id="txtTimeIn" class="form-control">
						</div>
						<label class="col-xs-4 col-sm-1 control-label">Time Out</label>
						<div class="col-xs-5 col-sm-2">
							<input type="text" id="txtTimeOut" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-1 col-sm-2 control-label">Client</label>
						<div class="col-xs-4 col-sm-2">
							<input type="text" id="txtClient" class="form-control" maxlength="2">
						</div>
						<div class="col-xs-offset-4 col-xs-4 col-sm-offset-0 col-sm-2 text-left">
							<button type="button" id="btnSearch" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								Search
							</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-8">
							<div id="alertAvailability" class="alert alert-success text-center" style="display: none;"></div>
							<div id="alertUnavailability" class="alert alert-danger text-center" style="display: none;"></div>
						</div>
						<div class="col-sm-offset-2 col-sm-8 text-center">
							<button type="button" id="btnRecord" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Record
							</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="panel-title">
										<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										Therapists
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<div class="col-sm-12">
											<table id="tableTherapist" class="display" cellspacing="0" width="100%">
												<thead>
													<tr>
										                <th>#</th>
										                <th>Name</th>
										                <th>Available</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="panel-title">
										<span class="glyphicon glyphicon-modal-window" aria-hidden="true"></span>
										Rooms
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<div class="col-sm-12">
											<table id="tableRoom" class="display" cellspacing="0" width="100%">
												<thead>
													<tr>
										                <th>Room No.</th>
										                <th>Available</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
    	</div>
    </body>
</html>












