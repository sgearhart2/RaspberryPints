<?
use RaspberryPints\DB;

// value sent from form
$email_to=$_POST['email_to'];

$DB = DB::getInstance();
$sql = "SELECT password FROM users WHERE email=?";
$result = $DB->get($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $email_to]
]);
// compare if $count =1 row
if(count($result) == 1){

  $your_password = $rows[0]['password'];

  // ---------------- SEND MAIL FORM ----------------

  // send e-mail to ...
  $to = $email_to;

  // Your subject
  $subject = "Your password here";

  // From
  $header = "from: Support";

  // Your message
  $messages .= "This is your password to your login( $your_password ) \r\n";
  $messages .= "Please Purge this email and update the password within your admin panel after receiving this email. \r\n";

  // send email
  $sentmail = mail($to,$subject,$messages,$header);

}

// else if $count not equal 1
else {
  echo "We Can not find your email in our Database, please go back and retry.";
}

echo "An email has been sent including the info you have requested.";
?>
<a href="../index.php">Click Here<a/> to go back to the login.
