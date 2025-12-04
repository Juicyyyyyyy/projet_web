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

// Auth Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Home Route
$router->get('/', [HomeController::class, 'index']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
