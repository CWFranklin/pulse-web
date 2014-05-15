<!DOCTYPE html>
<?php
	require_once ('php/process_dashboard.php');
?>
<!-- General overview page for Pulse server monitoring application  -->

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta content="" name="description">
		<meta content="" name="author">
		<link href="img/favicon.ico" rel="shortcut icon">

		<title>Pulse - General Overview</title>
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
						<li><a href="v_dashboard.php"><strong>Dashboard</strong></a></li>
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
						<li class="active"><a href="v_dashboard.php"><strong>Account Dashboard</strong></a></li>
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
					<h1 class="page-header"><span class="redtext">Pulse</span> - Server and OS Overview</h1>
					<p>The following page displays information about all servers and groups associated to this account.</br>
					Server OS count can be seen below, as well as a summary list of servers which can be clicked to view individual server statistics.</p>
					</br>
					<div class="row placeholders">
						<div class="col-xs-6 col-sm-4 placeholder height-310">
							<img src="img/winlogo.png" class="img-responsive" alt="Windows logo">
							<h4>Windows</h4>
							<p class="winNum overlayNum">0</p>
						</div>
						<div class="col-xs-6 col-sm-4 placeholder height-310">
							<img src="img/linuxlogo2.png" class="img-responsive" alt="Tux logo">
							<h4>Linux</h4>
							<p class="linNum overlayNum">0</p>
						</div>
						<div class="col-xs-6 col-sm-4 placeholder height-310">
							<img src="img/applelogo.png" class="img-responsive" alt="Apple logo">
							<h4>Apple</h4>
							<p class="macNum overlayNum">0</p>
						</div>
					</div>
					<h2 class="sub-header">Server List Summary</h2>
					<p>Click on a server to view individual utilisation statistics for that server.</p>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>GUID</th>
									<th>Hostname</th>
									<th>Server Group</th>
									<th>Operating System</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="tableBody">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/docs.min.js"></script>
		
		<script>
			function make_base_auth(user, password) {
				var tok = user + ':' + password;
				var hash = btoa(tok);
				return "Basic " + hash;
			}
			
			function getServerGroup(serverGroups, id) {
				var name = "N/A";
				
				$.each(serverGroups, function( i, group) {
					if (id == group.id) {
						name = group.name;
					}
				});
				
				return name;
			}
			
			function setServerNums(p, term, servers) {
				var num = 0;
				
				$.each(servers, function(i, server) {
					if (server.os_name.toLowerCase().indexOf(term) >= 0) {
						num++;
					}
				});
				
				$(p).text(num);
			}
			
			$(document).ready(function() {
				$.ajax({
					url: "http://cadence-bu.cloudapp.net/servers",
					type: "GET",
					headers: { 'Authorization': make_base_auth("brandon@brandonscott.co.uk", "Cadenc3!") },
					success: function(data) {
						$.ajax({
							url: "http://cadence-bu.cloudapp.net/servergroups",
							type: "GET",
							headers: { 'Authorization': make_base_auth("brandon@brandonscott.co.uk", "Cadenc3!") },
							success: function(groups) {
								console.log(data);
								console.log(groups);
							
								$.each( data, function( i, server ) {
									var online = (server.online == 0 ? "Offline" : "Online");
									$('#tableBody').append( "<tr class=\"server\"> <td>" + server.id + "</td> <td>" + server.guid + "</td> <td>" + server.name + "</td> <td>" + getServerGroup(groups, server.servergroup_id) + "</td> <td>" + server.os_name + "</td><td class=\"" + online + "\">" + online + "</td> </tr>" );
								});
								
								setServerNums($('.winNum'), "windows", data);
								setServerNums($('.linNum'), "linux", data);
								setServerNums($('.macNum'), "os x", data);
							}
						});
					}
				});
				
				$('#tableBody').click( function(o) {
					var parent = o.target.parentNode;
					window.location.replace("v_server.php?serverid=" + parent.children[0]['textContent'] + "&serverguid=" + parent.children[1]['textContent']);
				});
			});
		</script>
	</body>
</html>
