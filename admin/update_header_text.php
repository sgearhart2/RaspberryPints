<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
  header("location:index.php");
}
use RaspberryPints\ConfigNames;

// Get values from form
$header_text = $_POST['header_text'];

// update data in mysql database
$DB = DB::getInstance();
$sql = "UPDATE config SET configValue = ? WHERE configName = ?";
$result = $DB->execute($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $header_text],
  ['type' => DB::BIND_TYPE_STRING, 'value' => ConfigNames::HeaderText]
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
