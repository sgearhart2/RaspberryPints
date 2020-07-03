<?php
use RaspberryPints\DB;

// value sent from form
$email_to = $_POST['email_tou'];

$DB = DB::getInstance();
$sql = "SELECT username FROM users WHERE email = ?";
$result = $DB->get($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $email_to]
]);
// compare if $count =1 row
if(count($result) == 1) {
  $your_username = $result[0]['username'];

  // ---------------- SEND MAIL FORM ----------------

  // send e-mail to ...
  $to = $email_to;

  // Your subject
  $subject = "Your username";

  // From
  $header = "from: Support <shawn@besmartdesigns.com>";

  // Your message
  $messages .= "This is your username to your login ( $your_username ) \r\n";

  // send email
  $sentmail = mail($to,$subject,$messages,$header);

}

// else if $count not equal 1
else {
  echo "Error ";
}
echo "An email has been sent including the info you have requested.";
?>
<a href="../index.php">Click Here<a/> to go back to the login.
