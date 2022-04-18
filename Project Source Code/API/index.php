<?PHP
/* API/index.php
 * NOTES: Primary API for all AJAX forms post-authentication
 *			Accessable via https://slipstreamdevs.tech/API/?task=[TASKNAME]
 *
 * Last Modified: 2022/03/29 - By Adam Mutimer (s3875753)
 *
 * Notes: need to add protection to the API to ensure user is permitted and logged in.
 */
require_once('config.php');
require_once('include/db.inc.php');

if(isset($_GET['task']) && !empty($_GET['task'])) {
    $ReqTask = $_GET['task'];
} else {
	$ReqTask = NULL;
}
 
switch ($ReqTask) {
	// ----- USER CONTROL ----- //
	// User Management - Add User
	case "UserAdd":
		// INSERT INTO Users (Username, Password, Email, Position, FirstName, LastName, LicenseNumber, LicenseState, LicenseType, StartDate) VALUES (?,?,?,?,?,?,?,?,?,?);
		echo "User Management - Add User";
		die(); // Kill the script do not process any further as we are done here
	 
	// User Management - Delete User
	case "UserDel":
		// UPDATE Users SET Active=?, FinishDate=? WHERE UserID=?
		echo "User Management - Delete User";
		die();
	 
	// User Management - Modify User
	case "UserModify":
		// UPDATE Users SET Email=?, Position=?, FirstName=?, LastName=?, LicenseNumber=?, LicenseState=?, LicenseType=?, StartDate=? WHERE UserID=?
		echo "// User Management - Modify User";
		die();
	 
	// User Management - User Password Change
	case "UserPass":
		// UPDATE Users SET Password=? WHERE UserID=?
		echo "User Management - User Password Change";
		die();

	// User Management - List Users
	case "UserList":
		if(isset($_GET['opt']) && !empty($_GET['opt'])) {
			$option = $_GET['opt'];
			
			switch ($option) {
				case "selection":
					$query = $pdo->prepare('SELECT UserID, Username, FirstName, LastName from Users');
					$query->execute([ ]);
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
			$query = $pdo->prepare('SELECT UserID, Username, Position, Email, FirstName, LastName, LicenseNumber, LicenseType, LicenseState, AdminAccess, StartDate, FinishDate from Users');
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
				if ($row['FinishedDate'] == null) {
					$finishedDate = "N/A";
				} else {
					$finishedDate = $row['FinishedDate'];
				}
				
				// Call a modal and pass the GroupID to the modal code, so it can pass it to the API :)
				$deleteButton = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#deleteUser\" data-id=\"" . $row['UserID'] . "\" data-name=\"" . $row['Username'] . "\" alt=\"Delete User\"><i class=\"text-danger fa-solid fa-x\"></i></a>";
				$editButton = "<a href=\"$baseURL/dashboard.php?module=userMod&task=modify&target=$row[UserID]\" alt=\"Edit User\"><i class=\"text-warning fa-solid fa-pen\"></i></a>";
				
				// Build Array Entry
				$arrayEntry = array($row['Username'], $row['Position'], $row['Email'], $row['FirstName'], $row['LastName'], $row['LicenseNumber'], $row['LicenseType'], $row['LicenseState'], $adminAccess, $row['StartDate'], $finishedDate, $editButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $deleteButton);
				
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
		$query = $pdo->prepare('SELECT GroupID, GroupName, GroupDescription, Location, Manager, Supervisor from UserGroups');
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
			
			// LocationID to Name
			if ($row['Location'] !== null) {
				$query = $pdo->prepare('SELECT Name from Locations WHERE LocationID=?');
				$query->execute([ $row['Location'] ]);
				$result = $query->fetch();
				$location = $result['Name'];
			} else {
				$Supervisor = "Not Assigned";
			}
			
			// How many users belong to group
			$query = $pdo->prepare('SELECT COUNT(UserID) AS Count from UserGroupMapping WHERE GroupID=?');
			$query->execute([ $row['GroupID'] ]);
			$result = $query->fetch();
			$userCount = $result['Count'];
			
			// Call a modal and pass the GroupID to the modal code, so it can pass it to the API :)
			$deleteButton = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#deleteGroup\" data-id=\"" . $row['GroupID'] . "\" data-name=\"" . $row['GroupName'] . "\" alt=\"Delete Group\"><i class=\"text-danger fa-solid fa-x\"></i></a>";
			$editButton = "<a href=\"$baseURL/dashboard.php?module=groupMod&task=modify&target=$row[GroupID]\" alt=\"Edit Group\"><i class=\"text-warning fa-solid fa-pen\"></i></a>";
			$membersButton = "<i class=\"text-success fa-solid fa-users\"></i>";
			
			
			// Build Array Entry
			$arrayEntry = array($row['GroupName'], $row['GroupDescription'], $location, $manager, $supervisor, $userCount, $membersButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $editButton . '&nbsp;&nbsp;&nbsp;&nbsp;' . $deleteButton);
			
			$processed[] = $arrayEntry; // Add date to processed array
		}
		$preparedArray = array("data" => $processed);
		print json_encode($preparedArray, JSON_PRETTY_PRINT);
		die();

	case "GroupCreate":
		// INSERT INTO UserGroups (GroupName, GroupDescription, Location, Manager, Supervisor) VALUES (?,?,?,?,?);
		echo "Group Management - Create Group";
		die();
		
	// Group Management - Delete Group
	case "GroupDel":
		// DELETE FROM UserGroups WHERE GroupID=?
		echo "Group Management - Delete Group";
		die();
	
	case "GroupModify":
		// UPDATE UserGroups SET GroupDescription=?, Location=?, Manager=?, Supervisor=? WHERE GroupID=?
		echo "Group Management - Modify Group";
		die();
	
	// Group Management - Group Add Member
	case "GroupMemberAdd":
		// INSERT INTO UserGroupMapping (UserID, GroupID) VALUES (?,?);
		echo "Group Management - Add Group Memever";
		die();

	// Group Management - Group Delete Member
	case "GroupMemberDel":
		// DELETE FROM UserGroupMapping WHERE UserID=? AND GroupID=?
		echo "Group Management - Delete Group Member";
		die();
		
	// ----- END OF DEFINED TASKS ----- //
		
	// Catch all
	default:
		echo "Task Not Found!";
		die();
}

?>