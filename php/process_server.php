<?php
	// PHP Processing of the individual server page and dashboard using CURL to interface with the Cadence API
	// process_server.php
	
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
	
		if (isset($_GET['serverid']) && isset($_GET['serverguid'])) {
			$serverid = $_GET['serverid'];
			$serverguid = $_GET['serverguid'];
			
			$ch = curl_init(API_URL . 'servers/' . $serverid);                                                                    
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword); 		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json'
			));
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		$serverDetails = json_decode($result);
			
		} else { 
			//If the user tries to access the server page without selecting a server, redirect to the dashboard page
			header('Location: v_dashboard.php?selectserver=1');
		}
	} else {
		//If the user doesn't have an open session, redirect to login page and display an error
		header('Location: v_login.php?notloggedin=1');
	}
?>