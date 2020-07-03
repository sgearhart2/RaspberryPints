<head></head>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Installation Processor</title>
</head>
<body>
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

require_once __DIR__.'/sql_parse.php';

//Process and load form data
$servername = $_POST["servername"];
$rootpass = $_POST["rootpass"];
$dbuser = $_POST["dbuser"];
$dbpass1 = $_POST["dbpass1"];
$dbpass2 = $_POST["dbpass2"];
$adminuser = $_POST["adminuser"];
$adminpass1 = $_POST["adminpass1"];
$adminpass2 = $_POST["adminpass2"];
$action = $_POST["selectaction"];

//Create the MD5 hash value for the admin password
$adminhash = md5($adminpass1);

//-----------------Do some validation---------
$validerror ='';
//Validate DB password
echo "Validating Entries...";
flush();

if ($dbpass1 != $dbpass2)
	{
		$validerror .= "<br><strong>Your Database passwords do not match.</strong>";
	}

//Validate admin password
if ($adminpass1 != $adminpass2) {
		$validerror .= "<br><strong>Your Administrator account passwords do not match.</strong>";
	}

echo "Success!<br>";
flush();

//Validate DB connectivity
echo "Checking DB connectivity...";
flush();
$rootDbInfo = [
	'server' => $servername,
	'user' => "root",
	'password' => $rootpass
];
require '../../includes/DB.php';
use RaspberryPints\DB;

try {
	$DB = DB::getInstance($rootDbInfo);
}
catch(Exception $ex) {
	$validerror .= "<br><strong>Cannot connect the the database using the supplied information. Error message : \"" . $ex->getMessage() . "\"</strong>";
}

echo "Success!<br>";
flush();

//Validate that the config directories are writable
echo "Checking config folder permissions...";
flush();
if (!is_writable(dirname('../../includes/functions.php')))
{
   $validerror .= "<br><strong>Cannot write the configuration files. Please check the /includes/ folder permissions. See the RPints Installation page on www.raspberrypints.com.</strong>";
}

echo "Checking includes/db.ini permissions...";
flush();
if (!is_writable(dirname('../../includes/db.ini')))
{
	$validerror .= "<br><strong>Cannot write the db ini file. Please check the /includes/ folder permissions. See the RPints Installation page on www.raspberrypints.com.</strong>";
}

if (!is_writable(dirname('../../admin/includes/checklogin.php')))
{
$validerror .= "<br><strong>Cannot write the configuration files. Please check the /admin/includes/ folder permissions. See the RPints Installation page on www.raspberrypints.com.</strong>";
}
echo "Success!<br>";
flush();

  //##TODO## Check if administrator account already exists



//Display errors and die
if ($validerror !='')
	{
		echo "<html><body>";
		echo $validerror;
		echo "<br /><br />Please press the back button on your browser to fix these errors";
		echo "</body></html>";
		die();
	}

if ($action == 'remove')
{
	echo "Deleting raspberrypints database...";
	flush();

	$sql = "DROP database raspberrypints;";
	$DB->execute($sql);

	echo "Success!<br>";
	flush();

	echo "Removing configuration files...";
	flush();
	unlink('../../includes/db.ini');
	echo "Success!<br>";
	flush();
}

if ($action == 'install')
{

	//-----------------Create apache .htaccess----------------
	include "create_htaccess.php";

	//-----------------Create the db.ini file-----------------
	include "create_db_ini.php";

	//-----------------Create RPints User--------------------------
	echo "Creating RPints database user...";
	flush();

	$sql = "GRANT ALL ON *.* TO '" . $dbuser . "'@'" . $servername . "' IDENTIFIED BY '" . $dbpass1 . "' WITH GRANT OPTION;";
	$DB->execute($sql);

	echo "Success!<br>";
	flush();

	//-----------------Run The Schema File-------------------------
	echo "Running Database Script...";
	flush();
	$dbms_schema = "../../sql/schema.sql";


	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema)) or die('Cannot find SQL schema file. ');

	$sql_query = remove_remarks($sql_query);
	$sql_query = remove_comments($sql_query);
	$sql_query = split_sql_file($sql_query, ';');

	foreach($sql_query as $sql)
	{
		// Trim whitespace from statement
		$sql = trim($sql);
		// replace relative paths for load files with absolute path based on script location
		$sql = preg_replace("/(LOAD DATA INFILE ')\./i", "$1" . __DIR__ . "/../../sql", $sql);

		$DB->execute($sql) or die("error in query : $sql");
	}

	echo "Success!<br>";
	flush();


	//-----------------Create RaspberryPints version file----------
	include "set_db_ini_version.php";

	//-----------------Add the admin user to the Users DB----------
	echo "Adding new admin user...";
	flush();
	$currentdate = Date('Y-m-d H:i:s');
	$sql = "INSERT INTO users (username, password, name, email, createdDate, modifiedDate) VALUES (?, ?, ?, ?, ?, ?);";
	$params = [
		['type' => DB::BIND_TYPE_STRING, 'value' => $adminuser],
		['type' => DB::BIND_TYPE_STRING, 'value' => $adminhash],
		['type' => DB::BIND_TYPE_STRING, 'value' => 'name'],
		['type' => DB::BIND_TYPE_STRING, 'value' => 'email'],
		['type' => DB::BIND_TYPE_STRING, 'value' => $currentdate],
		['type' => DB::BIND_TYPE_STRING, 'value' => $currentdate]
	];

	$DB->execute($sql, $params);

	echo "Success!<br>";
	flush();
	//-----------------Load the sample data if requested-----------

		if(!empty($_POST['sampledata']))
		{
			echo "Adding sample data...";
			flush();

			$dbms_schema = "../../sql/test_data.sql";


			$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema)) or die('Cannot find SQL schema file. ');

			$sql_query = remove_remarks($sql_query);
			$sql_query = remove_comments($sql_query);
			$sql_query = split_sql_file($sql_query, ';');

			foreach($sql_query as $sql)
			{
				$DB->execute($sql) or die("error in query: $sql");
			}

			echo "Success!<br>";
			flush();
		}
}


if ($action != 'remove')
{
	##TODO## Add better error handling before showing the Success message
	echo '<br /><br /><br /><h3> Congratulations! Your Raspberry Pints has been setup successfully.<br />';
	echo 'Tap List - <a href="http://' . $_SERVER['HTTP_HOST'] . '/index.php">http://' . $_SERVER['HTTP_HOST'] . '/index.php</a><br />';
	echo 'Administration - <a href="http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php">http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php</a><br />';
}

?>
</body>
</html>
