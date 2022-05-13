<?PHP
/* modules/assetMod.php - Asset Management Module
 * 		Code here takes care of creating, modifying, deleting assets
 *		Currently a placeholder
 *
 * Last Modified: 2022/05/11 By 
*/

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	
	switch($task) {
			case "list":
				// Put Code for this task, basically HTML
?>
 <!-- HTML GOES HERE -->
 <pre>LIST ALL ASSETS</pre>

<?PHP
			break;
			
			case "create":
				// Put Code for this task, basically HTML
?>
 <!-- HTML GOES HERE -->
 <pre>CREATE AN ASSET</pre>
 
<?PHP 
			break;
	}
}
?>
