<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
header("location:index.php");
}
use RaspberryPints\DB;
use RaspberryPints\ConfigNames;


// Get values from form
$header_text_trunclen=$_POST['header_text_trunclen'];




// update data in mysql database
$DB = DB::getInstance();
$sql = "UPDATE config SET configValue = ? WHERE configName = ?";
$result = $DB->execute($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $header_text_trunclen ],
  ['type' => DB::BIND_TYPE_STRING, 'value' => 'headerTextTruncLen']
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
