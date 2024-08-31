<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
  function index() {
    $this->render("home");
  }
}
