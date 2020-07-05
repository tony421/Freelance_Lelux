<?php
	require_once '../login/page-authentication.php';
	
	Authentication::permissionCheck(basename($_SERVER['PHP_SELF']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Client - Search client</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
	    
	    <script type="text/javascript" src="client-booking-history.js?<?php echo time(); ?>"></script>
	    
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	<body>
		<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'client'; require_once '../master-page/menu.php';?>
    	
    	<div id="content">
    		<div class="title-container">
				<div class="title-text">Booking History</div>
			</div>
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-2 col-sm-offset-1 col-sm-2 control-label">Date</label>
						<div class="col-xs-10 col-sm-3">
							<div id="dateStartInput" class="input-group date">
							    <input type="text" id="txtDateStart" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
						<div class="col-xs-2 col-sm-1 text-center">
							<label class="control-label">To</label>
						</div>
						<div class="col-xs-10 col-sm-3">
							<div id="dateEndInput" class="input-group date">
							    <input type="text" id="txtDateEnd" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 control-label">Search By</label>
						<div class="col-xs-offset-1 col-xs-5 col-sm-offset-0 col-sm-3 col-md-2">
							<label class="radio-inline">
								<input type="radio" id="radSearchName" name="searchby" value="1" checked="checked" /> Booking Name
							</label>
						</div>
						<div class="col-xs-6 col-sm-3">
							<input type="text" id="txtText" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-offset-1 col-xs-12 col-sm-offset-2 col-sm-4">
							<label class="radio-inline">
								<input type="radio" id="radSearchTel" name="searchby" value="2" /> Booking Tel.
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-offset-4 col-xs-8 col-sm-offset-4 col-sm-2">
							<button type="button" id="btnSearch" class="btn btn-primary">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								Search
							</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-10">
							<table id="tableBookingHistory" class="display" cellspacing="0" width="100%">
				            </table>
						</div>
					</div>
				</form>
			</div>
    	</div>
    	
    	<div id="footer">
		</div> <!-- footer -->
	</body>
</html>