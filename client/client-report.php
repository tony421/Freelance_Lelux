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
	    
	    <title>Client - Client reports</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>
	    
	    <link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	    <link rel="stylesheet" href="../css/messagebox.css">
	    <link rel="stylesheet" href="../css/loadingpanel.css">
	    <link rel="stylesheet" href="../css/jquery.bootstrap-touchspin.css">
	    
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/loadingpanel.js"></script>
	    <script type="text/javascript" src="../js/autoNumeric.js"></script>
	    <script type="text/javascript" src="../js/jquery.bootstrap-touchspin.js"></script>
	    <script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
	    <script type="text/javascript" src="client-report.js"></script>
	    <script type="text/javascript" src="client-variable.js"></script>
	    
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
				<div class="title-text">~:: Client Report ::~</div>
			</div>
			<div class="container">
				<div class="option-container">
					<button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#popupPrintReceipt">
						<span class="glyphicon glyphicon-print"></span>
						Print Receipt
					</button>
					<button type="button" id="btnPrintReport" class="btn btn-success btn-lg">
						<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
						Print Report
					</button>
				</div>
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Health Fund</label>
						<div class="col-sm-4">
							<select id="ddlHealthFund" class="form-control" disabled>
							</select>
						</div>
					</div>
					<div id="rowHealthFundInput" class="form-group">
						<label class="col-sm-2 control-label text-danger">*Membership No.</label>
						<div class="col-sm-3">
							<input type="text" id="txtMemNo" class="form-control" readonly="readonly">
						</div>
						<label class="col-sm-2 control-label text-danger">*Patient ID</label>
						<div class="col-sm-2">
							<input type="text" id="txtPatientID" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label text-danger">*First Name</label>
						<div class="col-sm-2">
							<input type="text" id="txtFirstName" class="form-control" readonly="readonly">
						</div>
						<label class="col-sm-2 control-label text-danger">*Last Name</label>
						<div class="col-sm-2">
							<input type="text" id="txtLastName" class="form-control" readonly="readonly">
						</div>
						<label class="col-sm-1 control-label">Gender</label>
						<div class="col-sm-3">
							<label class="radio-inline">
								<input type="radio" id="radMale" name="gender" value="0" checked="checked" disabled> Male
							</label>
							<label class="radio-inline">
								<input type="radio" id="radFemale" name="gender" value="1" disabled> Female
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Address</label>
						<div class="col-sm-5">
							<input type="text" id="txtAddress" class="form-control" readonly="readonly">
						</div>
						<label class="col-sm-2 control-label">Postcode</label>
						<div class="col-sm-2">
							<input type="text" id="txtPostcode" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-5">
							<input type="text" id="txtEmail" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Contact No.</label>
						<div class="col-sm-5">
							<input type="text" id="txtContactNo" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Date of Birth</label>
						<div class="col-sm-5">
							<input type="text" id="txtBirthday" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Occupation</label>
						<div class="col-sm-5">
							<input type="text" id="txtOccupation" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Finding us</label>
						<div class="col-sm-10">
							<label class="checkbox-inline">
								<input type="checkbox" id="cbTrueLocal" value="1" disabled> True Local
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="cbGoogle" value="2" disabled> Google 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="cbPassing" value="3" disabled> Passing By 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="cbWord" value="4" disabled> Word of mouth 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="cbFlyer" value="5" disabled> Flyer 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="cbFacebook" value="6" disabled> Facebook 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="cbGiftVoucher" value="7" disabled> Gift Voucher 
							</label>						
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-5">
							<label class="checkbox-inline">
								<input type="checkbox" id="cbReferred" value="8" disabled> Referred By
							</label>
							<input type="text" id="txtReferred" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Sports/Activities</label>
						<div class="col-sm-5">
							<input type="text" id="txtSports" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Conditions Apply</label>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbStroke" value="1" disabled> Stroke
						    		</label>
							    </span>
							    <input type="text" id="txtStroke" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbCancer" value="2" disabled> Cancer
						    		</label>
							    </span>
							    <input type="text" id="txtCancer" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbIsomnia" value="3" disabled> Isomnia
						    		</label>
							    </span>
							    <input type="text" id="txtIsomnia" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbHeadache" value="4" disabled> Headache
						    		</label>
							    </span>
							    <input type="text" id="txtHeadache" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbHeartCon" value="5" disabled> Heart Conditions
						    		</label>
							    </span>
							    <input type="text" id="txtHeartCon" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbPain" value="6" disabled> Pain/Stiffness
						    		</label>
							    </span>
							    <input type="text" id="txtPain" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbBloodPressure" value="7" disabled> High/Low Blood Pressure
						    		</label>
							    </span>
							    <input type="text" id="txtBloodPressure" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbAllergy" value="8" disabled> Allergies/Asthma
						    		</label>
							    </span>
							    <input type="text" id="txtAllergy" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbBrokenBone" value="9" disabled> Broken/Dislocated Bones
						    		</label>
							    </span>
							    <input type="text" id="txtBrokenBone" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbDisease" value="10" disabled> Contagious/Infectious Diseases
						    		</label>
							    </span>
							    <input type="text" id="txtDisease" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbPregnancy" value="11" disabled> Pregnancy/Breastfeeding
						    		</label>
							    </span>
							    <input type="text" id="txtPregnancy" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" id="cbSoreBack" value="12" disabled> Sore Back
						    		</label>
							    </span>
							    <input type="text" id="txtSoreBack" class="form-control" readonly="readonly">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Other Conditions or Recent injuries</label>
						<div class="col-sm-6">
							<input type="text" id="txtOtherCon" class="form-control" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Emergency Contact</label>
						<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">Name</span>
								<input type="text" id="txtEmerConName" class="form-control" readonly="readonly">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">Phone No.</span>
								<input type="text" id="txtEmerConNo" class="form-control" readonly="readonly">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnEditClient" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								Edit Client
							</button>
							<button type="button" id="btnUpdateClient" class="btn btn-warning btn-lg hidden">
								<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								Update Client
							</button>
							<button type="button" id="btnCancelEdit" class="btn btn-default btn-lg hidden">Cancel</button>
						</div>
					</div>
				</form> <!-- /form-horizontal -->
				
				
				<div class="title-container">
					<div class="title-text">~:: Report ::~</div>
				</div>
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Date</label>
						<div class="col-sm-2">
							<input type="text" id="txtReportDate" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Therapist</label>
						<div class="col-sm-2">
							<select id="ddlReportTherapist" class="form-control">
							</select>
						</div>
						<label class="col-sm-1 control-label">Hours</label>
						<div class="col-sm-2">
							<select id="ddlReportHour" class="form-control">
								<option value="30">30 Min</option>
								<option value="45">45 Min</option>
								<option value="60" selected>1 Hr</option>
								<option value="75">1 Hr 15 Min</option>
								<option value="90">1 Hr 30 Min</option>
								<option value="105">1 Hr 45 Min</option>
								<option value="120">2 Hr</option>
								<option value="135">2 Hr 15 Min</option>
								<option value="150">2 Hr 30 Min</option>
								<option value="165">2 Hr 45 Min</option>
								<option value="180">3 Hr</option>
								<option value="195">3 Hr 15 Min</option>
								<option value="210">3 Hr 30 Min</option>
								<option value="225">3 Hr 45 Min</option>
								<option value="240">4 Hr</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Massage Details</label>
						<div class="col-sm-6">
							<textarea id="txtReportDetail" rows="3" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-offset-2 col-sm-2 control-label">Recommendation</label>
						<div class="col-sm-6">
							<textarea id="txtReportRecom" rows="3" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="button" id="btnAddReport" class="btn btn-primary btn-lg">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add Report
							</button>
						</div>
					</div>
					<br>
					<div class="form-group">
					
						<!-- Panel Report Container -->
						<div id="panelReportContainer" class="col-sm-offset-2 col-sm-8">
							<!-- Panel Item Template -->
							<!--
							<div id="panelItem" class="panel panel-warning">
								<div class="panel-heading">
									<div class="row">
										<div class="col-sm-6">
											<div class="panel-title">
												<b>Report on</b>
												<span id="lblItemDate">23/3/2016</span>
											</div>
										</div>
										<div class="col-sm-6 text-right">
											<button type="button" id="btnEditItem" class="btn btn-info btn-xs" name="">Edit</button>
											<button type="button" id="btnDeleteItem" class="btn btn-danger btn-xs" name="">Delete</button>
											<button type="button" id="btnUpdateItem" class="btn btn-warning btn-xs" name="">Update</button>
											<button type="button" id="btnCancelItem" class="btn btn-default btn-xs" name="index">Cancel</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-3 control-label">Therapist</label>
										<div class="col-sm-3">
											<select id="ddlItemTherapist" class="form-control" disabled>
												<option value="1">A</option>
												<option value="2">B</option>
												<option value="3">C</option>
												<option value="4">D</option>
												<option value="5">E</option>
												<option value="6">F</option>
											</select>
										</div>
										<label class="col-sm-1 control-label">Hours</label>
										<div class="col-sm-3">
											<select id="ddlItemHour" class="form-control" disabled>
												<option value="30">30 Min</option>
												<option value="45">45 Min</option>
												<option value="60" selected>1 Hr</option>
												<option value="75">1 Hr 15 Min</option>
												<option value="90">1 Hr 30 Min</option>
												<option value="105">1 Hr 45 Min</option>
												<option value="120">2 Hr</option>
												<option value="135">2 Hr 15 Min</option>
												<option value="150">2 Hr 30 Min</option>
												<option value="165">2 Hr 45 Min</option>
												<option value="180">3 Hr</option>
												<option value="195">3 Hr 15 Min</option>
												<option value="210">3 Hr 30 Min</option>
												<option value="225">3 Hr 45 Min</option>
												<option value="240">4 Hr</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Massage Details</label>
										<div class="col-sm-9">
											<textarea id="txtItemDetail" rows="2" class="form-control" readonly></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Recommendation</label>
										<div class="col-sm-9">
											<textarea id="txtItemRecom" rows="2" class="form-control" readonly></textarea>
										</div>
									</div>
								</div>
								<div class="panel-footer">
									<small>
										<b>Created by:</b>
										<span id="lblItemCreateUser">Default</span>
										<b>Created on:</b>
										<span id="lblItemCreateDateTime">23/3/2016 13:33</span>
										<b>Updated by:</b>
										<span id="lblItemUpdateUser">Default</span>
										<b>Updated on:</b>
										<span id="lblItemUpdateDateTime">23/3/2016 15:33</span>
									</small>
								</div>
							</div>
							-->
							<!-- /Panel Item Template -->
						</div> <!-- /#panelReportContainer -->
					</div> <!-- /form-group > .panel -->
				</form> <!-- /form-horizontal -->
			</div> <!-- div/container -->
		</div> <!-- /content -->
		
<!-- 		<div id="popupPrintReceipt" class="popup-panel popup-hide-element"> -->
<!-- 			<table class="table-print-receipt"> -->
<!-- 				<tr> -->
<!-- 					<th>Receipt Date </th> -->
<!-- 					<td> -->
<!-- 						<input type="text" id="txtReceiptDate" class="form-control"> -->
<!-- 					</td> -->
<!-- 				</tr> -->
<!-- 				<tr> -->
<!-- 					<th>Receipt Value </th> -->
<!-- 					<td> -->
<!-- 						<input type="text" id="txtReceiptValue" class="form-control"> -->
<!-- 					</td> -->
<!-- 				</tr> -->
<!-- 			</table> -->
<!-- 			<button type="button" id="btnPrintReceipt" class="btn btn-success btn-lg">Print Receipt</button> -->
<!-- 			<button type="button" id="btnCancelPrintReceipt" class="btn btn-default btn-lg">Cancel</button> -->
<!-- 		</div> -->
		
		<!-- Modal -->
		<div class="modal fade" id="popupPrintReceipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Receipt Details</h4>
						<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
					</div>
					<div class="modal-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Receipt Date</label>
								<div class="col-md-6">
									<input type="text" id="txtReceiptDate" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Receipt Value</label>
								<div class="col-md-6">
									<input type="text" id="txtReceiptValue" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-offset-1 col-sm-3 control-label">Provider No</label>
								<div class="col-md-6">
									<select id="ddlProvider" class="form-control">
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnPrintReceipt" class="btn btn-success btn-lg">
							<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
							Print Receipt
						</button>
						<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="footer">
		</div> <!-- footer -->
    </body>
</html>








