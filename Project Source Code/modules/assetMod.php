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
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Task Heading</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
									<pre>LIST ALL ASSETS</pre>
							</div>
						</div>
					</div>

<?PHP
			break;
			
			case "create":
				// Put Code for this task, basically HTML
?>
 <!-- HTML GOES HERE -->
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Task Heading</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
									<pre>CREATE ASSETS</pre>
							</div>
						</div>
					</div>
 
<?PHP 
			break;
	}
}
?>
