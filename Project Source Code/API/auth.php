<?PHP
/* 
 * API/auth.php - handles login requests
 * Notes: I have made this a seperate file so PHP dosnt need to process 
 *		  all api functions at each login attempt.
 *
 * Last Modified: 2022/03/29 By Adam Mutimer
 */
 require_once('config.php');
 require_once('include/db.inc.php');
 
 // Fetch form posted variables

// IF no $_POST['user'] is set or it is set but empty, then it will be set to NULL
if(isset($_POST['user']) && !empty($_POST['user'])) {
    $username = $_POST['user'];
} else {
	$username = NULL;
}

// IF no $_POST['pass'] is set or it is set but empty, then it will be set to NULL
if(isset($_POST['pass']) && !empty($_POST['pass'])) {
    $password = $_POST['pass'];
} else {
	$password = NULL;
}

if ($username === NULL || $password === NULL) {
	// IF $username OR $password is non-existant we throw back an error to the form.
	// We do not indicate a specific reason as this would make it easy to determine usernames
	
	// $response is an associative array
	$response = array("status" => 0, "message" => "Incorrect username and/or password!"); //status: 0 = bad, 1 = good.    message = the message the form will display when status is 0
	echo json_encode($response); // encode php array as a JSON 

} else {
	// We have both username and password, lets attempt to verify them
	
	// Check if username exists in user table, if not error!
	$query = $pdo->prepare('SELECT UserID FROM Users WHERE Username=?'); // Our Query, variables passed in are --> ? <---
	$query->execute([ $username ]); // fill the variables (?) in order and execute the query 
	$result = $query->fetch(); // Fetch the result of the query :)
	
	if (empty($result['UserID'])) {
		$response = array("status" => 0, "message" => "Incorrect username and/or password!");
		echo json_encode($response);
	} else {
		// We have a matching Username, lets store the UserID for the session and check the password
		$UserID = $result['UserID']; // Store this for reuse
		$query = $pdo->prepare('SELECT Password FROM Users WHERE UserID=?');
		$query->execute([ $UserID ]);
		$result = $query->fetch();
		
		if (empty($result['Password'])) {
			// User has no password in the database?!?!? GREAT SCOTT!
			// Throw an error, because that shouldnt happen!
			$response = array("status" => 0, "message" => "Incorrect username and/or password!");
			echo json_encode($response);
		} else {
			// run the hash verification
			$verified = password_verify($password, $result['Password']);
			
			if ($verified) {
				// We have a match made in heaven <3 <3
			
				// Fetch the users access level!
				$query = $pdo->prepare('SELECT AdminAccess FROM Users WHERE UserID=?');
				$query->execute([ $UserID ]);
				$result = $query->fetch();
				$AccessLevel = $result['AdminAccess'];
				
				// CREATE SESSION!
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				
				// SET SESSION VARIABLES
				$_SESSION['Username'] = $username;
				$_SESSION['UserID'] = $UserID; // This will be used over and over when running other database transactions 
				$_SESSION['Access'] = $AccessLevel; // This will be checked over and over while running database transactions, in case access level changes, mostly to display access level to the user on the dashboard
				
				// Send positive response to AJax form to redirect the user
				$response = array("status" => 1, "message" => "");
				echo json_encode($response);
			} else {
				// No Match puke out the gerneral error message...
				$response = array("status" => 0, "message" => "Incorrect username and/or password!");
				echo json_encode($response);
			}
		}
	}
}
 
 ?>