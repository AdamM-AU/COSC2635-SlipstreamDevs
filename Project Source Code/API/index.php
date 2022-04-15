<?PHP
/* API/index.php
 * NOTES: Primary API for all AJAX forms post-authentication
 *			Accessable via https://slipstreamdevs.tech/API/?task=[TASKNAME]
 *
 * Last Modified: 2022/03/29 - By Adam Mutimer (s3875753)
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
		echo "User Management - Add User";
		die(); // Kill the script do not process any further as we are done here
	 
	// User Management - Delete User
	case "UserDel":
		echo "User Management - Delete User";
		die();
	 
	// User Management - Modify User
	case "UserAdd":
		echo "// User Management - Modify User";
		die();
	 
	// User Management - User Password Change
	case "UserPass":
		echo "User Management - User Password Change";
		die();

	// User Management - List Users
	case "UserList":
		// Hit the database for the user list
		$query = $pdo->prepare('SELECT Username, Position, Email, FirstName, LastName, LicenseNumber, LicenseType, LicenseState, AdminAccess, StartDate, FinishDate from Users');
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
			
			// Build Array Entry
			$arrayEntry = array($row['Username'], $row['Position'], $row['Email'], $row['FirstName'], $row['LastName'], $row['LicenseNumber'], $row['LicenseType'], $row['LicenseState'], $adminAccess, $row['StartDate'], $finishedDate);
			
			$processed[] = $arrayEntry; // Add date to processed array
		}
		$preparedArray = array("data" => $processed);
		print json_encode($preparedArray, JSON_PRETTY_PRINT);
		die();
	
	// ----- GROUP CONTROL ----- //
	
	// Group Management - Create Group
	case "GroupCreate":
		echo "Group Management - Create Group";
		die();
		
	// Group Management - Delete Group
	case "GroupDel":
		echo "Group Management - Delete Group";
		die();

	// Group Management - Group Add Member
	case "GroupMemberAdd":
		echo "Group Management - Add Group Memever";
		die();

	// Group Management - Group Delete Member
	case "GroupMemberDel":
		echo "Group Management - Delete Group Member";
		die();
		
	// ----- END OF DEFINED TASKS ----- //
		
	// Catch all
	default:
		echo "Task Not Found!";
		die();
}

?>