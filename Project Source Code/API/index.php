<?PHP
/* API/index.php
 * NOTES: Primary API for all AJAX forms post-authentication
 *			Accessable via https://slipstreamdevs.tech/API/?task=[TASKNAME]
 *
 * Last Modified: 2022/03/29 - By Adam Mutimer
 */

if(isset($_GET['task']) && !empty($_GET['task'])) {
    $ReqTask = $_GET['task'];
} else {
	$ReqTask = NULL;
}
 
switch ($ReqTask) {
	// User Management - Add User
	case "UserAdd":
		echo "User Management - Add User";
		die();
	 
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
	
	// Catch all
	default:
		echo "Task Not Found!";
		die();
}
 
?>