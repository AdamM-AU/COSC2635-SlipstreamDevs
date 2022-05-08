<?PHP
/* API/index.php
 * NOTES: Primary API for all AJAX forms post-authentication
 *			Accessable via https://slipstreamdevs.tech/API/?task=[TASKNAME]
 *
 * Last Modified: 2022/04/28 - By Adam Mutimer (s3875753)
 *
 * Notes: 	- need to add protection to the API to ensure user is permitted and logged in.
 * 			- need to add admin access checks
 */
require_once('config.php');
require_once('include/db.inc.php');

function assignIfNotEmpty($var) {
	// Sepecial function to assign "" to empty veriables
    return (!empty($var)) ? $var : "";
}

if(isset($_GET['task']) && !empty($_GET['task'])) {
    $ReqTask = $_GET['task'];
} else {
	$ReqTask = NULL;
}
 
switch ($ReqTask) {
	// ----- USER CONTROL ----- //
	// User Management - Add User
	case "UserAdd":
		// Do we have a email address
		if(isset($_POST['Email']) && !empty($_POST['Email'])) {
			// Do we have a username
			if(isset($_POST['Username']) && !empty($_POST['Username'])) {
				// Do we have a password
				if(isset($_POST['Password']) && !empty($_POST['Password'])) {
					// Do we have a confirmation password
					if(isset($_POST['PasswordConf']) && !empty($_POST['PasswordConf'])) {
						// Do the passwords match?
						if ($_POST['Password'] == $_POST['PasswordConf']) {
							// Do we have a first name
							if(isset($_POST['FName']) && !empty($_POST['FName'])) {
								// Do we have a last name
								if(isset($_POST['LName']) && !empty($_POST['LName'])) {
									// We have all required fields
									
									// Check if username is taken!
									$query = $pdo->prepare('SELECT Username from Users WHERE Username=? LIMIT 1');
									$query->execute([ $_POST['Username'] ]);
									$result = $query->fetchAll();
									
									if ($result == NULL) {
										// Unique Username
										// Required Variables
										$Email = $_POST['Email']; // Email Address
										$Username = $_POST['Username']; // Username
										$Password = $_POST['Password']; // Plain text password
										$FName = $_POST['FName']; // First Name
										$LName = $_POST['LName']; // Last Name
										
										// Optional Variables
										$Position = assignIfNotEmpty($_POST['Position']); // Job Role
										$LicenseNumber = assignIfNotEmpty($_POST['LicNo']); // License Number
										$LicenseState = assignIfNotEmpty($_POST['LicState']); // License Issuing State
										$LicenseState = strtoupper($LicenseState); // Uppercase string
										$LicenseType = assignIfNotEmpty($_POST['LicType']); // License Issuing State
										
										// Process License Type Array
										$LicenseType = json_encode($LicenseType); // Convert to JSON array to store in SQLlite
										
										// Process Password
										$Password = password_hash($Password, PASSWORD_DEFAULT); // Hashed Password
										
										// Set Start Date
										$StartDate = date("Y-m-d");
										
										$query = $pdo->prepare('INSERT INTO Users (Username, Password, Email, Position, FirstName, LastName, LicenseNumber, LicenseState, LicenseType, StartDate) VALUES (?,?,?,?,?,?,?,?,?,?)');
										$query->execute([ $Username, $Password, $Email, $Position, $FName, $LName, $LicenseNumber, $LicenseState, $LicenseType, $StartDate ]);
										
										$response = array("status" => 1, "message" => "OK"); // OK
									} else {
										$response = array("status" => 0, "message" => "ERROR: Username is in use!");
									}
								} else {
									$response = array("status" => 0, "message" => "ERROR: Last name required!");
								}
							} else {
								$response = array("status" => 0, "message" => "ERROR: First name required!");
							}
						} else {
							$response = array("status" => 0, "message" => "ERROR: Passwords do not match!");
						}
					} else {
						$response = array("status" => 0, "message" => "ERROR: Please Confirm Password!");
					}
				} else {
					$response = array("status" => 0, "message" => "ERROR: Password Required!");
				}
					
			} else {
				$response = array("status" => 0, "message" => "ERROR: Username Required!");
			}
			
		} else {
			$response = array("status" => 0, "message" => "ERROR: An Email Address is Required!");
		}
		
		print json_encode($response, JSON_PRETTY_PRINT);
		die();

	// User Management - Delete User
	case "UserDel":
		if(isset($_POST['target']) && !empty($_POST['target'])) {
			$userID = $_POST['target'];
			$terminationDate = date("Y-m-d");
			
			$query = $pdo->prepare('UPDATE Users SET Active=?, FinishDate=? WHERE UserID=?');
			$query->execute([ 0, $terminationDate, $userID ]);
			$response = array("status" => 1, "message" => "");
		} else {
			$response = array("status" => 0, "message" => "ERROR: Unable to delete user!");
		}
		print json_encode($response, JSON_PRETTY_PRINT);	
		die();

	// User Management - Delete User
	case "UserUnDel":
		if(isset($_POST['target']) && !empty($_POST['target'])) {
			$userID = $_POST['target'];
			$startDate = date("Y-m-d");
			$terminationDate = NULL;
			
			$query = $pdo->prepare('UPDATE Users SET Active=?, StartDate=?, FinishDate=? WHERE UserID=?');
			$query->execute([ 1, $startDate, $terminationDate, $userID ]);
			$response = array("status" => 1, "message" => "");
		} else {
			$response = array("status" => 0, "message" => "ERROR: Unable to restore user!");
		}
		print json_encode($response, JSON_PRETTY_PRINT);
		die();
	 
	// User Management - Modify User
	case "UserModify":
		if(isset($_GET['opt']) && !empty($_GET['opt'])) {
			$opt = $_GET['opt'];
			
			switch ($opt) {
				// Fetch Current UserData
				case "fetch" :
					if(isset($_GET['target']) && !empty($_GET['target'])) {
						$target = $_GET['target'];
						
						$query = $pdo->prepare('SELECT Username, Email, Position, FirstName, LastName, LicenseNumber, LicenseState, LicenseType FROM Users WHERE UserID=?');
						$query->execute([ $target ]);
						$result = $query->fetch();
						
						// Does the user exist?
						if ($result != NULL) {
							$processed = array( "Username" => $result['Username'],
												"Email" => $result['Email'],
												"Position" => $result['Position'],
												"FName" => $result['FirstName'],
												"LName" => $result['LastName'],
												"LicNo" => $result['LicenseNumber'],
												"LicState" => strtolower($result['LicenseState']),
												"LicType[]" => json_decode($result['LicenseType']) // This is stored in the database as a json array, as SQLite cannot store array datatypes :)
												);
							$response = array("status" => 1, "message" => "", "data" => $processed);
							print json_encode($response, JSON_PRETTY_PRINT);
							die();
						} else {
							$response = array("status" => 0, "message" => "ERROR: User not found!");
							print json_encode($response, JSON_PRETTY_PRINT);
							die();
						}
					} else {
						$response = array("status" => 0, "message" => "ERROR: User not found!");
						print json_encode($response, JSON_PRETTY_PRINT);
						die();
					}
					
				// Update UserData
				case "update" :
					if(isset($_GET['target']) && !empty($_GET['target'])) {
						$target = $_GET['target'];
						
						// Check user exists in database
						$query = $pdo->prepare('SELECT Username FROM Users WHERE UserID=?');
						$query->execute([ $target ]);
						$result = $query->fetch();
						
						// Does the user exist?
						if ($result != NULL) {
							$Email = $_POST['Email']; // Email address
							$Username = $_POST['Username']; // Username
							$FName = $_POST['FName']; // First Name
							$LName = $_POST['LName']; // Last Name
							$Position = $_POST['Position']; // Job Role
							$LicNo = $_POST['LicNo']; // License Number
							
							$LicState = strtoupper($_POST['LicState']); // License Issuing State + convert to all uppercase
							
							$LicType = $_POST['LicType']; // License Types (array)
							$LicType = json_encode($LicType); // Convert to JSON array to store in SQLlite
							
							$query = $pdo->prepare('UPDATE Users SET Email=?, Username=?, Position=?, FirstName=?, LastName=?, LicenseNumber=?, LicenseState=?, LicenseType=? WHERE UserID=?');
							$query->execute([ $Email, $Username, $Position, $FName, $LName, $LicNo, $LicState, $LicType, $target ]);
							$response = array("status" => 1, "message" => "");
						} else {
							$response = array("status" => 0, "message" => "ERROR: User not found!");
						}
					} else {
						$response = array("status" => 0, "message" => "ERROR: User not found!");
					}
					print json_encode($response, JSON_PRETTY_PRINT);
					die();
				
				default:
					$response = array("status" => 0, "message" => "ERROR: Option not found!");
					print json_encode($response, JSON_PRETTY_PRINT);
			}
		} else {
			$response = array("status" => 0, "message" => "ERROR: Option not found!");
			print json_encode($response, JSON_PRETTY_PRINT);
		}
		die();
	 
	// User Management - User Password Change (Self Change)
	case "UserPass":
		// UPDATE Users SET Password=? WHERE UserID=?
		echo "User Management - User Password Change";
		die();

	// User Management - User Password Change
	case "UserPassReset":
		if(isset($_POST['target']) && !empty($_POST['target'])) {
			$target = $_POST['target']; // Target User ID
			
			// Check user exists in database
			$query = $pdo->prepare('SELECT Username FROM Users WHERE UserID=?');
			$query->execute([ $target ]);
			$result = $query->fetch();
			
			// Does the user exist?
			if ($result != NULL) {
				$password = $_POST['password']; // Plain text password
				$password = password_hash($password, PASSWORD_DEFAULT); // Hashed Password
			
				$query = $pdo->prepare('UPDATE Users SET Password=? WHERE UserID=?');
				$query->execute([ $password, $target ]);
				$response = array("status" => 1, "message" => "");
			} else {
				$response = array("status" => 0, "message" => "ERROR: User not found!");
			}
		} else {
			$response = array("status" => 0, "message" => "ERROR: User not found!");
		}
		
		print json_encode($response, JSON_PRETTY_PRINT);
		die();

	// User Management - List Users
	case "UserList":
		if(isset($_GET['opt']) && !empty($_GET['opt'])) {
			$option = $_GET['opt'];
			
			switch ($option) {
				case "selection":
					// Used for populating drop down menus ;)
					$query = $pdo->prepare('SELECT UserID, Username, FirstName, LastName from Users WHERE Active=?');
					$query->execute([ 1 ]);
					$result = $query->fetchAll();
					
					// Create holding array
					$processed = array();					

					foreach ($result as $row) {			
						// Build Array Entry
						$text = $row['Username'] . ' (' . $row['LastName'] . ', ' . $row['FirstName'] . ')';
						$arrayEntry = array("id" => $row['UserID'], "text" => $text);
						$processed[] = $arrayEntry; // Add date to processed array
					}
					print json_encode($processed, JSON_PRETTY_PRINT);
				default:
					die();	
			}
			
		} else {
			// Hit the database for the user list
			$query = $pdo->prepare('SELECT UserID, Username, Position, Email, FirstName, LastName, LicenseNumber, LicenseType, LicenseState, AdminAccess, StartDate, FinishDate, Active FROM Users');
			$query->execute([ ]);
			$result = $query->fetchAll();
			
			// Create holding array
			$processed = array();
		
			foreach ($result as $row) {
				// Override AdminAccess value
				if ($row['AdminAccess'] == 1) {
					$adminAccess = "Yes";
				} else {
					$adminAccess = "No";
				}
				
				// Override finish date - null
				if ($row['FinishDate'] == null) {
					$finishedDate = "N/A";
				} else {
					$finishedDate = $row['FinishDate'];
				}
				
				// Process License Types
				$LicenseType = json_decode($row['LicenseType']);
				$LicenseType = implode(", ", $LicenseType);
				
				// Call a modal and pass the GroupID to the modal code, so it can pass it to the API :)
				if ($row['Active'] == 1) {
					// Delete Button
					$deleteButton = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#deleteUser\" data-id=\"" . $row['UserID'] . "\" data-name=\"" . $row['Username'] . "\" alt=\"Delete User\"><i class=\"text-danger fa-solid fa-x\"></i></a>";
				} else {
					// Activate Button
					$deleteButton = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#unDeleteUser\" data-id=\"" . $row['UserID'] . "\" data-name=\"" . $row['Username'] . "\" alt=\"Undelete User\"><i class=\"text-success fa-solid fa-check\"></i></a>";
				}
				
				$passwordChange = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#passwordResetUser\" data-id=\"" . $row['UserID'] . "\" data-name=\"" . $row['Username'] . "\" alt=\"Password Reset\"><i class=\" text-warning fa-solid fa-key\"></i></a>";
				
				$editButton = "<a href=\"$baseURL/dashboard.php?module=userMod&task=modify&target=$row[UserID]\" alt=\"Edit User\"><i class=\"text-primary fa-solid fa-pen\"></i></a>";
				
				// Build Array Entry
				if ($row['Active'] == 1) {
					// Active Accounts
					$arrayEntry = array($row['Username'], $row['Position'], $row['Email'], $row['FirstName'], $row['LastName'], $row['LicenseNumber'], $LicenseType, $row['LicenseState'], $adminAccess, $row['StartDate'], $finishedDate, $passwordChange . '&nbsp;&nbsp;&nbsp;&nbsp;' . $editButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $deleteButton);
				} else {
					// Inactive Accounts
					$arrayEntry = array('<i><s>' . $row['Username'] . '</s></i>', '<i>' . $row['Position'] . '</i>', '<i>' . $row['Email'] . '</i>', '<i>' . $row['FirstName'] . '</i>', '<i>' . $row['LastName'] . '</i>', '<i>' . $row['LicenseNumber'] . '</i>', '<i>' . $LicenseType . '</i>', '<i>' . $row['LicenseState'] . '</i>', '<i>' . $adminAccess . '</i>', '<i>' . $row['StartDate'] . '</i>', '<i>' . $finishedDate . '</i>', $passwordChange . '&nbsp;&nbsp;&nbsp;&nbsp;' . $editButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $deleteButton);
				}
				
				$processed[] = $arrayEntry; // Add date to processed array
			}
			$preparedArray = array("data" => $processed);
			print json_encode($preparedArray, JSON_PRETTY_PRINT);
		}
		die();
	
	// ----- GROUP CONTROL ----- //
	
	// Group Management - Create Group
	case "GroupList":
		// Hit the database for the user list
		$query = $pdo->prepare('SELECT GroupID, GroupName, GroupDescription, Location, Manager, Supervisor from UserGroups WHERE disabled=0');
		$query->execute([ ]);
		$result = $query->fetchAll();
		
		// Create holding array
		$processed = array();
	
		foreach ($result as $row) {
			// Manager UserID to Name
			if ($row['Manager'] !== null) {
				// Run Query to get actual manager name
				$query = $pdo->prepare('SELECT FirstName, LastName from Users WHERE UserID=?');
				$query->execute([ $row['Manager'] ]);
				$result = $query->fetch();
				$manager = $result['LastName'] . ", " . $result['FirstName'];
			} else {
				$adminAccess = "Not Assigned";
			}
			
			// Supervisor UserID to Name
			if ($row['Supervisor'] !== null) {
				$query = $pdo->prepare('SELECT FirstName, LastName from Users WHERE UserID=?');
				$query->execute([ $row['Supervisor'] ]);
				$result = $query->fetch();
				$supervisor = $result['LastName'] . ", " . $result['FirstName'];
			} else {
				$Supervisor = "Not Assigned";
			}
			
			// REMOVED: To reduce complexity this has been removed - 26/04/2022
			// LocationID to Name
			/*if ($row['Location'] !== null) {
				$query = $pdo->prepare('SELECT Name from Locations WHERE LocationID=?');
				$query->execute([ $row['Location'] ]);
				$result = $query->fetch();
				$location = $result['Name'];
			} else {
				$Supervisor = "Not Assigned";
			}*/
			$location = $row['Location'];
			
			// How many users belong to group
			$query = $pdo->prepare('SELECT COUNT(UserID) AS Count from UserGroupMapping WHERE GroupID=?');
			$query->execute([ $row['GroupID'] ]);
			$result = $query->fetch();
			$userCount = $result['Count'];
			
			// Call a modal and pass the GroupID to the modal code, so it can pass it to the API :)
			$deleteButton = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#deleteGroup\" data-id=\"" . $row['GroupID'] . "\" data-name=\"" . $row['GroupName'] . "\" alt=\"Delete Group\"><i class=\"text-danger fa-solid fa-x\"></i></a>";
			$editButton = "<a href=\"$baseURL/dashboard.php?module=groupMod&task=modify&target=$row[GroupID]\" alt=\"Edit Group\"><i class=\"text-warning fa-solid fa-pen\"></i></a>";
			$membersButton = "<a href=\"$baseURL/dashboard.php?module=groupMod&task=memberControl&target=$row[GroupID]\" alt=\"Edit Group\"><i class=\"text-success fa-solid fa-users\"></i></a>";			
			
			// Build Array Entry
			$arrayEntry = array($row['GroupName'], $row['GroupDescription'], $location, $manager, $supervisor, $userCount, $membersButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $editButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $deleteButton);
			
			$processed[] = $arrayEntry; // Add date to processed array
		}
		$preparedArray = array("data" => $processed);
		print json_encode($preparedArray, JSON_PRETTY_PRINT);
		die();

	case "GroupCreate":
		// INSERT INTO UserGroups (GroupName, GroupDescription, Location, Manager, Supervisor) VALUES (?,?,?,?,?)
		// Run some validation
		if(isset($_POST['Name']) && !empty($_POST['Name'])) {
			if(isset($_POST['Location']) && !empty($_POST['Location'])) {
				if(isset($_POST['manager']) && !empty($_POST['manager'])) {
					if(isset($_POST['supervisor']) && !empty($_POST['supervisor'])) {
						// Check if group name is taken!
						$query = $pdo->prepare('SELECT GroupName from UserGroups WHERE GroupName=? LIMIT 1');
						$query->execute([ $_POST['Name'] ]);
						$result = $query->fetchAll();
						
						if ($result == NULL) {							
							// Save variables
							$Name = $_POST['Name']; // Group Name
							$Desc = $_POST['Desc']; // Group Description
							$Location = $_POST['Location']; // Group Location
							$Manager = $_POST['manager']; // Group Managers ID
							$Supervisor = $_POST['supervisor']; // Group Supervisors ID
							
							$query = $pdo->prepare('INSERT INTO UserGroups (GroupName, GroupDescription, Location, Manager, Supervisor) VALUES (?,?,?,?,?)');
							$query->execute([ $Name, $Desc, $Location, $Manager, $Supervisor ]);
							
							$response = array("status" => 1, "message" => ""); // OK
						} else {
							$response = array("status" => 0, "message" => "ERROR: Group name already in use!");
						}
					} else {
						$response = array("status" => 0, "message" => "ERROR: Supervisor Required!");
					}
				} else {
					$response = array("status" => 0, "message" => "ERROR: Manager Required!");
				}
					
			} else {
				$response = array("status" => 0, "message" => "ERROR: Location Required!");
			}
			
		} else {
			$response = array("status" => 0, "message" => "ERROR: Group Name Required!");
		}
		
		print json_encode($response, JSON_PRETTY_PRINT);
		
		die();
		
	// Group Management - Delete Group
	case "GroupDel":
		if(isset($_POST['target']) && !empty($_POST['target'])) {
			$groupID = $_POST['target'];
			$query = $pdo->prepare('UPDATE UserGroups SET Disabled=1 WHERE GroupID=?');
			$query->execute([ $groupID ]);
			$response = array("status" => 1, "message" => "");
		} else {
			$response = array("status" => 0, "message" => "ERROR: Unable to delete group!");
		}
		print json_encode($response, JSON_PRETTY_PRINT);	
		die();
	
	case "GroupModify":
		if(isset($_GET['opt']) && !empty($_GET['opt'])) {
			$opt = $_GET['opt'];
			
			switch ($opt) {
				// Fetch Current UserGroup Data
				case "fetch" :
					if(isset($_GET['target']) && !empty($_GET['target'])) {
						$target = $_GET['target'];
						
						$query = $pdo->prepare('SELECT GroupName, GroupDescription, Location, Manager, Supervisor FROM UserGroups WHERE GroupID=?');
						$query->execute([ $target ]);
						$result = $query->fetch();
						
						// Does the group exist?
						if ($result != NULL) {
							$processed = array( "Name" => $result['GroupName'], // Group Name
												"Desc" => $result['GroupDescription'], // Group Description
												"Location" => $result['Location'], // Group Location
												"Manager" => $result['Manager'], // Group Manager UserID
												"Supervisor" => $result['Supervisor'], // Group Supervisor UserID
												);
							$response = array("status" => 1, "message" => "", "data" => $processed);
							print json_encode($response, JSON_PRETTY_PRINT);
							die();
						} else {
							$response = array("status" => 0, "message" => "ERROR: Group not found!");
							print json_encode($response, JSON_PRETTY_PRINT);
							die();
						}
					} else {
						$response = array("status" => 0, "message" => "ERROR: Group not found!");
						print json_encode($response, JSON_PRETTY_PRINT);
						die();
					}
					
				// Update GroupData
				case "update" :
					if(isset($_GET['target']) && !empty($_GET['target'])) {
						$target = $_GET['target'];
						
						// Check user exists in database
						$query = $pdo->prepare('SELECT GroupName FROM UserGroups WHERE GroupID=?');
						$query->execute([ $target ]);
						$result = $query->fetch();
						
						// Does the user exist?
						if ($result != NULL) {
							$GroupName = $_POST['Name']; // Group Name
							$GroupDesc = $_POST['Desc']; // Group Description
							$GroupLocation = $_POST['Location']; // Group Location
							$GroupManager = $_POST['Manager']; // Group's Manager UserID
							$GroupSupervisor = $_POST['Supervisor']; // Group's Supervisor UserID
							
							$query = $pdo->prepare('UPDATE UserGroups SET GroupName=?, GroupDescription=?, Location=?, Manager=?, Supervisor=? WHERE GroupID=?');
							$query->execute([ $GroupName, $GroupDesc, $GroupLocation, $GroupManager, $GroupSupervisor, $target ]);
							$response = array("status" => 1, "message" => "");
						} else {
							$response = array("status" => 0, "message" => "ERROR: User not found!");
						}
					} else {
						$response = array("status" => 0, "message" => "ERROR: User not found!");
					}
					print json_encode($response, JSON_PRETTY_PRINT);
					die();
				
				default:
					$response = array("status" => 0, "message" => "ERROR: Option not found!");
					print json_encode($response, JSON_PRETTY_PRINT);
			}
		} else {
			$response = array("status" => 0, "message" => "ERROR: Option not found!");
			print json_encode($response, JSON_PRETTY_PRINT);
		}
		die();
	
	// Group Management - Group Add/Remove Memberss
	case "GroupMembers":
		if(isset($_GET['target']) && !empty($_GET['target'])) {
			$target = $_GET['target'];
			
			if(isset($_GET['opt']) && !empty($_GET['opt'])) {
				$option = $_GET['opt'];
			
				switch ($option) {
					case "selection":
						// Get Group Name
						$query = $pdo->prepare('SELECT GroupName FROM UserGroups WHERE GroupID=?');
						$query->execute([ $target ]);
						$result = $query->fetch();
						$GroupName = $result['GroupName'];

						// Members
						$query = $pdo->prepare('SELECT UserGroupMapping.UserID, Users.FirstName, Users.LastName, Users.Username FROM UserGroupMapping INNER JOIN Users ON UserGroupMapping.UserID = Users.UserID WHERE UserGroupMapping.GroupID=?');
						$query->execute([ $target ]);
						$result = $query->fetchAll();
						
						// Create holding array
						$processed1 = array();

						foreach ($result as $row) {			
							// Build Array Entry
							$text = $row['Username'] . ' (' . $row['LastName'] . ', ' . $row['FirstName'] . ')';
							$arrayEntry = array("id" => $row['UserID'], "text" => $text);
							$processed1[] = $arrayEntry; // Add date to processed array
						}
						
						$response[] = array("members" => $processed);
						
						// Non Members
						$query = $pdo->prepare('SELECT Users.UserID, Users.Username, Users.FirstName, Users.LastName FROM Users LEFT JOIN ( SELECT UserID, GroupID FROM UserGroupMapping WHERE GroupID=? ) AS Q1 ON Users.UserID = Q1.UserID WHERE Q1.UserID IS NULL;');
						$query->execute([ $target ]);
						$result = $query->fetchAll();				

						// Create holding array
						$processed2 = array();

						foreach ($result as $row) {
							// Build Array Entry
							$text = $row['Username'] . ' (' . $row['LastName'] . ', ' . $row['FirstName'] . ')';
							$arrayEntry = array("id" => $row['UserID'], "text" => $text);
							$processed2[] = $arrayEntry; // Add date to processed array
						}
						$response = array("GroupName" => $GroupName, "nonMembers" => $processed2, "members" => $processed1,);
						
						print json_encode($response, JSON_PRETTY_PRINT);
						die();
						
					case "update":
						// DELETE ALL MEMBER MAPS EXCEPT Manager/Supervisor!
						// ADD ALL MEMBER MAPS EXCEPT Manager/Supervisor
						$target = $_GET['target'];
						$userMap = $_POST['userSelection'];
						
						$query = $pdo->prepare('SELECT Manager, Supervisor FROM UserGroups WHERE GroupID=?');
						$query->execute([ $target ]);
						$result = $query->fetchAll(PDO::FETCH_NUM);	
						
						// We will use this to remove them from the data to be processed for deletion or addition to the group
						$ManagerSupervisor = $result[0];
						$ManagerSupervisor = array_unique($ManagerSupervisor);
						
						// Sanitised User MAP
						$userMap = array_unique($userMap); // Remove Duplicates
						$userMap = array_diff($userMap, $ManagerSupervisor); // Remove any user from usermap array that also exists in ManagerSupervisor array
						
						// Delete everyone but manager and supervisor from database
						$query = $pdo->prepare('DELETE FROM UserGroupMapping WHERE UserID NOT IN (SELECT Manager FROM UserGroups WHERE GroupID=?) AND UserID NOT IN (SELECT Supervisor FROM UserGroups WHERE GroupID=?) AND UserGroupMapping.GroupID=?');
						$query->execute([ $target, $target, $target ]);
						
						// Proccess the changes to the database
						foreach ($userMap as $user) {
							$query = $pdo->prepare('INSERT INTO UserGroupMapping (UserID, GroupID) VALUES (?, ?)');
							$query->execute([ $user, $target ]);
						}

						print json_encode(array("status" => 1, "message" => ""), JSON_PRETTY_PRINT);
						die();
					
					default:
						print json_encode(array("status" => 0, "message" => "ERROR: Task not found!"), JSON_PRETTY_PRINT);
						die();
				}
			} else {
				print json_encode(array("status" => 0, "message" => "ERROR: Option Not Found!"), JSON_PRETTY_PRINT);
				die();
			}
			
		} else {
			print json_encode(array("status" => 0, "message" => "ERROR: Group Not Found!"), JSON_PRETTY_PRINT);
			die();
		}
		
	// ----- END OF DEFINED TASKS ----- //
		
	// Catch all
	default:
		echo "Task Not Found!";
		die();
}

?>