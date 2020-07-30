<?php declare(strict_types = 1);
ini_set('display_errors', 1);
// Set user session time - 10 min
session_set_cookie_params(600,'/');

include_once 'config/db_config.php';
include_once 'auload.php';

$request = new \App\Service\Request();
$router = new \App\Service\Router();
$router->parse($request);
