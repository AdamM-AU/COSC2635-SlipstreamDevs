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
	
	// $response is an associative array
	$response = array("status" => 0, "message" => "ERROR: Incorrect username and/or password!"); //status: 0 = bad, 1 = good.    message = the message the form will display when status is 0
	echo json_encode($response); // encode php array as a JSON 

} else {
	// We have both username and password, lets attempt to verify them
	
	// Check if username exists in user table, if not error!
	$pdo->prepare('SELECT UserID FROM Users WHERE Username=?'); // Our Query, variables passed in are --> ? <---
	$pdo->execute([ $username ]); // fill the variables (?) in order and execute the query 
	$result = $pdo->fetch(); // Fetch the result of the query :)
	
	if (empty($result['UserID'])) {
		$response = array("status" => 0, "message" => "ERROR: Incorrect username and/or password!");
		echo json_encode($response);
	} else {
		// We have a matching Username, lets store the UserID for the session and check the password
		$UserID = $result['UserID']; // Store this for reuse
		$pdo->prepare('SELECT Password FROM Users WHERE UserID=?');
		$pdo->execute([ $UserID ]);
		$result = $pdo->fetch();
		
		if (empty($result['Password'])) {
			// User has no password?!?!?
			// Throw an error, because that shouldnt happen!
			$response = array("status" => 0, "message" => "ERROR: Incorrect username and/or password!");
			echo json_encode($response);
		} else {
			// run the hash verification
			$verified = password_verify($password, $result['Password']);
			
			if ($verified) {
				// We have a match made in heaven <3 <3
			
				// Fetch the users access level!
				$pdo->prepare('SELECT AccessLevel FROM Users WHERE UserID=?');
				$pdo->execute([ $UserID ]);
				$result = $pdo->fetch();
				$AccessLevel = $result['AccessLevel'];
				
				// CREATE SESSION!
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				
				// SET SESSION VARIABLES
				$_SESSION['UserID'] = $UserID; // This will be used over and over when running other database transactions 
				$_SESSION['Access'] = $AccessLevel // This will be checked over and over while running database transactions, in case access level changes, mostly to display access level to the user on the dashboard
				
				// Send positive response to AJax form to redirect the user
				$response = array("status" => 1, "message" => "");
				echo json_encode($response);
			} else {
				// No Match puke out the gerneral error message...
				$response = array("status" => 0, "message" => "ERROR: Incorrect username and/or password!");
				echo json_encode($response);
			}
		}
	}
}
 
 ?>