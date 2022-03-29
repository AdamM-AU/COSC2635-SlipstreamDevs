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
	$pdo->prepare('SELECT UserID FROM Users WHERE Username=?');
	$pdo->execute([ $username ]);
	$result = $pdo->fetch();
	
	if (empty($result['UserID'])) {
		echo "ERROR: Incorrect username and/or password!";
	} else {
		// We have a matching Username, lets store the UserID for the session and check the password
		$UserID = $result['UserID']; // Store this for reuse
		$pdo->prepare('SELECT Password FROM Users WHERE UserID=?');
		$pdo->execute([ $UserID ]);
		$result = $pdo->fetch();
		
		if (empty($result['Password'])) {
			// User has no password?!?!?
			// Throw an error, because that shouldnt happen!
			echo "ERROR: Incorrect username and/or password!";
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
				$_SESSION['UserID'] = $UserID;
				$_SESSION['Access'] = $AccessLevel
				echo ''; // RESPONSES should probably be JSON payloads to make the JS simpler
			} else {
				// No Match puke out the gerneral error message...
				echo "ERROR: Incorrect username and/or password!";
			}
		}
	}
}
 
 ?>