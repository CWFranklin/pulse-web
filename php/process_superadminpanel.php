<?php
	// PHP Processing of the administration panel using CURL to interface with the Cadence API
	// process_adminpanel.php
	
	require_once('library.php');

	session_start();
	
	$usersArray = array();
	
	$identify = $_SERVER['REMOTE_ADDR'];
	$randString = hash('sha512', $identify);

	if(isset($_SESSION['email']) && (isset($_SESSION['userid'])) &&($_SESSION['loginstring'] == $randString)){

		$error = false;
		$email = $_SESSION['email'];
		$userid = $_SESSION['userid'];
		$permission = $_SESSION['permissionlevel'];
		
		if($permission == 2){

			$ch = curl_init(API_URL . 'users');                                                                    
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");   
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword); 		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json'
			));
			$result = curl_exec($ch);
			curl_close($ch);
				
			$usersArray = json_decode($result, true);
			
			foreach($usersArray as $currentUser){
				if($currentUser['email'] == $email){
					$adminfirstname = $currentUser['first_name'];
					$adminlastname = $currentUser['last_name'];
				}	
			}
			
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
		
			if(isset($_POST['adduserbutton'])) {
				
				$firstname = strip_tags(trim($_POST['firstname']));
				$lastname = strip_tags(trim($_POST['lastname']));
				$newemail = strip_tags(trim($_POST['email']));
				$password = strip_tags(trim($_POST['password']));
				$mobile = strip_tags(trim($_POST['mobile']));
				$privilege = strip_tags(trim($_POST['privilege']));
				$servergroup = '0';
				
				$detailsarray = array($firstname, $lastname, $newemail, $password, $mobile);
				
				foreach ($detailsarray as $i => $value){
					if ($value == "" || null){
						$displayError[] = "You must fill in the ".$detailsarray[$i]." field! <br/>";
						$error = true;
					}
				}

				$data = array("privilege_id" => "$privilege", "first_name" => "$firstname", "last_name" => "$lastname", "mobile_number" => "$mobile", 
				"email" => "$newemail", "password" => "$password");  
				
				$data_string = json_encode($data);                                                                                   
				
				if ($error == false) {
				
					$ch = curl_init(API_URL . 'users');                                                                    
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword); 				
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
						'Content-Type: application/json',                                                                                
						'Content-Length: ' . strlen($data_string))                                                    
					);  	
					
					$result = curl_exec($ch);
					curl_close($ch);
			
					$addResult = json_decode($result);
					
					$to      = $newemail; // Send email to the user  
					$subject = 'Pulse | Account Creation'; // Give the email a subject   
					$message = 'An account has been created for you by '. $adminfirstname . ' ' . $adminlastname . "\n";
					$message .='You can login with the following email.' . "\n";
					$message .='' . "\n";
					$message .='------------------------' . "\n";
					$message .='Email: '.$newemail.'' . "\n";
					$message .='------------------------' . "\n";
					$from = "noreply@pulse.com";
					$headers = "From:" . $from;

					// Send verification email to registered email address
					mail($to, $subject, $message, $headers); 
			
					if (isset($addResult->success)){
						header('Location: v_superadminpanel.php?adderror=1');
					} else {
						header('Location: v_superadminpanel.php?addsuccess=1');
					}
				}
			}

			if (isset($_POST['DUForm'])) {
			
				$userremoveid = $_POST['selectuser'];
				
				$ch = curl_init(API_URL . 'users/' . $userremoveid); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
				$result = curl_exec($ch);
				
				curl_close($ch);
				
				foreach($usersArray as $removeUser){
					if($removeUser['id'] == $userremoveid){
						$removeemail = $removeUser['email'];
					}	
				}
				
				$to      = $email; // Send email to the user  
				$subject = 'Pulse | Account Deletion'; // Give the email a subject   
				$message = 'Your Pulse account has been deleted by '. $adminfirstname . ' ' . $adminlastname . "\n";
				$message .='You can login with the following email.' . "\n";
				$message .='You can no longer log in to the web or mobile applications.' . "\n";
				$message .='if this is incorrect, please contact the administrator who performed the action at:' . "\n";
				$message .='------------------------' . "\n";
				$message .='Email: '.$email.'' . "\n";
				$message .='------------------------' . "\n";
				$from = "noreply@pulse.com";
				$headers = "From:" . $from;

				// Send verification email to registered email address
				mail($to, $subject, $message, $headers); 
				
				header('Location: v_superadminpanel.php?removesuccess=1');
			}
		
		} else {
			//If the user does not have the correct permissions to view this page, redirect back to the dashboard
			header('Location: v_dashboard.php');
		}	
	} else {
		//If the user doesn't have an open session, redirect to login page and display an error
		header('Location: v_login.php?notloggedin=1');
	}
?>