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
			$genreportButton = false;
			break;
		
		case "list":
			$subtitle = "Group List";
			$genreportButton = true;
			break;
		
		case "modify":
			$subtitle = "Modify Group";
			$genreportButton = false;
			break;
		
		case "memberControl":
			$subtitle = "Member Control";
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
                        <h1 class="h3 mb-0 text-gray-800">Group Control Module<?PHP if ($subtitle !== "") { echo " - " . $subtitle; } ?></h1>
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
				// Adam: Lets use the API to fill the drop down selections ;) 
				$(document).ready(function() {
					var dropdownManager = $('#Manager');
					var dropdownSupervisor = $('#Supervisor');
					
					dropdownManager.empty(); 	// Flush all values
					dropdownSupervisor.empty(); // Flush all values
					
					// Set Default Placeholders
					dropdownManager.append('<option selected="true" disabled>Select a Manager</option>');
					dropdownManager.prop('selectedIndex', 0);

					dropdownSupervisor.append('<option selected="true" disabled>Select a Supervisor</option>');
					dropdownSupervisor.prop('selectedIndex', 0);
					
					// JSON Request no ajax this time.. we work or fail
					var url = '<?PHP echo $baseURL; ?>/API/?task=UserList&opt=selection'
					
					$.getJSON(url, function (data) {
						$.each(data, function (key, entry) {
							dropdownManager.append($('<option></option>').attr('value', entry.id).text(entry.text));
							dropdownSupervisor.append($('<option></option>').attr('value', entry.id).text(entry.text));
						})
					});
					
					// When page is finished loading in browser override default form action
					$("#createGroupForm").submit(function(event){
						// cancels the form submission
						event.preventDefault();
						// instead of default action run javascript function below
						submitForm("createGroupForm"); // Our custom action/function
					});
					
					// The custom form submission function
					function submitForm(form){
						if (form == "createGroupForm") {
							var formData = $("#createGroupForm").serializeJSON(); // Encode entire form as JSON object
							$.ajax({
								url:'<?PHP echo $baseURL; ?>/API/?task=GroupCreate',
								type:'POST',
								data: formData,
								success:function(response){
									var msg = "";
									var responseData = jQuery.parseJSON(response);
									console.log(response); // Debugging purposes
									if (responseData.status == 1) {
										window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=groupMod&task=list";
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
			<div class="col-4">
				<form id="createGroupForm">
					<div class="form-group row">
						<label for="Name" class="col-4 col-form-label">Group Name</label> 
						<div class="col-8">
							<input id="Name" name="Name" type="text" required="required" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="Desc" class="col-4 col-form-label">Description</label> 
						<div class="col-8">
							<textarea id="Desc" name="Desc" cols="40" rows="5" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label for="Location" class="col-4 col-form-label">Location</label> 
						<div class="col-8">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-globe"></i>
									</div>
								</div> 
								<input id="Location" name="Location" placeholder="Melbourne, Victoria" type="text" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="manager" class="col-4 col-form-label">Manager</label> 
						<div class="col-8">
							<select id="Manager" name="Manager" class="custom-select" required="required">
								<option value="1">USERNAME (Lname + FName)</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="supervisor" class="col-4 col-form-label">Supervisor</label> 
						<div class="col-8">
							<select id="Supervisor" name="Supervisor" class="custom-select">
								<option value="rabbit">USERNAME (LNAME, FNAME)</option>
							</select>
						</div>
					</div> 
					<div class="form-group row">
						<div class="offset-4 col-8">
							<button name="submit" type="submit" class="btn btn-primary">Submit</button>
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
						var groupID; // Kinda like a global
						
						$(document).ready(function() {
							// Populate Datatable
							$('#dataTable-grouplist').DataTable( {
								"columnDefs": [
									{ className: "text-center", "targets": [ 5, 6 ] }
								],
								"ajax": '<?PHP echo $baseURL; ?>/API/?task=GroupList'
							} );
							
							// Delete Confirmation - Varying Modal Content
							$('#deleteGroup').on('show.bs.modal', function (event) {
							  var button = $(event.relatedTarget) // Button that triggered the modal
							  var groupName = button.data('name') // Extract info from data-* attributes
							  groupID = button.data('id') // Extract info from data-* attributes

							  $("#deleteGroupNameDrop").html(groupName);
							})
							
							// Delete the group
							$("#deleteConfirmed").click(function (){
								console.log(groupID);
								$.ajax({
									url:'<?PHP echo $baseURL; ?>/API/?task=GroupDel',
									type:'POST',
									data: 'target=' + groupID,
									success:function(response){
										var msg = "";
										var responseData = jQuery.parseJSON(response);
										console.log(response); // Debugging purposes
										if (responseData.status == 1) {
											window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=groupMod&task=list";
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
                                <table class="table-sm table-bordered table-striped" id="dataTable-grouplist" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Location</th>
                                            <th>Manager</th>
                                            <th>Supervisor</th>
                                            <th>User Count</th>
											<th class="text-center">Options</th>
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
											<th class="text-center">Options</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
							<div class="mt-3"><sub>
								<span class="font-weight-bold">Legend:</span><br />
								<i class="text-success fa-solid fa-users"></i> - Group Members<br />
								<i class="text-warning fa-solid fa-pen"></i> - Edit Group<br />
								<i class="text-danger fa-solid fa-x"></i> - Delete Group<br />
								</sub>
							</div>
                        </div>
                    </div>
					
					<!-- Modal - Deletion Confirmation -->
					<div class="modal fade" id="deleteGroup" tabindex="-1" role="dialog" aria-labelledby="deleteGroupTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title text-danger" id="deleteGroupCenterTitle">Confirmation Required!</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
							Are you sure you wish to delete the group "<span id="deleteGroupNameDrop"></span>" ?
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
							<button id="deleteConfirmed" type="button" class="btn btn-danger">Delete</button>
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
						var groupID; // Kinda like a global
						$(document).ready(function() {
							// Auto Fill the supervisor and manager drop down selections, Needs to be done before we prefil form
							var dropdownManager = $('#Manager');
							var dropdownSupervisor = $('#Supervisor');
							
							dropdownManager.empty(); 	// Flush all values
							dropdownSupervisor.empty(); // Flush all values
							
							// Set Default Placeholders
							dropdownManager.append('<option selected="true" disabled>Select a Manager</option>');
							dropdownManager.prop('selectedIndex', 0);

							dropdownSupervisor.append('<option selected="true" disabled>Select a Supervisor</option>');
							dropdownSupervisor.prop('selectedIndex', 0);
							
							// JSON Request no ajax this time.. we work or fail
							var url = '<?PHP echo $baseURL; ?>/API/?task=UserList&opt=selection'

							$.getJSON(url, function (data) {
								$.each(data, function (key, entry) {
									dropdownManager.append($('<option></option>').attr('value', entry.id).text(entry.text));
									dropdownSupervisor.append($('<option></option>').attr('value', entry.id).text(entry.text));
								})
								
								// Now that the drop downs have been populated, lets populate target form with target user data
								$.ajax({
									url:'<?PHP echo $baseURL; ?>/API/?task=GroupModify&opt=fetch&target=<?PHP echo $target; ?>',
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
							});
							
							// Process form information and submit!
							// When page is finished loading in browser override default form action
							$("#modGroupForm").submit(function(event){
								// cancels the form submission
								event.preventDefault();
								// instead of default action run javascript function below
								submitForm("modGroupForm"); // Our custom action/function
							});
							
							// The custom form submission function
							function submitForm(form){
								if (form == "modGroupForm") {
									var formData = $("#modGroupForm").serializeJSON(); // Encode entire form as JSON object
									$.ajax({
										url:'<?PHP echo $baseURL; ?>/API/?task=GroupModify&opt=update&target=<?PHP echo $target; ?>',
										type:'POST',
										data: formData,
										success:function(response){
											var msg = "";
											var responseData = jQuery.parseJSON(response);

											if (responseData.status == 1) {
												window.location.href = "<?PHP echo $baseURL; ?>/dashboard.php?module=groupMod&task=list";
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

					<div class="col-4">
						<form id="modGroupForm">
							<div class="form-group row">
								<label for="Name" class="col-4 col-form-label">Group Name</label> 
								<div class="col-8">
									<input id="Name" name="Name" type="text" required="required" class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label for="Desc" class="col-4 col-form-label">Description</label> 
								<div class="col-8">
									<textarea type="textarea" id="Desc" name="Desc" cols="40" rows="5" class="form-control"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="Location" class="col-4 col-form-label">Location</label> 
								<div class="col-8">
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<i class="fa fa-globe"></i>
											</div>
										</div> 
										<input id="Location" name="Location" placeholder="Melbourne, Victoria" type="text" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="manager" class="col-4 col-form-label">Manager</label> 
								<div class="col-8">
									<select id="Manager" name="Manager" class="custom-select" required="required">
										<option value="1">USERNAME (Lname + FName)</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="supervisor" class="col-4 col-form-label">Supervisor</label> 
								<div class="col-8">
									<select id="Supervisor" name="Supervisor" class="custom-select">
										<option value="rabbit">USERNAME (LNAME, FNAME)</option>
									</select>
								</div>
							</div> 
							<div class="form-group row">
								<div class="offset-4 col-8">
									<button name="submit" type="submit" class="btn btn-primary">Submit</button>
									<button name="reset" type="reset" class="btn btn-danger">Reset</button>
								</div>
							</div>
							<div class="form-group row" id="message"></div>
						</form>
					</div>

<?PHP
			break;
		case "memberControl":
			echo "Incomplete Module";
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