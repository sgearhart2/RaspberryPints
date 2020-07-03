<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
header("location:index.php");
}

use RaspberryPints\DB;
$DB = DB::getInstance();

$sql = "SELECT name, username, email FROM users WHERE username = ?";
$result = $DB->get($sql, [
  ['type' => DB::BIND_TYPE_STRING, 'value' => $_SESSION['myusername']]
]);

if(count($result) == 0) {
  // user not found, redirect to login
}

$user = $result[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>RaspberryPints</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/wysiwyg.css" rel="stylesheet" type="text/css" />
<!-- Theme Start -->
<link href="styles.css" rel="stylesheet" type="text/css" />
<!-- Theme End -->
<link href='http://fonts.googleapis.com/css?family=Fredoka+One' rel='stylesheet' type='text/css'>
</head>
	<!-- Start Header  -->
<?php
include 'header.php';
?>
	<!-- End Header -->

    <!-- Top Breadcrumb Start -->
    <div id="breadcrumb">
    	<ul>
        	<li><img src="img/icons/icon_breadcrumb.png" alt="Location" /></li>
        	<li><strong>Location:</strong></li>
            <li class="current">My Account</li>
        </ul>
    </div>
    <!-- Top Breadcrumb End -->

    <!-- Right Side/Main Content Start -->
    <div id="rightside">


		<div class="contentcontainer med left">
            <div class="headings alt">
                <h2>Account Info</h2>
            </div>
            <div class="contentbox">
			<p style="padding:0px;margin:0px">
 <font size="2" Color="Black" font-family="Impact">Name:</font>
 <?= $user['name']?>
 <br />
 <font size="2" Color="Black" font-family="Impact">Username:</font>
 <?= $user['username']?>
<br />
<font size="2" Color="Black" font-family="Impact"> Email:</font>
<?= $user['name']?>
<br />
<br />

     </div>

    <!-- Start Footer -->
<?php
include 'footer.php';
?>

	<!-- End Footer -->
           </div>
    </div>
    <!-- Right Side/Main Content End -->

	<!-- Start Left Bar Menu -->
<?php
include 'left_bar.php';
?>
	<!-- End Left Bar Menu -->
	<!-- Start Js  -->
<?php
include 'scripts.php';
?>
	<!-- End Js -->
    <!--[if IE 6]>
    <script type='text/javascript' src='scripts/png_fix.js'></script>
    <script type='text/javascript'>
      DD_belatedPNG.fix('img, .notifycount, .selected');
    </script>
    <![endif]-->
</body>
</html>
