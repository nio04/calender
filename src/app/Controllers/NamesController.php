<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Traits\Database;
use Exception;
use PDOException;

class NamesController extends Controller {
  use Database;

  function getAllNames() {
    header("Content-Type:application/json");

    try {
      $names = $this->query("select name from names");
      $response = ['success' => true, 'data' => $names];
    } catch (PDOException $e) {
      $response = ['success' => false, 'data' => ["something went wrong while retriving data from database"]];
    } catch (Exception $e) {
      $response = [
        'success' => false,
        'data' => [$e->getMessage()]
      ];
    }

    echo json_encode(['data' => $response]);
  }
}
