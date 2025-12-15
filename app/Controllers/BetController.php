<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\Bet;
use App\Models\Group;
use App\Models\GroupMatch;

class BetController extends BaseController
{
    public function placeBet(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        if ($request->method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $groupId = (int)$request->body['group_id'] ?? 0;
        $matchId = (int)$request->body['match_id'] ?? 0;
        $homeScore = (int)$request->body['home_score'] ?? 0;
        $awayScore = (int)$request->body['away_score'] ?? 0;

        if ($groupId <= 0 || $matchId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'group_id et match_id requis']);
            return;
        }

        if ($homeScore < 0 || $awayScore < 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Scores invalides']);
            return;
        }

        $group = new Group();
        if (!$group->isMember($groupId, $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Vous n\'etes pas membre de ce groupe']);
            return;
        }

        $groupMatch = new GroupMatch();
        if (!$groupMatch->isMatchInGroup($groupId, $matchId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Ce match n\'est pas disponible dans ce groupe']);
            return;
        }

        $bet = new Bet();
        $result = $bet->createOrUpdate([
            'group_id' => $groupId,
            'match_id' => $matchId,
            'user_id' => $userId,
            'home_score' => $homeScore,
            'away_score' => $awayScore
        ]);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Pari enregistre']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'enregistrement']);
        }
    }

    public function getUserBets(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $groupId = (int)($request->params['id'] ?? 0);

        if ($groupId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'group_id requis']);
            return;
        }

        $group = new Group();
        if (!$group->isMember($groupId, $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Vous n\'etes pas membre de ce groupe']);
            return;
        }

        $bet = new Bet();
        $bets = $bet->findByGroupAndUser($groupId, $userId);

        http_response_code(200);
        echo json_encode(['success' => true, 'bets' => $bets]);
    }

    public function getLeaderboard(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifie']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $groupId = (int)($request->params['id'] ?? 0);

        if ($groupId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'group_id requis']);
            return;
        }

        $group = new Group();
        if (!$group->isMember($groupId, $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Vous n\'etes pas membre de ce groupe']);
            return;
        }

        $bet = new Bet();
        $leaderboard = $bet->getGroupLeaderboard($groupId);

        http_response_code(200);
        echo json_encode(['success' => true, 'leaderboard' => $leaderboard]);
    }

    public function calculateMatchPoints(Request $request): void
    {
        $matchId = (int)($request->params['id'] ?? 0);

        if ($matchId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'match_id requis']);
            return;
        }

        $bet = new Bet();
        $bet->calculatePointsForMatch($matchId);

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Points calcules']);
    }
}
