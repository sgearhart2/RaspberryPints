<?php
session_start();
use RaspberryPints\DB;

// Get values from form
$password=md5($_POST['password']);
$email=($_POST['email']);

$DB = DB::getInstance();
// update data in mysql database
$sql = "UPDATE users SET password = ? WHERE email = ?";
$result = $DB->execute($sql, [
		['type' => DB::BIND_TYPE_STRING, 'value' => $password ],
		['type' => DB::BIND_TYPE_STRING, 'value' => $email ]
]);

// if successfully updated.
if($result){
echo "Successful";
echo "<BR>";
echo "<script>location.href='../index.php';</script>";
}

else {
echo "ERROR";
}

?>
