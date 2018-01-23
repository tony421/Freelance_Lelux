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
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
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
				<div class="title-text">Roster</div>
			</div>
			<div class="container-fluid">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-xs-1 roster-selection-res-gutter">
							<button type="button" id="btnPrevious" class="btn btn-default">
								<span class="roster-selection">
									<span class="glyphicon glyphicon-chevron-left"></span>
									</span>
							</button>
						</div>
						<div class="col-xs-10">
							<table id="tableRoster" class="display" cellspacing="0" width="100%">
			            	</table>
						</div>
						<div class="col-xs-1 roster-selection-res-gutter"> 
							<button type="button" id="btnNext" class="btn btn-default">
								<span class="roster-selection">
									<span class="glyphicon glyphicon-chevron-right"></span>
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
    
    
    
    
    
    
    
    
