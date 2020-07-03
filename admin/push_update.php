<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
header("location:index.php");
}

use RaspberryPints\DB;
$DB = DB::getInstance();

// Get values from form
$name = $_POST['name'];
$style = $_POST['style'];
$notes = $_POST['notes'];
$og = $_POST['og'];
$fg = $_POST['fg'];
$srm = $_POST['srm'];
$ibu = $_POST['ibu'];
$active = $_POST['active'];
$tapnumber = $_POST['tapnumber'];
$beerid = $_POST['beerid'];



// update data in mysql database
$sql = "UPDATE beers SET name = ?, style = ?, notes = ?, og = ?, fg = ?, srm = ?,
ibu = ?, active = ?, tapnumber =  WHERE beerid = ?";
$result = $DB->execute($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $name],
  ['type' => DB::BIND_TYPE_INT, 'value' => $style],
  ['type' => DB::BIND_TYPE_STRING, 'value' => $notes],
  ['type' => DB::BIND_TYPE_DOUBLE, 'value' => $og],
  ['type' => DB::BIND_TYPE_DOUBLE, 'value' => $fg],
  ['type' => DB::BIND_TYPE_DOUBLE, 'value' => $srm],
  ['type' => DB::BIND_TYPE_DOUBLE, 'value' => $ibu],
  ['type' => DB::BIND_TYPE_STRING, 'value' => $active],
  ['type' => DB::BIND_TYPE_INT, 'value' => $tapnumber],
  ['type' => DB::BIND_TYPE_INT, 'value' => $beerid]
])

// if successfully updated.
if($result){
  echo "Successful";
  echo "<BR>";
  echo "<a href='beer_main.php'>Back To Beers</a>";
}

else {
  echo "ERROR";
}
?>
