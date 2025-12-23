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

// Home Route
$router->get('/', [HomeController::class, 'index']);

// Group Routes (Web)
$router->get('/groups', [GroupController::class, 'index']);
$router->get('/groups/create', [GroupController::class, 'create']);
$router->post('/groups/create', [GroupController::class, 'store']);
$router->get('/groups/join', [GroupController::class, 'join']);
$router->post('/groups/join', [GroupController::class, 'joinStore']);
$router->get('/groups/{id}', [GroupController::class, 'show']);
$router->get('/groups/{id}/add-match', [GroupController::class, 'addMatchView']);

// Group Routes (API)
$router->get('/api/groups', [GroupController::class, 'listApi']);
$router->post('/api/groups', [GroupController::class, 'createApi']);
$router->get('/api/groups/{id}', [GroupController::class, 'getGroupApi']);

// Group Members Routes (API - admin only)
$router->post('/api/groups/{id}/members', [GroupController::class, 'addUserToGroup']);
$router->post('/api/groups/{id}/members/remove', [GroupController::class, 'removeUserFromGroup']);

// Group Matches Routes (API)
$router->get('/api/groups/{id}/matches', [GroupController::class, 'getGroupMatches']);
$router->post('/api/groups/{id}/matches', [GroupController::class, 'addMatchToGroup']);
$router->post('/api/groups/{id}/matches/remove', [GroupController::class, 'removeMatchFromGroup']);

// Bet Routes
$router->post('/bets', [BetController::class, 'placeBet']);
$router->get('/groups/{id}/bets', [BetController::class, 'getUserBets']);
$router->get('/groups/{id}/leaderboard', [BetController::class, 'getLeaderboard']);

// Admin/System Route
$router->post('/matches/{id}/calculate', [BetController::class, 'calculateMatchPoints']);
