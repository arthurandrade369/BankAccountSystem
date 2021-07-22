<?php

define('local', 'localhost');
define('bd', 'bank');
define('usuario', 'root');
define('senha', '12345');
define('DB_CHARSET', 'utf8_general_ci');

try {
  $pdo = new PDO('mysql:host=' . local . ';dbname=' . bd, usuario, senha);
} catch (PDOException $e) {
  echo "Erro ao tentar conectar ao banco<br>ERRO: " . $e;
}