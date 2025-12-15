<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\GroupController;

// Auth Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Group Routes
$router->get('/mygroups', [GroupController::class, 'index']);
$router->get('/groups/create', [GroupController::class, 'create']);
$router->post('/groups/create', [GroupController::class, 'store']);

// Home Route
$router->get('/', [HomeController::class, 'index']);
