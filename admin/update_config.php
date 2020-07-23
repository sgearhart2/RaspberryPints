<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
  header("location:index.php");
}
use \RaspberryPints\DB;

require_once 'includes/functions.php';

// Get values from form
$configName = $_POST['configName'];
$configValue = $_POST['configValue'];

// update data in mysql database
$DB = DB::getInstance();
$sql = "UPDATE config SET configValue = ? WHERE configName = ?";
$result = $DB->execute($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => strval($configValue)],
  ['type' => DB::BIND_TYPE_STRING, 'value' => strval($configName)]
]);

// if successfully updated.
if($result){
  echo "Successful";
  echo "<BR>";
  echo "<script>location.href='personalize.php';</script>";
}

else {
  echo "ERROR";
}
?>
