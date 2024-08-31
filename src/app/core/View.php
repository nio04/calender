<?php

namespace App\Core;

class View {
  function render($file, $data = []) {
    extract($data);
    include __DIR__ . "/../../../views/" . $file . ".view.php";
  }
}
