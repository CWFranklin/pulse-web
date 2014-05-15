<!DOCTYPE html>
<?php 
	require_once('php/process_login.php');
?>
<!-- Login page for Pulse server monitoring application  -->

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta content="" name="description">
		<meta content="" name="author">
		<link href="img/favicon.ico" rel="shortcut icon">

		<title>Pulse - Login</title>
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
		<div class="container">
			<div id="logo" class="col-md-6 col-md-offset-3">
				<div id="PulseSVG" class="centralalignment"></div>
				<div id="PulseHeader" class="centertext">Pulse</div>
				<div id="PulseText" class="centertext">A server health monitoring app for<br>Android OS</div>
			</div>
			<form class="form-signin" method="POST" action="#">
				<h2 class="form-signin-heading">Please sign in</h2>
				<input autofocus="" class="form-control highlightred" placeholder="Email address" required="" type="email" id="email" name="email"> 
				<input class="form-control highlightred" placeholder="Password" required="" type="password" id="password" name="password">
				<button id="loginbutton" name="loginbutton" class="btn btn-lg btn-primary btn-block buttonred" type="submit">Login</button>
			</form>
			<!-- PHP Handling of Error/Success messages and pop-ups -->
			<?php if(isset($_GET['loginerror'])) { ?>
				<div class="alert alert-danger alert-dismissable centralalignment" style="width: 300px;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong><?php echo "Email or password incorrect"?></strong>
				</div>
			<?php } ?> 
			<?php if(isset($_GET['logoutsuccess'])) { ?>
				<div class="alert alert-success alert-dismissable centralalignment" style="width: 300px;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong><?php echo "You have successfully logged out"?></strong>
				</div>
			<?php } ?> 
			<?php if(isset($_GET['notloggedin'])) { ?>
				<div class="alert alert-danger alert-dismissable centralalignment" style="width: 300px;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong><?php echo "You must be logged in to view that page"?></strong>
				</div>
			<?php } ?> 
			<?php if(isset($_GET['accountdeletion'])) { ?>
				<div class="alert alert-danger alert-dismissable centralalignment" style="width: 300px;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong><?php echo "Account successfully deleted"?></strong>
				</div>
			<?php } ?>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/jquery.lazylinepainter-1.4.1.min.js"></script>
		<script src="js/raphael.2.1.0.min.js"></script>
		<script src="js/pulselogo.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>