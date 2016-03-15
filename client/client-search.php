<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Client - Search client</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../script/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>
	    
	    <link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	</head>
	<body>
		<div id="header">
			<div id="header-logo"></div>
		</div><!-- header -->
		
		<div id="menu">
			
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
								<input type="radio" name="gender" value="1"> Membership Number
							</label>
						</div>
						<div class="col-sm-2">
							<label class="radio-inline">
								<input type="radio" name="gender" value="2"> Client Name
							</label>
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-2">
							<button type="button" class="btn btn-primary">Search</button>
						</div>
					</div>
				</form> <!-- /form-horizontal -->
			</div> <!-- /container -->
		</div> <!-- /content -->
	</body>
</html>