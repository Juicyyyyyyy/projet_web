<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\GroupController;
use App\Controllers\BetController;

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

// Group Routes
$router->get('/groups', [GroupController::class, 'getMyGroups']);
$router->post('/groups', [GroupController::class, 'createGroup']);
$router->get('/groups/{id}', [GroupController::class, 'getGroup']);

// Group Members Routes (admin only)
$router->post('/groups/{id}/members', [GroupController::class, 'addUserToGroup']);
$router->post('/groups/{id}/members/remove', [GroupController::class, 'removeUserFromGroup']);

// Group Matches Routes
$router->get('/groups/{id}/matches', [GroupController::class, 'getGroupMatches']);
$router->post('/groups/{id}/matches', [GroupController::class, 'addMatchToGroup']);
$router->post('/groups/{id}/matches/remove', [GroupController::class, 'removeMatchFromGroup']);

// Bet Routes
$router->post('/bets', [BetController::class, 'placeBet']);
$router->get('/groups/{id}/bets', [BetController::class, 'getUserBets']);
$router->get('/groups/{id}/leaderboard', [BetController::class, 'getLeaderboard']);

// Admin/System Route
$router->post('/matches/{id}/calculate', [BetController::class, 'calculateMatchPoints']);
