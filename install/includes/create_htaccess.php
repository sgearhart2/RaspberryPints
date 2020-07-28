<?php
$appRoot = realpath(__DIR__ . "/../../") ;

$htaccessString = "";

$htaccessString .= "php_value include_path \".;" . $appRoot . "/\"" . PHP_EOL;
$htaccessString .= "php_value auto_prepend_file \"" . $appRoot . "/vendor/autoload.php\"" . PHP_EOL;


file_put_contents('../../.htaccess', $htaccessString);

?>
