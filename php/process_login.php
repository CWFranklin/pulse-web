<?php
	// PHP Login Processing using CURL to interface with the Cadence API.
	// process_login.php
	
	require_once('library.php');
	
	session_start();
	
	$identify = $_SERVER['REMOTE_ADDR'];
	$randString = hash('sha512', $identify);

	if(isset($_SESSION['email']) && (isset($_SESSION['userid'])) && ($_SESSION['loginstring'] == $randString)){
		header('Location: v_dashboard.php?alreadyloggedin=1');
	}
	
	if(isset($_POST['loginbutton'])){
	
		// Get the email and password from the login form
		// Trim them just in case the user has copied and pasted the email + pw and whitespace has been added
		$email = strip_tags(trim($_POST['email']));
		$password = strip_tags(trim($_POST['password']));

		if ($email && $password) {                                                        

			$ch = curl_init(API_URL . 'auth');                                                                    
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $email . ":" . $password);		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   			
			
			$result = curl_exec($ch);
			$loginmatch = json_decode($result);
			
			if($loginmatch->id != 0){
			
				
				$ch = curl_init(API_URL . 'users/' . $loginmatch->id);                                                                    
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");   
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword); 		
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					'Content-Type: application/json'
				));
				$result = curl_exec($ch);
				curl_close($ch);

				$userArray = json_decode($result, true);
				
				$_SESSION['userid'] = $loginmatch->id;
				$_SESSION['email'] = $email;
				$_SESSION['loginstring'] = $randString;
				$_SESSION['permissionlevel'] = $userArray['privilege_id'];
				
				header('Location: v_dashboard.php');
			} else {
				header("Location: v_login.php?loginerror=1");
			}
		} else {
			header('Location: v_login.php?loginerror=2');
		}
	}
?>