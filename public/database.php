<?php

$dsn = "mysql:host=localhost;dbname=test;";
$username = "nishat";
$password = '1234';
$db;

try {
  $db = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ]);
} catch (PDOException $e) {
  throw new Exception("Database connection failed: " . $e->getMessage());
}

function query($db, $sql, $params = []) {
  $stmt = $db->prepare($sql);

  foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
  }

  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_OBJ);
}
