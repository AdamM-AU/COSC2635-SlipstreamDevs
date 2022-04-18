<?PHP
/* modules/userMod.php - User Management Module
 * 		Code here takes care of creating, modifying, deleting users
 *
 * Last Modified: 2022/04/13 By Adam Mutimer (s3875753)
*/
require_once('API/config.php');

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	
	switch($task) {
		case "create":
			$subtitle = "Create User";
			$genreportButton = false;
			break;
		
		case "list":
			$subtitle = "User List";
			$genreportButton = true;
			break;
		
		case "modify":
			$subtitle = "Modify User";
			$genreportButton = false;
			break;
		
		case "delete":
			$subtitle = "Delete User";
			$genreportButton = false;
			break;
		
		default: 
			$subtitle = "";
			$genreportButton = false;
			break;
	}
}
?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">User Control Module<?PHP if ($subtitle !== "") { echo " - " . $subtitle; } ?></h1>
					<?PHP
					// Hide the Generate report modules when we have a submodule in use
					if ($genreportButton === true) { ?>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
					<?PHP } ?>
                    </div>

<?PHP
if (isset($_GET["task"])) {
	$task = $_GET["task"];
	
	switch($task) {
		// Show user list content
		case "create":		
?>
			<div class="col-6">
				<form>
					<div class="form-group row">
						<label for="email" class="col-4 col-form-label">Email</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-at"></i>
									</div>
								</div> 
								<input id="email" name="email" placeholder="j.connor@slipstreamdevs.tech" type="text" required="required" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="Username" class="col-4 col-form-label">Username</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-user-circle"></i>
									</div>
								</div> 
								<input id="Username" name="Username" placeholder="Username" type="text" class="form-control" required="required">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="Password" class="col-4 col-form-label">Password</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-key"></i>
									</div>
								</div>
								<input id="Password" name="Password" placeholder="Password" type="text" class="form-control" required="required">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="text" class="col-4 col-form-label">Confirm Password</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-key"></i>
									</div>
								</div> 
								<input id="text" name="text" placeholder="Confirm Password" type="text" class="form-control" required="required">
							</div>
						</div>
					</div>
					<hr>
					<div class="form-group row">
						<label for="FName" class="col-4 col-form-label">First Name</label> 
						<div class="col-8">
							<input id="FName" name="FName" placeholder="John" type="text" class="form-control" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label for="LName" class="col-4 col-form-label">Last Name</label> 
						<div class="col-8">
							<input id="LName" name="LName" placeholder="Connor" type="text" class="form-control" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label for="position" class="col-4 col-form-label">Job Position</label> 
						<div class="col-8">
							<input id="position" name="position" placeholder="Job Role" type="text" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="LicNo" class="col-4 col-form-label">License No.</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-id-card"></i>
									</div>
								</div> 
								<input id="LicNo" name="LicNo" placeholder="01234567" type="text" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="LicState" class="col-4 col-form-label">License State</label> 
						<div class="col-8">
							<select id="LicState" name="LicState" class="custom-select">
								<option value="vic">Victoria</option>
								<option value="nsw">New South Wales</option>
								<option value="qld">Queensland</option>
								<option value="sa">South Australia</option>
								<option value="wa">Western Australia</option>
								<option value="tas">Tasmania</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="LicType" class="col-4 col-form-label">License Type</label> 
						<div class="col-8">
							<select id="LicType" name="LicType" class="custom-select" multiple="multiple">
								<option value="C">Car</option>
								<option value="R">Rider</option>
								<option value="RE">Restricted Rider</option>
								<option value="LR">Light Rigid</option>
								<option value="MR">Medium Rigid</option>
								<option value="HR">Heavy Rigid</option>
								<option value="HC">Heavy Combination</option>
								<option value="MC">Multi-Combination</option>
							</select>
						</div>
					</div> 
					<div class="form-group row">
						<div class="offset-4 col-8">
							<button name="submit" type="submit" class="btn btn-primary">Submit</button>
							<button name="reset" type="reset" class="btn btn-danger">Reset</button>
						</div>
					</div>
				</form>
			</div>
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
						$('#dataTable-userlist').DataTable( {
							"columnDefs": [
								{ className: "text-center", "targets": [ 8, 9, 10, 11 ] }
							],
							"ajax": '<?PHP echo $baseURL; ?>/API/?task=UserList'
						} );
						
						// Delete Confirmation - Varying Modal Content
						$('#deleteUser').on('show.bs.modal', function (event) {
						  var button = $(event.relatedTarget) // Button that triggered the modal
						  var userName = button.data('name') // Extract info from data-* attributes
						  var userID = button.data('id') // Extract info from data-* attributes

						  $("#deleteUserNameDrop").html(userName);
						})
					} );
					</script>
			        
					<!-- DataTales - Populated via AJAX API call -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-sm table-bordered table-striped" id="dataTable-userlist" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>License Number</th>
											<th>License Type</th>
											<th>License State</th>
											<th>Admin</th>
											<th>Start Date</th>
											<th>Finish Date</th>
											<th>Options</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Username</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>License Number</th>
											<th>License Type</th>
											<th>License State</th>
											<th>Admin</th>
											<th>Start Date</th>
											<th>Finish Date</th>
											<th>Options</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
							<div class="mt-3"><sub>
								<span class="font-weight-bold">Legend:</span><br />
								<i class="text-warning fa-solid fa-pen"></i> - Edit User<br />
								<i class="text-danger fa-solid fa-x"></i> - Delete User<br />
								</sub>
							</div>
                        </div>			
                    </div>
					
					<!-- Modal - Deletion Confirmation -->
					<div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteUserTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title text-danger" id="deleteUserCenterTitle">Confirmation Required!</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
							Are you sure you wish to delete the user "<span id="deleteUserNameDrop"></span>" ?
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-danger">Delete</button>
						  </div>
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