<!DOCTYPE html>
<?php
	require_once ('php/process_superadminpanel.php');
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

		<title>Pulse - Super Admin Panel</title>
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
						<li><a href="v_profile.php">Profile</a></li>
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
						<?php 
							if ($permission == 2) {
								echo "<li><a href=v_servergroups.php>Server Group Admin</a></li>";
							}
						?>
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
					<ul class="nav nav-sidebar">
						<li class="active"><a href="v_superadminpanel.php"><strong>Super Administration Panel</strong></a></li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h1 class="page-header"><span class="redtext">Pulse</span> - Super Administration Panel</h1>
					<h2 class="sub-header">Administration Functions</h2>
					<p>The Super Administration Panel is only accessible to accounts with 'Super Admin' status. </br> This panel allows the administration of user accounts for the Pulse application and Web Panel.</p>
				</div>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-5">
			<!-- PHP Handling of Error/Success messages and pop-ups -->
				<?php if(isset($_GET['adderror'])) { ?>
					<div class="alert alert-danger alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Error creating user, email address is already in use") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['addsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("User successfully created") ?></strong>
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
			</div>
			<form id="adduserform" method="post" action="#" autocomplete="on">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h3>Create a New User</h3>
				<p>The following form enables new users to be added to Pulse. The owner of the new account will be emailed to let them know of their account creation. </br> Please note, attemtping to create an account with a duplicate email address will fail.</p>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-user"></span>
							</span>
							<input type="text" class="form-control highlightred" placeholder="First Name" name="firstname" maxlength="50" id="firstname" required>
						</div>
					</div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-user"></span>
							</span>
							<input type="text" class="form-control highlightred" placeholder="Last Name" name="lastname" maxlength="50" id="lastname" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-envelope"></span>
							</span>
							<input type="email" class="form-control highlightred" placeholder="Email" name="email" maxlength="50" id="email" required>
						</div>
					</div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group" id="passwordinput">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-lock"></span>
							</span>
							<input type="password" class="form-control highlightred" placeholder="Password" name="password" maxlength="20" id="password" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-earphone"></span>
							</span>
							<input type="text" class="form-control highlightred" placeholder="Mobile" name="mobile" maxlength="13" id="mobile">
						</div>
					</div>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-exclamation-sign"></span>
							</span>
							<select class="form-control highlightred" id="privilege" name="privilege">
								<option value="2">Super Admin</option>
								<option value="1">Administrator</option>
							</select>
						</div>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="adduserbutton" class="btn buttonred highlightred" name="adduserbutton" type="submit">Add new user</button>
					</div>
				</div>
			</form>
			<form id="removeuserform" method="post" autocomplete="on" onsubmit="return confirmSubmit(this)">
				<input type="hidden" name="DUForm"/>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h3>Remove an Existing User</h3>
				<p>To delete users that no longer require access to Pulse, simply select them from the list and confirm removal. </br> Please note, this change is permanent and the owner of the account will be emailed to inform them. </br> This email address can then be added under a new account.</p>
					<div class="input-group col-md3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-user"></span>
							</span>
							<select class="form-control highlightred" id="selectuser" name="selectuser">
								<?php foreach($usersArray as $user) {
									if ($user['email'] != $email) {
										echo "<option value=" . $user['id'] . ">" . $user['first_name']. " " . $user['last_name'] . " - " . $user['email'] ."</option>";
									}
								} ?>
							</select>
						</div>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="removeuserbutton" class="btn buttonred highlightred" name="removeuserbutton" type="submit">Remove User</button>
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
			bootbox.confirm("Are you sure you want to remove this user?", function(result) {
				if (result) {
					f.submit();
				}
			});
			return false;
		}
		</script>
	</body>
</html>
