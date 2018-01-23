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
	    
	    <title>Reception - Reception Record</title>
	    
	    <?php require_once '../master-page/script-main.php';?>
	    <?php require_once '../master-page/script-datatable.php';?>
	    
	    <script type="text/javascript" src="reception.js?<?php echo time(); ?>"></script>	    
	    
	   	<script type="text/javascript">
	    	$(document).ready(function(){
	    		initPage();
		    });
	    </script>
	</head>
	
	<body>
		<div id="content">
			<!--<div class="title-container">
				<div class="title-text">~:: Reception Record ::~</div>
			</div>-->
			<div class="container res-gutter">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-xs-3 col-sm-3 control-label">Reception</label>
						<div class="col-xs-6 col-sm-2">
							<select id="ddlReception" class="form-control">
							</select>
						</div>
						<div class="col-xs-offset-3 col-xs-8 col-sm-offset-0 col-sm-4">
							<label class="checkbox-inline">
								<input type="checkbox" id="cbLateNightWork"> Late Work (After 9.30 PM)
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 col-sm-3 control-label">Working</label>
						<div class="col-xs-4 col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radDay" name="wokring" value="1" checked="checked"> Whole Day
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-offset-3 col-xs-5 col-sm-offset-3 col-sm-2">
							<label class="radio-inline">
								<input type="radio" id="radHour" name="wokring" value="1"> Half Day
							</label>
						</div>
						<div class="col-xs-offset-3 col-xs-5 col-sm-offset-0 col-sm-1">
							<input type="text" id="txtHour" class="form-control" maxlength="2" disabled>
						</div>
						<label class="col-xs-2 col-sm-1 control-label" style="text-align: left;">hours</label>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-3 control-label text-nowrap">Shop Income</label>
						<div class="col-xs-6 col-sm-2">
							<input type="text" id="txtIncome" class="form-control" value="0" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-4 col-sm-3 control-label">Commission</label>
						<div class="col-xs-8 col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Std. Commission</span>
							    <input type="text" id="txtStdCom" class="form-control" value="0" disabled>
						    </div>
						</div>
						<div class="col-xs-offset-4 col-xs-8 col-sm-offset-0 col-sm-3">
							<div class="input-group">
							    <span class="input-group-addon">Extra Commission</span>
							    <input type="text" id="txtExtraCom" class="form-control" value="0">
						    </div>
						</div>
						<div class="col-xs-offset-4 col-xs-8 col-sm-offset-0 col-sm-2">
							<div class="input-group">
							    <span class="input-group-addon">Total</span>
							    <input type="text" id="txtTotalCom" class="form-control" value="0" disabled>
						    </div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAdd" class="btn btn-primary btn-list">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Record
							</button>
							<button type="button" id="btnUpdate" class="btn btn-warning btn-list">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Record
							</button>
							<button type="button" id="btnDelete" class="btn btn-danger btn-list">
								<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
								Delete Record
							</button>
							<button type="button" id="btnCancelEdit" class="btn btn-default btn-list">Cancel</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-10">
							<table id="tableRecord" class="display" cellspacing="0" width="100%">
								<thead>
						            <tr>
						                <th rowspan="2">#</th>
						                <th rowspan="2">Reception</th>
						                <th rowspan="2">Working</th>
						                <th rowspan="2" style="border-right: 1px solid #000;">Shop Income</th>
						                <th colspan="3" class="text-center" style="border-bottom: 1px solid #000;">Commission</th>
						            </tr>
						            <tr>
						            	<th>Standard</th>
						            	<th>Extra</th>
						            	<th>Total</th>
						            </tr>
						        </thead>
				            </table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>














