<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Traits\Database;
use PDOException;

class FormController extends Controller {
  use Database;

  function getFormData() {
    if (!$_SERVER['REQUEST_METHOD'] === "POST")
      return;

    header('Content-Type: application/json');
    $name = $_POST['name'];
    $name = htmlspecialchars(trim($name));

    if (isset($name) && !empty($name)) {
      try {
        // db 
        $this->query("INSERT INTO names (name) VALUES (:name)", ['name' => $name]);

        // data
        $response = ['success' => true, 'data' => [$name]];
      } catch (PDOException $e) {
        $response = ['success' => false, 'data' => [$e->getMessage() . " failed to save record in database"]];
      }
    } else {
      $response = ['success' => false, 'data' => ["name can not be empty!"]];
    }

    echo json_encode(['data' => $response]);
  }
}
