<?php
global $dbuser;
global $dbpass1;
global $servername;

require_once 'install_functions.php';

//-----------------Create the db.ini file-----------------
echo "Update db.ini file...";
flush();

$dbIniSettings = [
  'database connection info' => [
    'server' => $servername,
    'user' => $dbuser,
    'password' => $dbpass1,
    'db' => 'raspberrypints'
  ],
  'config' => [
    'version' => ''
  ]
];

file_put_contents('../../includes/db.ini', create_ini_string($dbIniSettings));

echo "Success!<br>";
flush();

?>
