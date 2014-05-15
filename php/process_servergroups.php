<?php
	// PHP Processing of the server groups panel using CURL to interface with the Cadence API
	// process_servergroups.php
	
	require_once('library.php');

	session_start();
	
	$identify = $_SERVER['REMOTE_ADDR'];
	$randString = hash('sha512', $identify);

	if(isset($_SESSION['email']) && (isset($_SESSION['userid'])) && ($_SESSION['loginstring'] == $randString)){
	
		$email = $_SESSION['email'];
		$userid = $_SESSION['userid'];
		$permission = $_SESSION['permissionlevel'];
		
		$ch = curl_init(API_URL . 'servers/unassigned');	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);  			
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json'
		));
		
		$result = curl_exec($ch);
		curl_close($ch);
			
		$unassignedServersArray = json_decode($result, true);
		
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
		
		$ch = curl_init(API_URL . 'servers/' . 'assigned');	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);  			
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json'
		));

		$result = curl_exec($ch);
		curl_close($ch);
			
		$assignedServersArray = json_decode($result, true);
		
		if(isset($_POST['creategroupbutton'])){
		
			$newGroupName = $_POST['newgroupname'];
			
			$data = array("name" => "$newGroupName");                                                                    
			$data_string = json_encode($data);                                                                                   
			
			$ch = curl_init(API_URL . 'servergroups');                                                                     
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

			if (isset($addResult->success)) {
				header('Location: v_servergroups.php?grouperror=1');
			} else {
				header('Location: v_servergroups.php?createsuccess=1');
			}
		}
		
		if(isset($_POST['editgroupbutton'])){
		
			$groupId = $_POST['selecteditgroup'];
			$editGroupName = $_POST['editgroupname'];
			
			$data = array("name" => "$editGroupName");                                                                    
			$data_string = json_encode($data);    
			
			$ch = curl_init(API_URL . 'servergroups/' . $groupId);
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

			$addResult = json_decode($response);
			
			if (isset($addResult->success)) {
				header('Location: v_servergroups.php?grouperror=1');
			} else {
				header('Location: v_servergroups.php?editgroupsuccess=1');
			}
		}
				
		if(isset($_POST['saveunassignedbutton'])){
		
			if(isset($unassignedServersArray)) {
			
				foreach($_POST as $id => $val) {
			
					if ($val == "on") {
						$newservergroup = $_POST['selectgroup'];
						$data = array("servergroup_id" => $newservergroup);
						$data_string = json_encode($data); 
						$ch = curl_init(API_URL . 'servers/' . $id . '/servergroup');
						
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
						header('Location: v_servergroups.php?unassignedsuccess=1');
					}
				}
			}
		}
		
		if(isset($_POST['saveassignedbutton'])){
			
			if(isset($assignedServersArray)) {
			
				foreach($_POST as $id => $val) {
			
					if ($val == "on") {
						$newservergroups = $_POST['selectexistinggroup'];
						$data = array("servergroup_id" => $newservergroups);
						$data_string = json_encode($data); 
						$ch = curl_init(API_URL . 'servers/' . $id . '/servergroup');
						
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
						header('Location: v_servergroups.php?assignedsuccess=1');
					}
				}
			}
		}
		
		if(isset($_POST['RGForm'])) {

			$groupremoveid = $_POST['selectgroup'];
			
			$ch = curl_init(API_URL . 'servergroups/' . $groupremoveid); 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $authemail . ":" . $authpassword);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
			$result = curl_exec($ch);

			curl_close($ch);
			
			header('Location: v_servergroups.php?removesuccess=1');
		
		}  
		
	} else {
		//If the user doesn't have an open session, redirect to login page and display an error
		header('Location: v_login.php?notloggedin=1');
	}

?>