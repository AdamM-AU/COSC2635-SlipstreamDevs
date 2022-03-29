<?PHP
/* 
 * API/auth.php - handles login requests
 * Notes: I have made this a seperate file so PHP dosnt need to process 
 *		  all api functions at each login attempt.
 *
 * Last Modified: 2022/03/29 By Adam Mutimer
 */
 require_once('config.php');
 require_once('includes/db.inc.php');
 
 // Fetch form posted variables
$username = $_POST['user'] ?? NULL; // IF no $_POST['user'], then it will be set to NULL
$password = $_POST['pass'] ?? NULL;

if ($username === NULL || $password === NULL) {
	// IF $username OR $password is non-existant we throw back an error to the form.
	// We do not indicate a specific reason as this would make it easy to determine usernames
	echo "ERROR: Incorrect username and/or password!";

} else {
	// We have both username and password, lets attempt to verify them
	
	// Check if username exists in user table, if not error!
	
	// Check password against matching username, if no match error
	
	// IF username and password are golden, create a session and attach session variables
	// and redirect user to dashboard
}
 
 ?>