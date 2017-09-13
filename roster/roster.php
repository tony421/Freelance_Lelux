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
	    
	    <title>Roster</title>
	    
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
	    <script type="text/javascript" src="../js/autonumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="../js/moment-round.js"></script>
	    <script type="text/javascript" src="roster.js?<?php echo time(); ?>"></script>
	    
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
    </head>
    <body>
    	<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'roster'; require_once '../master-page/menu.php';?>
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Roster ::~</div>
			</div>
			<div class="container-fluid">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-xs-1">
							<button type="button" id="btnPrevious" class="btn btn-default btn-lg">
								<span class="glyphicon glyphicon-chevron-left" style="font-size: 2em;"></span>
							</button>
						</div>
						<div class="col-xs-10">
							<table id="tableRoster" class="display" cellspacing="0" width="100%">
			            	</table>
						</div>
						<div class="col-xs-1"> 
							<button type="button" id="btnNext" class="btn btn-default btn-lg">
								<span class="glyphicon glyphicon-chevron-right" style="font-size: 2em;"></span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
    
    
    
    
    
    
    
    
