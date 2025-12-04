<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();

use Dotenv\Dotenv;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

require_once __DIR__ . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
