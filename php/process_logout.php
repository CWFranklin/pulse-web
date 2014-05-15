<?php
	//PHP Processing of logging out users
	// process_logout.php
	
	session_start();
	
	$email = $_SESSION['email'];
	$identify = $_SERVER['REMOTE_ADDR'];
	$randString = hash('sha512', $identify);
	
	// Only allow the user to access this page if that have a session
	if(isset($_SESSION['email']) && (isset($_SESSION['userid'])) && ($_SESSION['loginstring'] == $randString)){
	
		// Close user session
		session_destroy();
		header('Location: ../v_login.php?logoutsuccess=1');
	
	} else {
		// If the user doesn't have a session open, redirect to login page and display an error
		header('Location: ../v_login.php?notloggedin=1');
	}
	
?>