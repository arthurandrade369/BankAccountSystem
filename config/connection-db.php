<?php

define('local', 'localhost');
define('bd', 'bank');
define('usuario', 'root');
define('senha', '12345');
define('DB_CHARSET', 'utf8_general_ci');


// try {
//   $pdo = new PDO('mysql:host=' . local . ';dbname=' . bd, usuario, senha);
//   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//   die("ERROR: Could not connect " . $e->getMessage());
// }
class Connection
{
  public static $instance;

  private function __construct()
  {
    //
  }

  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      self::$instance = new PDO(
        'mysql:host=' . local . ';dbname=' . bd,
        usuario,
        senha,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
      self::$instance->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
      );
      self::$instance->setAttribute(
        PDO::ATTR_ORACLE_NULLS,
        PDO::NULL_EMPTY_STRING
      );
    }

    return self::$instance;
  }
}
