<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Client - Add new client</title>
	    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	    
	    <!-- Bootstrap -->
	    <link rel="stylesheet" href="../bootstrap-3.3.6/css/bootstrap.min.css">
	    <script type="text/javascript" src="../bootstrap-3.3.6/js/bootstrap.min.js"></script>
	    
	    <link rel="stylesheet" href="../css/main-id.css">
	    <link rel="stylesheet" href="../css/main-class.css">
	    <link rel="stylesheet" href="../css/messagebox.css">
	    
	    <script type="text/javascript" src="../js/messagebox.js"></script>
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script type="text/javascript" src="client-report.js"></script>
	    <script type="text/javascript" src="client-variable.js"></script>
	    
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
        					<li>
        						<a href="../client/client-search.php">Search Client</a>
        					</li>
      					</ul>
      				</div> <!-- /.navbar-collapse -->
  				</div> <!-- /.container-fluid -->
  			</nav> <!-- /nav.navbar -->
		</div> <!-- /menu -->
		
		<div id="content">
			<div class="title-container">
				<div class="title-text">~:: Client Report ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Health Fund</label>
						<div class="col-sm-4">
							<select id="ddlHealthFund" class="form-control" disabled>
								<option value="1">ACA Health</option>
								<option value="2">AHM Health Insurance</option>
								<option value="3">Australian Unity Health Ltd</option>
								<option value="4">Budget Direct</option>
								<option value="5">Bupa Australia</option>
								<option value="6">CBHS Health Fund Limited</option>
								<option value="7">CUA Health Limited</option>
								<option value="8">Defence Health Limited</option>
								<option value="9">Frank Health insurance</option>
								<option value="10">GMF Health</option>
								<option value="11">GMHBA Limited</option>
								<option value="12">Grand United Health</option>
								<option value="13">HBF Health Fund</option>
								<option value="14">Health Care Insurance Ltd</option>
								<option value="15">Health Insurance Fund of Australia Ltd</option>
								<option value="16">Health Partners</option>
								<option value="17">Health.com.au</option>
								<option value="18">Medibank Private Ltd</option>
								<option value="19">onemedifund</option>
								<option value="20">Navy Health</option>
								<option value="21">NIB Health Funds Ltd</option>
								<option value="22">Peoplecare Health Insurance</option>
								<option value="23">Phoenix Health Fund Ltd</option>
								<option value="24">Queensland Country Health Fund Ltd</option>
								<option value="25">Railway and Transport Health Fund Ltd</option>
								<option value="26">Reserve Bank Health Society</option>
								<option value="27">St Lukes</option>
								<option value="28">The Doctors Health Fund</option>
								<option value="29">Teachers Health Fund</option>
								<option value="30">Transport Health Pty Ltd</option>
								<option value="31">TUH</option>
								<option value="32">Uni Health</option>
								<option value="33">Westfund Ltd</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Membership No.</label>
						<div class="col-sm-3">
							<input type="text" id="txtMemNo" class="form-control" disabled>
						</div>
						<label class="col-sm-2 control-label">Patient ID</label>
						<div class="col-sm-3">
							<input type="text" id="txtPatientID" class="form-control" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">First Name</label>
						<div class="col-sm-2">
							<input type="text" id="txtFirstName" class="form-control" readonly="readonly">
						</div>
						<label class="col-sm-2 control-label">Last Name</label>
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
								<span class="input-group-addon">Phone</span>
								<input type="text" id="txtEmerConNo" class="form-control" readonly="readonly">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div id="footer">
		</div> <!-- footer -->
    </body>
</html>