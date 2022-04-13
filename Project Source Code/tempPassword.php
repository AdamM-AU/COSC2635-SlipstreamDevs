<?PHP
/* This file is used to generate random passwords and the hash for 
 * the password to be assigned to a user in the database.
 *
 */

function RandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
$password = RandomString();
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "PASSWORD: " . $password . "<br />\n";
echo "HASH: " . $hash;
echo "<br \>\n";
echo "<br \>\n";
echo "Verify: ";

if (password_verify($password, $hash)) {
	echo "GOOD";
} else {
	echo "BAD";
}

?>