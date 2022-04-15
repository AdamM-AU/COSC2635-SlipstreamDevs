<?PHP
/* modules/userMod.php - User Management Module
 * 		Code here takes care of creating, modifying, deleting users
 *
 * Last Modified: 2022/04/13 By Adam Mutimer (s3875753)
*/

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	
	switch($task) {
		case "create":
			$subtitle = "Create User";
			break;
		
		case "list":
			$subtitle = "User List";
			break;
		
		case "modify":
			$subtitle = "Modify User";
			break;
		
		case "delete":
			$subtitle = "Delete User";
			break;
		
		default: 
			$subtitle = "";
			break;
	}
}
?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">User Control Module<?PHP if ($subtitle !== "") { echo " - " . $subtitle; } ?></h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>
