<?php
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
?>
