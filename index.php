<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Router;
use App\Controllers\HomeController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
