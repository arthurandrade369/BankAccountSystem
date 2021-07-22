<?php

define('local', 'localhost');
define('bd', 'bank');
define('usuario', 'root');
define('senha', '12345');
define('DB_CHARSET', 'utf8_general_ci');

class Connectionbd
{

  public function connect()
  {
    try {
      $pdo = new PDO('mysql:host=' . local . ';dbname=' . bd, usuario, senha);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("ERROR: Could not connect" . $e->getMessage());
    }
  }
}
