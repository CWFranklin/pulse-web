<!DOCTYPE html>
<?php
	require_once ('php/process_servergroups.php');
?>
<!-- Server Groups page for Pulse server monitoring application  -->

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta content="" name="description">
		<meta content="" name="author">
		<link href="img/favicon.ico" rel="shortcut icon">

		<title>Pulse - Server Groups</title>
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
						<li class="active"><a href="v_servergroups.php"><strong>Server Group Admin</strong></a></li>
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
					<h1 class="page-header"><span class="redtext">Pulse</span> - Server Group Admin</h1>
					<h2 class="sub-header">Server Group Administration</h2>
					<p>The following admin panel allows administration functions to be carried out on server groups. New groups can be created, edited and deleted. </br> Servers that are currently unassigned can be assigned to groups, as well as those that are already assigned.
					</br> The current user can also edit their current server group subscriptions, assigning or unassigning themselves from groups of their choice.</p>
				</div>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-5">
			<!-- PHP Handling of Error/Success messages and pop-ups -->
				<?php if(isset($_GET['createsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Group created successfully") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['unassignedsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Unassigned Servers Successfully Assigned") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['assignedsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Server Groups Successfully Changed") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['removesuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo("Group successfully removed.") ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['grouperror'])) { ?>
					<div class="alert alert-danger alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo "There is already a group with that name, please choose another." ?></strong>
					</div>
				<?php } ?>
				<?php if(isset($_GET['editgroupsuccess'])) { ?>
					<div class="alert alert-success alert-dismissable alertoverlay centralalignment" style="width: 30%; margin-bottom:20px;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong><?php echo "Server name successfully updated." ?></strong>
					</div>
				<?php } ?>
			</div>
			<form id="addgroupform" method="post" action="#" autocomplete="on">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Create a New Server Group</h3>
					<p>Create new server groups here. To assign servers to a newly created group, use the controls below.</p>
					<div class="input-group col-md-3 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-th-list"></span>
							</span>
							<input type="text" class="form-control highlightred" placeholder="Group Name" name="newgroupname" maxlength="50" id="newgroupname" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="creategroupbutton"class="btn buttonred highlightred" name="creategroupbutton" type="submit">Create new Server Group</button>
					</div>
				</div>
			</form>
			</br>
			<form id="editgroupform" method="post" action="#" autocomplete="on">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h3>Edit Existing Server Group Name</h3>
					<p>To edit a server group name, use the controls below.</p>
					<div class="input-group col-md-5 floatformelement">
						<div class="input-group">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-th-list"></span>
							</span>
							<select class="form-control highlightred" id="selecteditgroup" name="selecteditgroup">
								<?php foreach($groupsArray as $group) {
										echo "<option value=" . $group['id'] . ">" . $group['name'] . "</option>";
									} ?>
							</select>								
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-th-list"></span>
							</span>
							<input type="text" class="form-control highlightred" placeholder="Rename to.." name="editgroupname" maxlength="50" id="editgroupname" required>
						</div>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="editgroupbutton"class="btn buttonred highlightred" name="editgroupbutton" type="submit">Edit Server Group Name</button>
					</div>
				</div>
			</form>
			</br>
			<form id="removegroupform" method="post" autocomplete="on" onsubmit="return confirmSubmit(this)">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<input type="hidden" name="RGForm"/>
					<h3>Remove Existing Server Group</h3>
					<p>Any servers that are still associated to a group when it is removed, will be moved into 'Unassigned Servers'.</p>
					<div class="input-group col-md-3 floatformelement">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-th-list"></span>
						</span>
						<select class="form-control highlightred" id="selectgroup" name="selectgroup">
							<?php foreach($groupsArray as $group) {
									echo "<option value=" . $group['id'] . ">" . $group['name'] . "</option>";
								} ?>
						</select>
					</div>
					<div class="clearfloat"></div>
					</br>
					<div class="centeralign">
						<button id="removegroupbutton"class="btn buttonred highlightred" name="removegroupbutton" type="submit">Remove Existing Server Group</button>
					</div>
				</div>
			</form>
			</br>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h3 class="sub-header">Assign Unassigned Servers to Groups</h2>
				<p>The following table contains all servers that are currently unassigned to a server group. </br>The servers you would like to assign can be selected using the checkbox, a new group can then be selected and saved as the new server group for the selected servers.</p>
				Upon being assigned, they will no longer appear in this table.</p>
				<div class="table-responsive">
					<form method="POST" action="#">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>GUID</th>
									<th>Hostname</th>
									<th>Status</th>
									<th>Select</th>
								</tr>
							</thead>
							<tbody>
							<!-- PHP Handling of table population -->
								<?php if(!empty($unassignedServersArray)) {
									foreach($unassignedServersArray as $server) {
										$online = ($server['online'] == 0 ? "Offline" : "Online");
										echo "<tr><td>" . $server['guid'] . "</td><td>" . $server['name'] . "</td><td class=$online>" . $online . "</td><td><input name=". $server['id'] ." type='checkbox'></td></tr>";
									}
								} else {
									echo "<tr><td>No servers are unassigned!</td><td></td><td></td><td></td></tr>";
								} ?>
							</tbody>
						</table>
						<div class="input-group col-md-3 floatformelement">
							<p>Assign selected servers to group:</p>
							<select class="form-control highlightred" id="selectgroup" name="selectgroup">
								<?php 
									foreach($groupsArray as $group) {
										echo "<option value=" . $group['id'] . ">" . $group['name'] . "</option>";
								} ?>
							</select>
							<div class="clearfloat"></div>
							</br>
							<div class="centeralign">
								<button id="saveunassignedbutton" class="btn buttonred highlightred" name="saveunassignedbutton" type="submit">Save Unassigned Groups</button>
							</div>
						</div>
					</form>
					</br>
				</div>
			</div>
			<div class="clearfloat"></div>
			</br>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h3 class="sub-header">Change Existing Server Groups</h2>
				<p>The following table contains all servers that are assigned to groups. These servers can be reassigned by ticking the checkbox for each server you would like to reassign.</br>A new group can then be selected and saved as the new server group for the selected servers.</p>
				<div class="table-responsive">
					<form method="POST" action="#">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>GUID</th>
									<th>Hostname</th>
									<th>Status</th>
									<th>Current Server Group</th>
									<th>Select</th>
								</tr>
							</thead>
							<tbody>
							<!-- PHP Handling of table population -->
							<?php
								if(!empty($assignedServersArray)) {
									foreach($assignedServersArray as $serverAss) {
										$online = ($serverAss['online'] == 0 ? "Offline" : "Online");
										$serverGroupName = "";
										foreach($groupsArray as $groups){
											if ($groups['id'] == $serverAss['servergroup_id'])
											{
												$serverGroupName = $groups['name'];
											}
										}
										echo "<tr><td>" . $serverAss['guid'] . "</td><td>" . $serverAss['name'] . "</td><td class=$online>" . $online . "</td><td>" . $serverGroupName . "<td><input name=". $serverAss['id'] ." type='checkbox'></td></tr>";
									}
								} else {
									echo "<tr><td>There are no servers to display.</td><td></td><td></td><td></td></tr>";
								} ?>
							</tbody>
						</table>
						<div class="input-group col-md-3 floatformelement">
							<p>Assign selected servers to group:</p>
							<select class="form-control" id="selectexistinggroup" name="selectexistinggroup">
								<?php 
								foreach($groupsArray as $group) {
									echo "<option value=" . $group['id'] . ">" . $group['name'] . "</option>";
								} ?>
							</select>
							<div class="clearfloat"></div>
							</br>
							<div class="centeralign">
								<button id="saveassignedbutton" class="btn buttonred highlightred" name="saveassignedbutton" type="submit">Save Already Assigned Groups</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootbox.min.js"></script>
		<script type="text/javascript">
		function confirmSubmit(f) {
			bootbox.confirm("Are you sure you want to remove this group?", function(result) {
				if (result) {
					f.submit();
				}
			});
			return false;
		}
		</script>
	</body>
</html>
