<?php
	// PHP Processing of users profiles panel using CURL to interface with the Cadence API
	// process_profile.php
	
	require_once('library.php');

	session_start();
	
	$identify = $_SERVER['REMOTE_ADDR'];
	$randString = hash('sha512', $identify);

	if(isset($_SESSION['email']) && (isset($_SESSION['userid'])) && ($_SESSION['loginstring'] == $randString)){
	
		$email = $_SESSION['email'];
		$userid = $_SESSION['userid'];
		$permission = $_SESSION['permissionlevel'];
		
		$ch = curl_init(API_URL . 'users/' . $userid);                                                                    
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
		
		$firstnamedisplay = $userArray['first_name'];
		$lastnamedisplay = $userArray['last_name'];
		
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
		
		$ch = curl_init(API_URL . 'servergroups');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);  			
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json'
		));
		
		$result = curl_exec($ch);
		curl_close($ch);
			
		$groupsArray = json_decode($result, true);
		
		if (isset($_POST['EUForm'])) {
		
			$firstname = strip_tags(trim($_POST['firstname']));
			$lastname = strip_tags(trim($_POST['lastname']));
			$newemail = strip_tags(trim($_POST['email']));
			$mobile = strip_tags(trim($_POST['mobile']));
			
			$data = array("first_name" => "$firstname", "last_name" => "$lastname", "mobile_number" => "$mobile", "email" => "$newemail");  
			
			$data_string = json_encode($data); 

			$ch = curl_init(API_URL . 'users/' . $userid);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))
			);

			$response = curl_exec($ch);
			header('Location: v_profile.php?updatesuccess=1');
		}
		
		function setSubscription($id, $textVal, $phoneVal, $userid, $authemail, $authpassword)
		{	
			$data = array("servergroup_id" => "$id", "user_id" => "$userid", "text" => "$textVal", "phonecall" => "$phoneVal", "push" => "0");                                                                    
			$data_string = json_encode($data);                                                                                   
			
			$ch = curl_init(API_URL . 'subscriptions');                                                                     
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
		}
		
		if (isset($_POST['subscribegroupsbutton'])) {
		
			$counter = 1;
			$subscribeGroupId = 0;
			$subscribeText = 0;
			$subscribePhone = 0;
			
			foreach($_POST as $id => $val) {
				if ($id == "subscribegroupsbutton")
				{
					break;
				}
				
				switch ($counter){
					case 1:
						$subscribeGroupId = explode('|', $id)[0];
						$counter++;
						break;
					case 2: 
						$subscribeText = $val;
						$counter++;
						break;
					case 3:
						$subscribePhone = $val;
						$counter = 1;
						setSubscription($subscribeGroupId , $subscribeText, $subscribePhone, $userid, $authemail, $authpassword);
						break;
				}
			}
			header('Location: v_profile.php?groupsassignsuccess=1');
		}
		
		if (isset($_POST['unsubscribegroupsbutton'])) {
		
			foreach ($_POST as $key => $val ) {
				
				if ($val == 1){
					
					$ch = curl_init(API_URL . 'subscriptions/' . $key); 
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
					$result = curl_exec($ch);
					
					curl_close($ch);
					header('Location: v_profile.php?groupsunassignsuccess=1');
				}
			}
		}
		
		if (isset($_POST['CPForm'])) {
		
			$oldpassword = strip_tags(trim($_POST['oldpassword']));
			$newpassword = strip_tags(trim($_POST['newpassword']));
			$newpasswordconfirm = strip_tags(trim($_POST['newpasswordconfirm']));
			
			if ($oldpassword && $newpassword && $newpasswordconfirm) {

				if ($newpassword == $newpasswordconfirm) {

					$ch = curl_init(API_URL . 'auth');                                                                    
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, $email . ":" . $oldpassword);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					
					$result = curl_exec($ch);
					$authUpdateMatch = json_decode($result);
					
					if($authUpdateMatch->id != 0){
						
						$data = array("newpassword" => "$newpassword");  
						$data_string = json_encode($data); 

						$ch = curl_init(API_URL . 'users/password');
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
						curl_setopt($ch, CURLOPT_USERPWD, $email . ":" . $oldpassword);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(       
							'Content-Type: application/json',                                                                                
							'Content-Length: ' . strlen($data_string))
						);
						
						$response = curl_exec($ch);

						header('Location: v_profile.php?updatepasssucc=1');
					} else {
						header("Location: v_profile.php?updatepasserr=1");
					}
				} else {
					header('Location: v_profile.php?updatepasserr=1');
				}
			} else {
				header('Location: v_profile.php?updatepasserr=1');
			}
		}
		
		if (isset($_POST['savenotificationsbutton'])) {
			$alteredSubs = array();
			
			foreach ($_POST as $key => $val) {
				if ($_POST[$key] != $_POST['savenotificationsbutton']) {
					$keysplode = explode('|', $key);
					$subId = $keysplode[0];
					
					if (!isset($alteredSubs[$subId])) {
						$alteredSubs[$subId] = array();
					}
					
					if (isset($keysplode[1])) {
						if ($keysplode[1] != "exists") {
							$alteredSubs[$subId][$keysplode[1]] = (int)$val;
						}
					}
					
					if (!isset($alteredSubs[$subId]['text'])) {
						$alteredSubs[$subId]['text'] = 0;
					}
					
					if (!isset($alteredSubs[$subId]['phone'])) {
						$alteredSubs[$subId]['phone'] = 0;
					}
				}
			}
			
			foreach ($alteredSubs as $key => $val) {
				$data = array("text" => $val['text'], "phonecall" => $val['phone']);
				$data_string = json_encode($data);
				
				$ch = curl_init(API_URL . 'subscriptions/' . $key);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(       
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($data_string))
				);
				
				$response = curl_exec($ch);
				curl_close($ch);
			}
			
			header('Location: v_profile.php?updatesubs=1');
		}
		
		if (isset($_POST['DAForm'])) {
			
			$ch = curl_init(API_URL . 'users/' . $userid); 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			session_destroy();
			
			header('Location: v_login.php?accountdeletion=1');
		}
	} else {
		//If the user doesn't have an open session, redirect to login page and display an error
		header('Location: v_login.php?notloggedin=1');
	}
?>