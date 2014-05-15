<!DOCTYPE html>
<?php
	require_once ('php/process_profile.php');
?>
<!-- Super Admin Administration Panel page for Pulse server monitoring application  -->

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta content="" name="description">
		<meta content="" name="author">
		<link href="img/favicon.ico" rel="shortcut icon">

		<title>Pulse - Profile (<?php echo $email; ?>)</title>
		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		<!-- Just for debugging purposes. Don't actually copy this line! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="v_dashboard.php"><img id="pulsethumbnail" src="img/PulseLogo.png" alt="Pulse Logo" width="42" height="42"></a><a class="navbar-brand" href="v_dashboard.php"><span class="redtext">Pulse</span> - Web Dashboard</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a>Welcome, <?php echo $email; ?></a></li>
						<li><a href="v_dashboard.php">Dashboard</a></li>
						<li><a class="active" href="#"><strong>Profile</strong></a></li>
						<li><a href="php/process_logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li><a href="v_dashboard.php">Account Dashboard</a></li>
						<li><a href="v_servergroups.php">Server Group Admin</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li><a>Server Groups</a></li>
						<ul>
							<?php
								$groupNameNav = "";
								if(!empty($subsArray)){
									foreach($subsArray as $sub) {
										if($sub['servergroup_id'] == $sub['server_group']['id']){
											$groupNameNav = $sub['server_group']['name'];
										}
										echo "<li><a href=''>" . $groupNameNav . "</a></li>";
									}
								} else {
									echo "<li><a href='v_profile.php'>No Group Subscriptions</a></li>";
								}
							?>
						</ul>	
					</ul>
					<?php 
						if ($permission == 2) {
							echo "<ul class='nav nav-sidebar'><li><a href=v_superadminpanel.php>Super Admin Panel</a></li></ul>";
						}
					?>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h1 class="page-header"><span class="redtext">Pulse</span> - <?php echo $firstnamedisplay . " " . $lastnamedisplay . "'s"; ?> Profile</h1>
					<h2 class="sub-header">Account Administration</h2>
					<p>The following user profile page allows editing and updating of the user details, as well as deletion of the users account.</p>
				</div>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-5">
			<!-- PHP Handling of Error/Success messages and pop-ups -->
				<?php if(isset($_GET['updatesuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Details successfully updated!") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['updateerror'])) { ?>
					<div class="alert alert-danger alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("There was an error updating your details!") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['groupsassignsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Successfully Subscribed to Groups!") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['groupsunassignsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Subscription Successfully Removed!") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['updatepasssucc'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Password successfully updated!") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['updatepasserr'])) { ?>
					<div class="alert alert-danger alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("There was an error updating your password!") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['removeerror'])) { ?>
					<div class="alert alert-danger alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Error whilst removing user") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['removesuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("User successfully removed") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['updatesubs'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Notifications Successfully Updated") ?></strong>
					</div>
				<?php } ?>
			</div>
			<form id="edituderdetails" method="post" autocomplete="on" onsubmit="return confirmSubmit(this)">
				<input type="hidden" name="EUForm"/>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h3>Edit Details</h3>
				<p>If you would like to update your details, simply edit the current values below and save the changes.</p>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-user"></span>
							</span>
							<input type="text" class="form-control highlightred" value="<?php echo $userArray['first_name']?>" name="firstname" maxlength="50" id="firstname" required>
						</div>
					</div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-user"></span>
							</span>
							<input type="text" class="form-control highlightred" value="<?php echo $userArray['last_name']?>" name="lastname" maxlength="50" id="lastname" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-envelope"></span>
							</span>
							<input type="email" class="form-control highlightred" value="<?php echo $userArray['email']?>" name="email" maxlength="50" id="email" required>
						</div>
					</div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-earphone"></span>
							</span>
							<input type="text" class="form-control highlightred" value="<?php echo $userArray['mobile_number']?>" name="mobile" maxlength="13" id="mobile">
						</div>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="adduserbutton"class="btn buttonred highlightred" name="adduserbutton" type="submit">Save updated details</button>
					</div>
				</div>
			</form>
			<form id="subscribegroupsform" method="post" autocomplete="on">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Subscribe to Server Groups</h3>
					<p>Subscribe to server groups using the following form.</br>
					Only groups this account is not currently associated to will appear in the list.</p>
					<div class="table-responsive">
						<form method="POST" action="#">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Group Name</th>
										<th>Subscribe</th>
										<th>SMS Notifications</th>
										<th>Phone Call Notifications</th>
									</tr>
								</thead>
								<tbody>
								<!-- PHP Handling of table population -->
									<?php if(!empty($groupsArray)) {
										$count = 0;
										foreach ($groupsArray as $allSrv) {
											if (!empty($subsArray)) {
												$exists = false;
												
												foreach ($subsArray as $existSub) {
													if ($allSrv['id'] == $existSub['servergroup_id']) {
														$exists = true;
													}
												}
												if (!$exists) {
													$count++;
													echo "<tr><td>" . $allSrv['name'] . "</td><td><input name=". $allSrv['id'] .'|'." type='checkbox' value=\"1\" checked></td><td><input name=\"". $allSrv['id'] . "|text\"" ." type='checkbox' value=\"1\"checked></td><td><input name=\"". $allSrv['id'] . "|phone\"" ." type='checkbox' value=\"1\"checked></td></tr>";
												}
											} else {
												$count++;
												echo "<tr><td>" . $allSrv['name'] . "</td><td><input name=". $allSrv['id'] ." type='checkbox' value=\"1\" checked></td><td><input name=\"". $allSrv['id'] . "|text\"" ." type='checkbox' value=\"1\"checked></td><td><input name=\"". $allSrv['id'] . "|phone\"" ." type='checkbox' value=\"1\"checked></td></tr>";
											}
										}
										if ($count == 0) {
											echo "<tr><td>You are subscribed to all server groups.</td><td></td><td></td><td></td></tr>";
										}
									} else {
										echo "<tr><td>No Server Groups Exist!</td><td></td><td></td><td></td></tr>";
									} ?>
								</tbody>
							</table>
							<div class="input-group col-md-3 floatformelement">
								<div class="centeralign">
									<button id="subscribegroupsbutton" class="btn buttonred highlightred" name="subscribegroupsbutton" type="submit">Subscribe to Groups</button>
								</div>
							</div>
						</form>
						</br>
					</div>
				</div>
			</form>
			<form id="unsubscribegroupsform" method="post" autocomplete="on">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Unsubscribe From Server Groups</h3>
					<p>Unsubscribe from server groups using the following form.</br>
					The tickbox will be checked as default, as groups within this table are already assigned to this account. Uncheck the box and save the form to unsubscribe from any unticked servers.</p>
					<div class="table-responsive">
						<form method="POST" action="#">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Group Name</th>
										<th>Unsubscribe</th>
									</tr>
								</thead>
								<tbody>
								<!-- PHP Handling of table population -->
									<?php if(!empty($subsArray)) {
										foreach($subsArray as $sub) {
											echo "<tr><td>" . $sub['server_group']['name'] . "</td><td><input name=". $sub['id'] ." type='checkbox' value=\"1\"></td></tr>";
										}
									} else {
										echo "<tr><td>No Subscriptions Exist!</td><td></td><td></td></tr>";
									} ?>
								</tbody>
							</table>
							<div class="input-group col-md-3 floatformelement">
								<p>Save Group Assignment Changes</p>
								<div class="centeralign">
									<button id="unsubscribegroupsbutton" class="btn buttonred highlightred" name="unsubscribegroupsbutton" type="submit">Unsubscribe from Groups</button>
								</div>
							</div>
						</form>
						</br>
					</div>
				</div>
			</form>
			<form id="changepassword" method="post" autocomplete="on" onsubmit="return confirmSubmit(this)">
				<input type="hidden" name="CPForm"/>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Change your password</h3>
					<p>If you would like to update your password, simply provide your old password and then your new one whilst confirming this with the 'Confirm New Password' input field.</p>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-lock"></span>
							</span>
							<input type="password" class="form-control highlightred" placeholder="Old Password" name="oldpassword" maxlength="50" id="oldpassword" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-lock"></span>
							</span>
							<input type="password" class="form-control highlightred" placeholder="New Password" name="newpassword" maxlength="50" id="newpassword" required>
						</div>
					</div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-lock"></span>
							</span>
							<input type="password" class="form-control highlightred" placeholder="Confirm New Password" name="newpasswordconfirm" maxlength="50" id="newpasswordconfirm" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="updatepasswordbutton"class="btn buttonred highlightred" name="updatepasswordbutton" type="submit">Save updated Password</button>
					</div>
				</div>
			</form>
			<form id="editnotificationform" method="post" autocomplete="on">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Edit Notification Settings</h3>
					<p>Edit the following settings to receive or disable both sms and phone call notifications, per group that this account is subscribed to.</p>
					<div class="table-responsive">
						<form method="POST" action="#">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Group Name</th>
										<th>SMS</th>
										<th>Phone</th>
									</tr>
								</thead>
								<tbody>
								<!-- PHP Handling of table population -->
									<?php 
										if(!empty($subsArray)) {
											foreach($subsArray as $sub) {
												$textCheck = "";
												$phoneCheck = "";
												
												if ($sub['text'] == 1){
													$textCheck = "checked";
												}
												
												if ($sub['phonecall'] == 1){
													$phoneCheck = "checked";
												}
												
												echo "<tr>
														<td>" . $sub['server_group']['name'] . "</td>
														
														<td><input name=". $sub['id'] . "|text" ." type='checkbox' value=\"1\" " . $textCheck . "></td>
														<td><input name=". $sub['id'] . "|phone" ." type='checkbox' value=\"1\" " . $phoneCheck . "></td>
														<input type=\"hidden\" value=\"1\" name=\"" . $sub['id'] . "|exists\"></input>
													</tr>";
											}
										} else {
											echo "<tr><td>Not subscribed to any groups!</td><td></td><td></td></tr>";
										}
									?>
								</tbody>
							</table>
							<div class="input-group col-md-3 floatformelement">
								<p>Save Notification Settings</p>
								<div class="clearfloat"></div>
								<div class="centeralign">
									<button id="savenotificationsbutton" class="btn buttonred highlightred" name="savenotificationsbutton" type="submit">Save Notifications</button>
								</div>
							</div>
						</form>
						</br>
					</div>
				</div>
			</form>
			<form id="deleteselfform" method="post" autocomplete="on" onsubmit="return confirmSubmit(this)">
				<input type="hidden" name="DAForm"/>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Delete My Account</h3>
					<p>If you would like to delete your account, click the button below. </br> Please bear in mind, this will be permanent unless the IT Manager or a Super Admin re-creates your account.</p>
					<div class="input-group col-md3 floatformelement">
						<div class="centeralign">
							<form>
								<input type="hidden" name="DAForm" />
								<button id="deleteaccountbutton" class="btn buttonred highlightred" name="deleteaccountbutton" type="submit">Delete My Account</button>
							</form>
						</div>
					</div>
				</div>
			</form>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/docs.min.js"></script>
		<script src="js/bootbox.min.js"></script>
		<script type="text/javascript">
		function confirmSubmit(f) {
			bootbox.confirm("Are you sure you want to complete this action?", function(result) {
				if (result) {
					f.submit();
				}
			});
			return false;
		}
		</script>
	</body>
</html>