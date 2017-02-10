<?php
	//ob_start();
	
	require_once '../controller/authentication.php';

	Authentication::authenticateUser();
	
	//ob_end_flush();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Daily Record</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	    <link rel="stylesheet" href="../css/messagebox.css">
	    <link rel="stylesheet" href="../css/loadingpanel.css">
	    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
	    <script type="text/javascript" src="../js/moment.js"></script>
	    <script type="text/javascript" src="daily-record.js"></script>
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'daily-record'; require_once '../master-page/menu.php';?>
    	
    	<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Daily Record ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="option-container">
						<button type="button" id="btnCommissionReport" class="btn btn-success btn-lg">
							<span class="glyphicon glyphicon-print"></span>
							Commission Report
						</button>
						<button type="button" id="btnIncomeReport" class="btn btn-success btn-lg">
							<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
							Income Report
						</button>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<div id="dateInput" class="input-group date">
							    <input type="text" id="txtDate" class="form-control input-lg" readonly>
							    <span class="input-group-addon input-lg">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="panel with-nav-tabs panel-default">
				<div class="container">
	                <div class="panel-heading" style="padding-bottom: 0;">
                        <ul class="nav nav-tabs">
                            <li class="active"><a name="frameMassage" class="tab-title" href="#tab1" data-toggle="tab">Massage Record</a></li>
                            <li><a name="frameSale" class="tab-title" href="#tab2" data-toggle="tab">Sale Record</a></li>
                            <li><a name="frameReception" class="tab-title" href="#tab3" data-toggle="tab">Reception Record</a></li>
                            <!--<li class="dropdown">
                                <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#tab4default" data-toggle="tab">Default 4</a></li>
                                    <li><a href="#tab5default" data-toggle="tab">Default 5</a></li>
                                </ul>
                            </li>-->
                        </ul>
	                </div>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1">
                        	<iframe name="frameMassage" src="../massage/massage-record.php" 
                        		frameborder="0" width="100%" height="826""></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                        	<iframe name="frameSale" src="../sale/sale.php" 
                        		frameborder="0" width="100%" height="987"></iframe>
                        </div>
                        <div class="tab-pane fade" id="tab3">
                        	<iframe name="frameReception" src="../reception/reception.php" 
                        		frameborder="0" width="100%" height="731"></iframe>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div id="footer"></div>
	</body>
</html>

















