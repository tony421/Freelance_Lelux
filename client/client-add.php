<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    
	    <title>Client - Add new client</title>
	    
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
				<div class="title-text">~:: New Client ::~</div>
			</div>
			<div class="container">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Health Fund</label>
						<div class="col-sm-4">
							<select class="form-control">
								<option>1</option>
								<option>2</option>
								<option>3</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Membership No.</label>
						<div class="col-sm-3">
							<input type="text" class="form-control">
						</div>
						<label class="col-sm-2 control-label">Patient ID</label>
						<div class="col-sm-3">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">First Name</label>
						<div class="col-sm-2">
							<input type="text" class="form-control">
						</div>
						<label class="col-sm-2 control-label">Last Name</label>
						<div class="col-sm-2">
							<input type="text" class="form-control">
						</div>
						<label class="col-sm-1 control-label">Gender</label>
						<div class="col-sm-3">
							<label class="radio-inline">
								<input type="radio" name="gender" value="1"> Male
							</label>
							<label class="radio-inline">
								<input type="radio" name="gender" value="2"> Female
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Address</label>
						<div class="col-sm-5">
							<input type="text" class="form-control">
						</div>
						<label class="col-sm-2 control-label">Postcode</label>
						<div class="col-sm-2">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-5">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Contact No.</label>
						<div class="col-sm-5">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Date of Birth</label>
						<div class="col-sm-5">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Occupation</label>
						<div class="col-sm-5">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Finding us</label>
						<div class="col-sm-10">
							<label class="checkbox-inline">
								<input type="checkbox" value="1"> True Local
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" value="2"> Google 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" value="3"> Passing By 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" value="4"> Word of mouth 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" value="5"> Flyer 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" value="6"> Facebook 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" value="7"> Gift Voucher 
							</label>						
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-5">
							<label class="checkbox-inline">
								<input type="checkbox" value="8"> Referred By 
								
							</label>
							<input type="text">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Sports/Activities</label>
						<div class="col-sm-5">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Conditions Apply</label>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="1"> Stroke
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="2"> Cancer
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="3"> Isomnia
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="4"> Headache
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="5"> Heart Conditions
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="6"> Pain/Stiffness
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="7"> High/Low Blood Pressure
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="8"> Allergies/Asthma
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="9"> Broken/Dislocated Bones
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="10"> Contagious/Infectious Diseases
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="11"> Pregnancy/Breastfeeding
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
						<div class="col-sm-4">
							<div class="input-group">
							    <span class="input-group-addon">
							    	<label class="checkbox-inline" style="padding-top: 0;">
							    		<input type="checkbox" value="12"> Sore Back
						    		</label>
							    </span>
							    <input type="text" class="form-control">
						    </div><!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Other Conditions or Recent injuries</label>
						<div class="col-sm-6">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Emergency Contact</label>
						<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">Name</span>
								<input type="text" class="form-control">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">Phone</span>
								<input type="text" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-5 col-sm-7">
							<button type="button" class="btn btn-primary btn-lg">Add Client</button>
						</div>
					</div>
				</form> <!-- /form-horizontal -->
			</div> <!-- div/container -->
		</div> <!-- /content -->
		
		<div id="footer">
		</div> <!-- footer -->
	</body>
</html>