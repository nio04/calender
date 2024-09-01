<?php

namespace App\Traits;

use PDO;
use PDOException;
use Exception;

trait Connect {
  private $db;

  /**
   * Initialize the PDO connection.
   *
   * @return void
   * @throws \Exception if the connection fails.
   */
  public function connect(): void {
    $dsn = 'mysql:host=localhost;dbname=test';
    $username = 'nishat';
    $password = '1234';

    try {
      $this->db = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ]);
    } catch (PDOException $e) {
      throw new Exception("Database connection failed: " . $e->getMessage());
    }
  }

  /**
   * Get the PDO instance.
   *
   * @return PDO The PDO instance.
   */
  protected function getDb(): PDO {
    return $this->db;
  }
}
