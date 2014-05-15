<?php
	// PHP Processing of the dashboard page and dashboard using CURL to interface with the Cadence API
	// process_dashboard.php
	
	require_once('library.php');

	session_start();
	
	$identify = $_SERVER['REMOTE_ADDR'];
	$randString = hash('sha512', $identify);

	if(isset($_SESSION['email']) && (isset($_SESSION['userid'])) && ($_SESSION['loginstring'] == $randString)){

		$email = $_SESSION['email'];
		$userid = $_SESSION['userid'];
		$permission = $_SESSION['permissionlevel'];
		
		$ch = curl_init(API_URL . 'users/' . $userid . '/subscriptions');                                                                    
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");   
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword); 		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json'
		));
		
		$result = curl_exec($ch);
		curl_close($ch);

		$subsArray = json_decode($result, true);
		
	} else {
		//If the user doesn't have an open session, redirect to login page and display an error
		header('Location: v_login.php?notloggedin=1');
	}
?>