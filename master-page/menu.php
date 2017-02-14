<script type="text/javascript">
	$(document).ready(function(){
		$('#btnLogOut').click(function(){
			main_log_out();
		});
	});
</script>

<div id="menu">
	<nav class="navbar navbar-default navbar-static-top">
  		<div class="container">
  			<!-- Brand and toggle get grouped for better mobile display -->
  			<div class="navbar-header">
      			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
      			</button>
				<a class="navbar-brand" href="#">[Menu]</a>
    		</div>
    				
    		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      			<ul class="nav navbar-nav">
      				<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'client') { echo 'class="dropdown active"'; } else { echo 'class="dropdown"'; } }?>>
        				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Client <span class="caret"></span></a>
        				<ul class="dropdown-menu">
            				<li><a href="../client/client-add.php">Add Client</a></li>
            				<li><a href="../client/client-search.php">Search Client</a></li>
            			</ul>
        			</li>
      				<!--
      				<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'client-add') { echo 'class="active"'; } }?>>
      					<a href="../client/client-add.php">Add Client</a>
      				</li>
        			<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'client-search') { echo 'class="active"'; } }?>>
        				<a href="../client/client-search.php">Search Client</a>
        			</li>
        			-->
        			<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'daily-record') { echo 'class="active"'; } }?>>
        				<a href="../daily-record/daily-record.php">Daily Record</a>
        			</li>
        			<?php if (Authentication::isAdmin()) { ?>
	        			<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'therapist-manage') { echo 'class="active"'; } }?>>
	        				<a href="../therapist/therapist-manage.php">Manage Therapist</a>
	        			</li>
        			<?php } ?>
        			<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'report') { echo 'class="dropdown active"'; } else { echo 'class="dropdown"'; } }?>>
        				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
        				<ul class="dropdown-menu">
            				<li><a href="../report/client_contacts.php">Client Contacts</a></li>
            			</ul>
        			</li>
      			</ul>
      			<ul class="nav navbar-nav navbar-right">
      				<li <?php if (isset($_GET['page'])) { if ($_GET['page'] == 'change-password') { echo 'class="active"'; } }?>>
      					<a href="../authentication/change-password.php">Change Password</a>
      				</li>
      				<li>
      					<a id="btnLogOut" href="#" class="log-out">[Log Out]</a>
      				</li>
      			</ul>
      		</div> <!-- /.navbar-collapse -->
  		</div> <!-- /.container-fluid -->
  	</nav> <!-- /nav.navbar -->
</div> <!-- /menu -->






