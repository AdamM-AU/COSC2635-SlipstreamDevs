<?PHP
/* API/index.php
 * NOTES: Primary API for all AJAX forms post-authentication
 *			Accessable via https://slipstreamdevs.tech/API/?task=[TASKNAME]
 *
 * Last Modified: 2022/03/29 - By Adam Mutimer (s3875753)
 */

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
		echo "User Management - List Users";
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