<?php
global $dbuser;
global $dbpass1;
global $servername;

function create_ini_string(array $iniSettings) : string {
  $res = array();
  foreach($iniSettings as $key => $val) {
    if(is_array($val)) {
      $res[] = "[$key]";
      foreach($val as $sKey => $sVal) {
        $res[] = "$sKey = " . (is_numeric($sVal) ? $sVal : '"' . $sVal . '"');
      }
    }
    else {
      $res[] = "$key = " . (is_numeric($val) ? $val : '"' . $val . '"');
    }
  }

  return implode(PHP_EOL , $res);
}
//-----------------Create the db.ini file-----------------
echo "Update db.ini file...";
flush();

$dbIniSettings = [
  'database connection info' => [
    'server' => $servername,
    'user' => $dbuser,
    'password' => $dbpass1,
    'db' => 'raspberrypints'
  ]
];

file_put_contents('../../includes/db.ini', create_ini_string($dbIniSettings));

echo "Success!<br>";
flush();

?>
