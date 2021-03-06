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
	    
	    <title>Change Password</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    
	    <script type="text/javascript" src="change-password.js?<?php echo time(); ?>"></script>
	    
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
    </head>
	<body>
    	<?php require_once '../master-page/header.php';?>
    	
    	<?php $_GET['page'] = 'change-password'; require_once '../master-page/menu.php';?>
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">Change Password</div>
			</div>
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-3 col-sm-2 control-label">Old Password</label>
						<div class="col-xs-6 col-sm-3">
							<input type="password" id="txtOldPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-3 col-sm-2 control-label">New Password</label>
						<div class="col-xs-6 col-sm-3">
							<input type="password" id="txtNewPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-offset-2 col-sm-3 control-label">New Password (Confirm)</label>
						<div class="col-xs-6 col-sm-3">
							<input type="password" id="txtNewPasswordConfirm" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnChangePassword" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Change Password
							</button>
						</div>
					</div>
				</form> <!-- .form-horizontal -->
			</div> <!-- .container -->
		</div> <!-- .content -->
	</body>
</html>











