<?php
use RaspberryPints\DB;

session_start();
if(!isset( $_SESSION['myusername'] )){
	//header("location:index.php");
}
require_once '../includes/functions.php';

$DB = DB:getInstance();
$sql = "INSERT INTO beers (name, style, notes, ogEst, fgEst, srmEst, ibuEst, modifiedDate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$DB->execute($sql, [
  ['type' => DB:BIND_TYPE_STRING, 'value' => $_POST['name']],
  ['type' => DB:BIND_TYPE_INT, 'value' => $_POST['style']],
  ['type' => DB:BIND_TYPE_STRING, 'value' => $_POST['notes']],
  ['type' => DB:BIND_TYPE_DOUBLE, 'value' => $_POST['ogEst']],
  ['type' => DB:BIND_TYPE_DOUBLE, 'value' => $_POST['fgEst']],
  ['type' => DB:BIND_TYPE_DOUBLE, 'value' => $_POST['srmEst']],
  ['type' => DB:BIND_TYPE_DOUBLE, 'value' => $_POST['ibuEst']]
])

redirect('../beer_main.php');
?>
