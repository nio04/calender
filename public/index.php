<?php

include __DIR__ . "/../helper.php";

use Framework\Router;

require __DIR__ . "/../vendor/autoload.php";

$router = new Router();

$router->autoRegisterRoute();
$router->dispatch();
