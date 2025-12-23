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
        $groupId = (int) $request->body['group_id'] ?? 0;
        $matchId = (int) $request->body['match_id'] ?? 0;

        // New inputs
        $prediction = $request->body['prediction'] ?? ''; // 'home', 'draw', 'away'
        $homeScore = isset($request->body['home_score']) && $request->body['home_score'] !== '' ? (int) $request->body['home_score'] : null;
        $awayScore = isset($request->body['away_score']) && $request->body['away_score'] !== '' ? (int) $request->body['away_score'] : null;
        $goalDiff = isset($request->body['goal_difference']) && $request->body['goal_difference'] !== '' ? (int) $request->body['goal_difference'] : null;

        // Metadata for determining winner ID
        $homeTeamId = (int) ($request->body['home_team_id'] ?? 0);
        $awayTeamId = (int) ($request->body['away_team_id'] ?? 0);

        if ($groupId <= 0 || $matchId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'group_id et match_id requis']);
            return;
        }

        // Validate Prediction (allow empty if user just wants to reset or bet on nothing)
        if ($prediction !== '' && !in_array($prediction, ['home', 'draw', 'away'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Prédiction invalide (victoire ou nul requis)']);
            return;
        }

        // Validate Optional Scores (if provided)
        if (($homeScore !== null && $homeScore < 0) || ($awayScore !== null && $awayScore < 0)) {
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

        // Determine winner_team_id
        $winnerTeamId = null;
        if ($prediction === 'home')
            $winnerTeamId = $homeTeamId;
        elseif ($prediction === 'away')
            $winnerTeamId = $awayTeamId;
        // if draw, winnerTeamId remains null

        $bet = new Bet();
        $result = $bet->createOrUpdate([
            'group_id' => $groupId,
            'match_id' => $matchId,
            'user_id' => $userId,
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'winner_team_id' => $winnerTeamId,
            'goal_difference' => $goalDiff
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
        $groupId = (int) ($request->params['id'] ?? 0);

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
        $groupId = (int) ($request->params['id'] ?? 0);

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
        $matchId = (int) ($request->params['id'] ?? 0);

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
