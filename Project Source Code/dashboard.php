<?PHP
/* Check if user has logged in via API by checking for session variable
	- Check if username session variable is set?
	- if not set destroy the session and redirect to login form
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
echo "Login Successful!<br /><br />";

echo "<h3> PHP List All Session Variables</h3>";
foreach ($_SESSION as $key=>$val)
	echo $key.": ".$val."<br/>";
?>
<br />
<br />
<a href="dashboard.php?logout">Logout!</a>