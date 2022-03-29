<?PHP 
/*
 * API/db.inc.php - Database configuration
 *
 * NOTES: We are using PHP PDO as it gives us a common interface for accessing
 *			over 12 types of databases, meaning we can change from SQLite to Postgres
 *			with minimal code changes.
 *	  		PDO->Prepare() also lets us prevent SQL injection attacks! 
 *
 * Last Modified: 2022/03/29 - By Adam Mutimer
 */
 
 $databaseFile = "../database/database.db";

// Attempt connection to the database
try {
	$pdo = new PDO("sqlite:$databaseFile");
	
	// Connection attributes
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Errormode --> exceptions

// Catch Errors and the error message	
} catch (PDOException $e) {
	echo "Database Error: ".$e->getMessage();
} catch (Exception $e) {
	echo "General Error: ".$e->getMessage();
}

?>