<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Client - Search client</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>
	    
	    <link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	    <link rel="stylesheet" href="../css/messagebox.css">
	    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="client-search.js"></script>
	    
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
		
		<div id="menu">
			<nav class="navbar navbar-default">
  				<div class="container">
  					<!-- Brand and toggle get grouped for better mobile display -->
  					<div class="navbar-header">
      					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					        <span class="sr-only">Toggle navigation</span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
      					</button>
						<a class="navbar-brand" href="#">Lulex</a>
    				</div>
    				
    				<div class="collapse navbar-collapse">
      					<ul class="nav navbar-nav">
      						<li>
      							<a href="../client/client-add.php">Add Client</a>
      						</li>
        					<li class="active">
        						<a href="../client/client-search.php">Search Client</a>
        					</li>
      					</ul>
      				</div> <!-- /.navbar-collapse -->
  				</div> <!-- /.container-fluid -->
  			</nav> <!-- /nav.navbar -->
		</div> <!-- /menu -->
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Search Client ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label">Search By</label>
						<div class="col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radSearchMem" name="gender" value="1" checked="checked"> Membership Number
							</label>
						</div>
						<div class="col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radSearchName" name="gender" value="2"> Client Name
							</label>
						</div>
						<div class="col-sm-3">
							<input type="text" id="txtText" class="form-control">
						</div>
						<div class="col-sm-2">
							<button type="button" id="btnSearchClient" class="btn btn-primary">Search</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<table id="tableClient" class="display" cellspacing="0" width="100%">
				            </table>
						</div>
					</div>
				</form> <!-- /form-horizontal -->
			</div> <!-- /container -->
		</div> <!-- /content -->
		
		<div id="footer">
		</div> <!-- footer -->
	</body>
</html>