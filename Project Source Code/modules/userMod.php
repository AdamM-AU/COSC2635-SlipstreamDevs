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
			<script type="text/javascript">
				$(document).ready(function() {
					// When page is finished loading in browser override default form action
					$("#createUserForm").submit(function(event){
						// cancels the form submission
						event.preventDefault();
						// instead of default action run javascript function below
						submitForm("createUserForm"); // Our custom action/function
					});
					
					// The custom form submission function
					function submitForm(form){
						if (form == "createUserForm") {
							var formData = $("#createUserForm").serializeJSON(); // Encode entire form as JSON object
							$.ajax({
								url:'<?PHP echo $baseURL; ?>/API/?task=UserAdd',
								type:'POST',
								data: formData,
								success:function(response){
									var msg = "";
									var responseData = jQuery.parseJSON(response);

									if (responseData.status == 1) {
										window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=userMod&task=list";
									} else {
										// Error Message
										$("#message").addClass("text-danger");
										msg = responseData.message;
									}
									$("#message").html(msg);
								}
							});
						}
					}
				});
						
			</script>
			
			<div class="col-6">
				<form id="createUserForm">
					<div class="form-group row">
						<label for="email" class="col-4 col-form-label">Email</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-at"></i>
									</div>
								</div> 
								<input id="Email" name="Email" placeholder="j.connor@slipstreamdevs.tech" type="text" required="required" class="form-control">
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
								<input id="PasswordConf" name="PasswordConf" placeholder="Confirm Password" type="text" class="form-control" required="required">
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
							<input id="Position" name="Position" placeholder="Job Role" type="text" class="form-control">
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
							<select id="LicType[]" name="LicType[]" class="custom-select" multiple="multiple">
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
							<button name="submit" type="submit" class="btn btn-primary">Create User</button>
							<button name="reset" type="reset" class="btn btn-danger">Reset</button>
						</div>
					</div>
					<div class="form-group row" id="message"></div>
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
					var userID; // Kinda like a global
					var password; // Kinda like a global
					
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
						  userID = button.data('id') // Extract info from data-* attributes

						  $("#deleteUserNameDrop").html(userName);
						})
						
						// Delete the user
						$("#deleteConfirmed").click(function (){
							$.ajax({
								url:'<?PHP echo $baseURL; ?>/API/?task=UserDel',
								type:'POST',
								data: 'target=' + userID,
								success:function(response){
									var msg = "";
									var responseData = jQuery.parseJSON(response);

									if (responseData.status == 1) {
										window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=userMod&task=list";
									} else {
										// Error Message
										$("#message").addClass("text-danger");
										msg = responseData.message;
									}
									$("#message").html(msg);
								}
							});
						});
						
						// UnDelete User - UserUnDel
						$('#unDeleteUser').on('show.bs.modal', function (event) {
						  var button = $(event.relatedTarget) // Button that triggered the modal
						  var userName = button.data('name') // Extract info from data-* attributes
						  userID = button.data('id') // Extract info from data-* attributes

						  $("#unDeleteUserNameDrop").html(userName);
						})
						
						// Delete the user
						$("#unDeleteConfirmed").click(function (){

							$.ajax({
								url:'<?PHP echo $baseURL; ?>/API/?task=UserUnDel',
								type:'POST',
								data: 'target=' + userID,
								success:function(response){
									var msg = "";
									var responseData = jQuery.parseJSON(response);

									if (responseData.status == 1) {
										window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=userMod&task=list";
									} else {
										// Error Message
										$("#message").addClass("text-danger");
										msg = responseData.message;
									}
									$("#message").html(msg);
								}
							});
						});
						
						// Password Confirmation - Varying Modal Content
						$('#passwordResetUser').on('show.bs.modal', function (event) {
						  var button = $(event.relatedTarget) // Button that triggered the modal
						  var userName = button.data('name') // Extract info from data-* attributes
						  userID = button.data('id') // Extract info from data-* attributes
						  password = generatePassword(); // Generate new random password

						  $("#generatedPassword").html(password);
						  $("#passwordResetUserNameDrop").html(userName);
						})
						
						// Delete the user
						$("#passwordResetConfirmed").click(function (){

							$.ajax({
								url:'<?PHP echo $baseURL; ?>/API/?task=UserPassReset',
								type:'POST',
								data: 'target=' + userID,
								success:function(response){
									var msg = "";
									var responseData = jQuery.parseJSON(response);

									if (responseData.status == 1) {
										window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=userMod&task=list";
									} else {
										// Error Message
										$("#message").addClass("text-danger");
										msg = responseData.message;
									}
									$("#message").html(msg);
								}
							});
						});						
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
								<i class=" text-warning fa-solid fa-key"></i> - Reset Password<br />
								<i class="text-primary fa-solid fa-pen"></i> - Edit User<br />
								<i class="text-danger fa-solid fa-x"></i> - Delete User<br />
								<i class="text-success fa-solid fa-check"></i> - Undelete User<br />
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
							<button id="deleteConfirmed" type="button" class="btn btn-danger">Delete</button>
						  </div>
						</div>
					  </div>
					</div>
					
					<!-- Modal - Undeletion Confirmation -->
					<div class="modal fade" id="unDeleteUser" tabindex="-1" role="dialog" aria-labelledby="unDeleteUserTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title text-danger" id="unDeleteUserCenterTitle">Confirmation Required!</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
							Are you sure you wish to restore the user "<span id="unDeleteUserNameDrop"></span>" ?
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
							<button id="unDeleteConfirmed" type="button" class="btn btn-danger">Restore</button>
						  </div>
						</div>
					  </div>
					</div>
					
					<!-- Modal - Password Reset Confirmation -->
					<div class="modal fade" id="passwordResetUser" tabindex="-1" role="dialog" aria-labelledby="passwordResetUserTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title text-danger" id="passwordResetUserCenterTitle">Confirmation Required!</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
							New Password: <span id="generatedPassword"></span><br />
							Reset the password for "<span id="passwordResetUserNameDrop"></span>" ?
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
							<button id="passwordResetConfirmed" type="button" class="btn btn-danger">Reset Password</button>
						  </div>
						</div>
					  </div>
					</div>					
<?PHP
		break;
		
		// Show content for user modification
		case "modify":
		$target = $_GET['target'];
?>
					<script type="text/javascript">
					var userID; // Kinda like a global
					
					$(document).ready(function() {
						// On page load populate target form with target user data
						$.ajax({
							url:'<?PHP echo $baseURL; ?>/API/?task=UserModify&opt=fetch&target=<?PHP echo $target; ?>',
							type:'POST',
							data: '',
							success:function(response){
								var msg = "";
								var responseData = jQuery.parseJSON(response);

								if (responseData.status == 1) {
									populateForm(responseData.data);
								} else {
									// Error Message
									$("#message").addClass("text-danger");
									msg = responseData.message;
								}
								$("#message").html(msg);
							}
						});
						
						// Process form information and submit!
					});
					</script>
					
					<div class="col-6">
						<form id="modUserForm" name"modUserForm">
							<div class="form-group row">
								<label for="email" class="col-4 col-form-label">Email</label> 
								<div class="col-8">
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<i class="fa fa-at"></i>
											</div>
										</div> 
										<input id="Email" name="Email" placeholder="j.connor@slipstreamdevs.tech" type="text" required="required" class="form-control">
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
									<input id="Position" name="Position" placeholder="Job Role" type="text" class="form-control">
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
									<select id="LicType[]" name="LicType[]" class="custom-select" multiple="multiple">
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
									<button name="submit" type="submit" class="btn btn-primary">Update User</button>
									<button name="reset" type="reset" class="btn btn-danger">Reset</button>
								</div>
							</div>
							<div class="form-group row" id="message"></div>
						</form>
					</div>
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