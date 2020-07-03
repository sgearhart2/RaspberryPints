<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
	header("location:index.php");
}
use RaspberryPints\ConfigNames;
use RaspberryPints\DB;



// Get values from form
$name = $_POST['id'];
$config_Value = $_POST['configValue'];
$DB = DB::getInstance();

foreach($_POST as $k => $v){
	// update data in mysql database
	$stmt = $conn->prepare("UPDATE config SET configValue = ? WHERE id = ?");

	$DB->execute($sql, [
		['type' => DB::BIND_TYPE_STRING, 'value' => $v],
		['type' => DB::BIND_TYPE_STRING, 'value' => $k]
	]);
}


echo "<script>location.href='personalize.php';</script>";





?>
