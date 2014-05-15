<!DOCTYPE html>
<?php
	require_once ('php/process_server.php');
?>
<!-- Individual Server page for Pulse server monitoring application  -->

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta content="" name="description">
		<meta content="" name="author">
		<link href="img/favicon.ico" rel="shortcut icon">

		<title>Pulse - Server Statistics</title>
		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		<link href="css/jquery.gridster.css" rel="stylesheet">
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
					<h1 id="pageTitle" class="page-header">Server Overview</h1>
					<h2 class="sub-header"> Current Server Utilisations</h2>
					<p>Current server resource utilisations can be seen below. These are real-time statistics that are updated every 5 seconds.</br>
					The graphical elements can be dragged and re-arranged on screen.</p>
					<h2>Server Status: <?php $online = ($serverDetails->online == 0 ? "Offline" : "Online"); echo "<span class=$online>" . $online . "</span>";	?></h2>
					<?php if($serverDetails->online == 0) {echo "<p>As this server is currently offline, the data shown are the utilisations the moment the server went offline.</p>";}?>
					<div class="gridster">
						<ul>
							<li id="base" class="" data-row="1" data-col="7" data-sizex="2" data-sizey="1"></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/pubnub.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.gridster.js"></script>
		<script src="js/raphael.2.1.0.min.js"></script>
		<script src="js/justgage.1.0.1.min.js"></script>
		<script>
			function make_base_auth(user, password) {
				var tok = user + ':' + password;
				var hash = btoa(tok);
				return "Basic " + hash;
			}
			
			$(document).ready(function(){ //DOM Ready
				var serverId = <?php echo $serverid ?>;
				var serverGuid = "<?php echo $serverguid ?>";
				
				$(".gridster ul").gridster({
					widget_margins: [10, 10],
					widget_base_dimensions: [150, 200]
				});
				
				$.ajax({
					url: "http://cadence-bu.cloudapp.net/servers/" + serverId,
					type: "GET",
					headers: { 'Authorization': make_base_auth("brandon@brandonscott.co.uk", "Cadenc3!") },
					success: function(data) {
						$('#pageTitle').text(data.name + ": Current Usage");
					}
				}); 
				
				var gridster = $('.gridster ul').gridster().data('gridster');
				
				gridster.add_widget('<li class="gageScheme"><div id="cpu" class="200x160px"></div></li>', 2, 1, 1, 1);
				gridster.add_widget('<li class="gageScheme"><div id="ram" class="200x160px"></div></li>', 2, 1, 3, 1);
				gridster.add_widget('<li class="gageScheme"><div id="disk" class="200x160px"></div></li>', 2, 1, 5, 1);
				gridster.add_widget('<li class="gageScheme"><div id="info" class="200x160px"></div></li>', 5, 2, 1, 2);
				
				gridster.remove_widget( $('#base') );
				
				var cpu = new JustGage({
					id: "cpu",
					value: 0,
					min: 0,
					max: 100,
					title: "Current CPU Usage %"
				});
				
				var ram = new JustGage({
					id: "ram",
					value: 0,
					min: 0,
					max: 100,
					title: "Current RAM Usage %"
				});
				
				var disk = new JustGage({
					id: "disk",
					value: 0,
					min: 0,
					max: 100,
					title: "Current Disk Usage %"
				});
				
				$.ajax({
					url: "http://cadence-bu.cloudapp.net/servers/" + serverId + "/pulses/latest",
					type: "GET",
					headers: { 'Authorization': make_base_auth("brandon@brandonscott.co.uk", "Cadenc3!") },
					success: function(data) {
						console.log(data);
						cpu.refresh(Math.round(data.cpu_usage))
						ram.refresh(Math.round(data.ram_usage));
						disk.refresh(Math.round(data.disk_usage));
					}
				});
				
				var pubnub = PUBNUB.init({
					publish_key: 'pub-c-18bc7bd1-2981-4cc4-9c4e-234d25519d36',
					subscribe_key: 'sub-c-5782df52-d147-11e3-93dd-02ee2ddab7fe'
				});
				
				pubnub.subscribe({
					channel: 'pulses-' + serverId,
					message: function(m){
						cpu.refresh(Math.round(m.cpu_usage));
						ram.refresh(Math.round(m.ram_usage));
						disk.refresh(Math.round(m.disk_usage));
					}
				});
				
				$.ajax({
					url: "http://cadence-bu.cloudapp.net/servers/" + serverId,
					type: "GET",
					headers: { 'Authorization': make_base_auth("brandon@brandonscott.co.uk", "Cadenc3!") },
					success: function(data) {					
						$('#info').append("<h3 class=\"padding-left-20\"> Operating System: " + data.os_name + "</h3>");
						$('#info').append("<h3 class=\"padding-left-20\"> OS Version: " + data.os_version + "</h3>");
						$('#info').append("<h3 class=\"padding-left-20\"> GUID: " + data.guid + "</h3>");
						$('#info').append("<h3 class=\"padding-left-20\"> Max CPU Speed: " + data.cpu_speed +  " MHz" + "</h3>");
						$('#info').append("<h3 class=\"padding-left-20\"> Total RAM: " + data.available_ram + " MB" + "</h3>");
						$('#info').append("<h3 class=\"padding-left-20\"> OS Disk Size: " + data.available_disk + " MB" + "</h3>");
					}
				});
			});
		</script>
	</body>
</html>