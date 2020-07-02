<?php
global $validerror;

require_once 'install_functions.php';
require_once "../../includes/DB.php";

echo "Set version in db.ini file...";
flush();
if(file_exists(__DIR__."/../../includes/db.ini")) {
  $result = $DB->get(
      "select * from config where configName = ?;",
      [
        ['type' => DB::BIND_TYPE_STRING, 'value' => 'version']
      ]
    );
  if(count($result) > 0) {
    $version = $result[0]['configValue'];

    $dbIni = parse_ini_file(__DIR__."/../../includes/db.ini");
    $dbIni['config']['version'] = $version;

    file_put_contents('../../includes/db.ini', create_ini_string($dbIni));
  }
  else {
    $validerror .= "<br><strong>Cannot write the DB version. Could not find the \"version\" config value in the database. See the RPints Installation page on www.raspberrypints.com.</strong>";
  }
}
else {
  $validerror .= "<br><strong>Cannot write the DB version. The db.ini file has not been created. See the RPints Installation page on www.raspberrypints.com.</strong>";
}
echo "Success!<br>";
flush();
 ?>
