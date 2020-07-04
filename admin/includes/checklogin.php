<?php
use RaspberryPints\DB;

session_start();
$session=session_id();
$time=time();
$time_check=$time-1800; //SET TIME 10 Minute

// username and password sent from form
$myusername=$_POST['myusername'];
$mypassword=md5($_POST['mypassword']);


$DB = DB::getInstance();

$sql = "SELECT * FROM users WHERE username = ? and password = ?";
$result = $DB->get($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $myusername],
  ['type' => DB::BIND_TYPE_STRING, 'value' => $mypassword]
]);



// If result matched $myusername and $mypassword, table row must be 1 row
if(count($result) == 1){
// Register $myusername, $mypassword and redirect to file "admin.php"
$_SESSION['myusername'] =$myusername;
$_SESSION['mypassword'] =$mypassword;
//session_register("myusername");
//session_register("mypassword");

echo "<script>location.href='../admin.php';</script>";
}
else {

echo "<script>location.href='../index2.php';</script>";
}
?>
