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
 					<div class="card shadow mb-4" style="width: 40%;">
						<div class="card-body" >
							<pre>LIST ALL ASSETS</pre>
						</div>
					</div>

<?PHP
			break;
			
			case "create":
				// Put Code for this task, basically HTML
?>
 <!-- HTML GOES HERE -->
 					<div class="card shadow mb-4" style="width: 40%;">
						<div class="card-body" >
							<pre>CREATE AN ASSET</pre>
						</div>
					</div>
 
<?PHP 
			break;
	}
}
?>
