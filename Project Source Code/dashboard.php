<?PHP
/* Check if user has logged in via API by checking for session variable
	- Check if username session variable is set?
	- if not set destroy the session and redirect to login form
*/
if(!isset($_SESSION['username'])){
	session_destroy();
    header('Location: index.html');
}
// Destroy Session and associated variables if we are logging out, and redirect
if(isset($_GET["logout"])) { //Set but Empty
	session_destroy();
    header('Location: index.html');
}

/* VALID User and Session */
echo "Login Successful!";
?>