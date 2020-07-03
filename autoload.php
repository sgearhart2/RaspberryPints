<?php
$namespaces = [
  'RaspberryPints' => __DIR__ . "/includes/",
  'RaspberryPints\\Admin\\Managers' => __DIR__ . "/admin/includes/managers/",
  'RaspberryPints\\Admin\\Models' => __DIR__ . "/admin/includes/models/"
];

spl_autoload_register(function($class) use ($namespaces) {
  $classParts = explode('\\', $class);
  $className = array_pop($classParts);
  $namespace = implode('\\', $classParts);

  if(!array_key_exists($namespace, $namespaces)) {
    throw new Exception("Namespace \"$namespace\" is not defined in the autoloader.");
  }

  $classPath = $namespaces[$namespace] . $className . ".php";

  if(!file_exists($classPath)) {
    throw new Exception("Class \"$class\" not found.");
  }
  require_once($classPath);
});
?>
