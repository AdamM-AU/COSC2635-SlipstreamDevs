<?PHP
/* Check if user has logged in via API by checking for session variable
 *	- Check if username session variable is set?
 *	- if not set destroy the session and redirect to login form
 *
 * Last Modified: 2022/04/13 By Adam Mutimer (s3875753)
*/
session_start();

if (!isset($_SESSION['Username'])) {	
	session_destroy();
    header('Location: index.html');
}

// Destroy Session and associated variables if we are logging out, and redirect
if (isset($_GET["logout"])) { //Set but Empty
	session_destroy();
    header('Location: index.html');
}
/* VALID User and Session */
if(isset($_GET['module'])) {
	$requestedModule = $_GET['module'];
	
	switch($requestedModule) {
	// User Management Module
	case 'userMod':
		echo "<h2>User Control Module</h2>";
		break;
	
	// Group Management Module
	case 'groupMod':
		echo "<h2>Group Control Module</h2>";
		break;
	
	default:
		echo "<h2>Requested Module Not Found!</h2>";
	}
} else {
	echo "<h1>Login Successful!</h1>";
	echo "<h3>List All PHP Session Variables</h3>";
	foreach ($_SESSION as $key=>$val)
		echo $key.": ".$val."<br/>";
}
?>
<p>&nbsp;</p>
<hr />
<a href="dashboard.php">Home</a> | <a href="dashboard.php?module=userMod">User Management</a> | <a href="dashboard.php?module=groupMod">Group Management</a> | <a href="dashboard.php?logout">Logout!</a>