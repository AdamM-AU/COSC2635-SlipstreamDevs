<?PHP
/* modules/userMod.php - Group Management Module
 * 		Code here takes care of creating, modifying, deleting groups
 *
 * Last Modified: 2022/04/13 By Adam Mutimer (s3875753)
*/
if (isset($_GET["task"])) {
	$task = $_GET["task"];
	
	switch($task) {
		case "create":
			$subtitle = "Create Group";
			break;
		
		case "list":
			$subtitle = "Group List";
			break;
		
		case "modify":
			$subtitle = "Modify Group";
			break;
		
		case "delete":
			$subtitle = "Delete Group";
			break;
		
		default: 
			$subtitle = "";
			break;
	}
}
?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Group Control Module<?PHP if ($subtitle !== "") { echo " - " . $subtitle; } ?></h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

<?PHP
if (isset($_GET["task"])) {
	$task = $_GET["task"];
	
	switch($task) {
		// Show user list content
		case "create":	
?>
			Incomplete Module
<?PHP
			break;
		
		// Show user list content
		case "list":
?>
					<!-- Page level plugins -->
					<script src="vendor/datatables/jquery.dataTables.min.js"></script>
					<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
					
					<script type="text/javascript">
					$(document).ready(function() {
						$('#dataTable-grouplist').DataTable( {
							"ajax": '<?PHP echo $baseURL; ?>/API/?task=GroupList'
						} );
					} );
					</script>
			        
					<!-- DataTales - Populated via AJAX API call -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable-grouplist" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Location</th>
                                            <th>Manager</th>
                                            <th>Supervisor</th>
                                            <th>User Count</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Location</th>
                                            <th>Manager</th>
                                            <th>Supervisor</th>
                                            <th>User Count</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
<?PHP
		break;
		
		// Show content for user modification
		case "modify":
?>
			Incomplete Module
<?PHP
			break;
		
		// Show content for deleting a user
		case "delete":
?>
			Incomplete Module
<?PHP
			break;
		
		// Default Content
		default: 
?>
			User Control Home<br />Here we will explain various operations.
<?PHP
			break;
	}
}
?>