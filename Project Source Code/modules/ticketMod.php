<?PHP
/* modules/ticketMod.php - Asset Management Module
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
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Task Heading</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="card shadow mb-4" style="width: 100%;">
							<div class="card-body">
							<!--- EDIT IN THIS SECTION --->
									<pre>LIST ALL TICKETS</pre>
									<!--- END - EDIT IN THIS SECTION --->
							</div>
						</div>
					</div>

<?PHP
			break;
			
			case "create":
				// Put Code for this task, basically HTML
?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Task Heading</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="card shadow mb-4" style="width: 100%;">
							<div class="card-body">
									<!--- EDIT IN THIS SECTION --->
									<pre>CREATE TICKET</pre>
									<!--- END - EDIT IN THIS SECTION --->
							</div>
						</div>
					</div>

<?PHP 
			break;
	}
}
?>
