<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Login</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    
	    <script type="text/javascript" src="login.js?<?php echo time(); ?>"></script>
	    
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
    </head>
    <body>
    	<?php require_once '../master-page/header.php';?>
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">Log In</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-3 col-sm-offset-3 col-sm-2 control-label">Username</label>
						<div class="col-xs-9 col-sm-3">
							<input type="text" id="txtUsername" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 col-sm-offset-3 col-sm-2 control-label">Password</label>
						<div class="col-xs-9 col-sm-3">
							<input type="password" id="txtPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnLogin" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
								Log In
							</button>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- .content -->
    </body>
</html>











