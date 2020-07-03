<?php
namespace RaspberryPints;

use \mysqli;
use \mysqli_stmt;

// Using Singleton pattern to use one connection throughout the request lifecycle
// https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php

/*
 * DB class to interact with mysql
 * Using Singleton pattern to use one connection throughout the request lifecycle
 * https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
 */
class DB {
  private static array $instances = [];
  private mysqli $conn;
  private string $connString;

  public const BIND_TYPE_STRING = 's';
  public const BIND_TYPE_DOUBLE = 'd';
  public const BIND_TYPE_INT = 'i';
  public const BIND_TYPE_BLOB = 'b';

  private function __construct($dbConnInfo)
  {
    $this->connString = self::getConnString($dbConnInfo);

  	$this->conn = mysqli_connect(
        $dbConnInfo['server'],
        $dbConnInfo['user'],
        $dbConnInfo['password'],
        $dbConnInfo['db'] ?? null
    );

    if($this->conn == false) {
      throw new Exception("Failed to open connection in DB.");
    }
  }

  public function get(string $sql, array $bindVariables = []) : array {
    $stmt = $this->prepareStatement($sql, $bindVariables);

    mysqli_stmt_execute($stmt);
    $meta = mysqli_stmt_result_metadata($stmt);

    while($field = $meta->fetch_field()) {
      $parameters[] = &$row[$field->name];
    }

    call_user_func_array([$stmt, 'bind_result'], $parameters);

    $results = [];
    while(mysqli_stmt_fetch($stmt)) {
      $x = array();

      foreach($row as $key => $val) {
        $x[$key] = $val;
      }
      $results[] = $x;
    }

    return $results;
  }

  public function execute(string $sql, array $bindVariables = []) : bool {
    // if no bind variables, just execute the query without creating a statement
    if(count($bindVariables) == 0) {
      return $this->conn->query($sql);
    }
    $stmt = $this->prepareStatement($sql, $bindVariables);

    return mysqli_stmt_execute($stmt);
  }

  private function prepareStatement(string $sql, array $bindVariables) : mysqli_stmt {
      $stmt = mysqli_prepare($this->conn, $sql);

      if(count($bindVariables) > 0) {
        $bindArgs = [$stmt, ''];

        foreach($bindVariables as $i => $var) {
          $bindArgs[1] .= $var['type'];
          $bindArgs[] = &$bindVariables[$i]['value'];
        }
        call_user_func_array("mysqli_stmt_bind_param", $bindArgs);
      }

      return $stmt;
  }

  /*
   * Returns an instance of this class. Creates one if it hasn't been created
   * yet.
   */
  public static function getInstance(array $dbConnInfo = null) : DB
  {
    if($dbConnInfo == null) {
        if(!file_exists(__DIR__ . '/db.ini')) {
          throw new Exception("Coud not locate file \"db.ini\". Run the install script to generate it.");
        }
        $dbConnInfo = parse_ini_file(__DIR__ . '/db.ini');
    }

    $connString = self::getConnString($dbConnInfo);

    if(!isset(self::$instances[$connString])) {
      self::$instances[$connString] = new DB($dbConnInfo);
    }

    return self::$instances[$connString];
  }

  private static function getConnString($dbConnInfo) : string {
    return $dbConnInfo['user'] . '@' . $dbConnInfo['server'] .
      (isset($dbConnInfo['db']) ? '/' . $dbConnInfo['db'] : '');
  }

  public function __destruct() {
    unset(self::$instances[$this->connString]);

    if(mysqli_close($this->conn) == false) {
      throw new Exception("Error closing DB connection.");
    }
  }
}
