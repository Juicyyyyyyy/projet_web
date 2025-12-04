<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

// Auth Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Home Route
$router->get('/', [HomeController::class, 'index']);
