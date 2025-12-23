<?php

namespace App\Models;

use App\Core\BaseModel;

class Bet extends BaseModel
{
    // Points constants
    private const POINTS_CORRECT_WINNER = 1;
    private const POINTS_CORRECT_DIFF = 3;
    private const POINTS_EXACT_SCORE = 5;

    public function __construct()
    {
        parent::__construct('bets');
    }

    /**
     * Create or update a bet
     */
    public function createOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->tableName}
            (group_id, match_id, user_id, home_score, away_score, winner_team_id, goal_difference)
            VALUES (:group_id, :match_id, :user_id, :home_score, :away_score, :winner_team_id, :goal_difference)
            ON DUPLICATE KEY UPDATE
            home_score = VALUES(home_score),
            away_score = VALUES(away_score),
            winner_team_id = VALUES(winner_team_id),
            goal_difference = VALUES(goal_difference)
        ");

        return $stmt->execute([
            'group_id' => $data['group_id'],
            'match_id' => $data['match_id'],
            'user_id' => $data['user_id'],
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score'],
            'winner_team_id' => $data['winner_team_id'] ?? null,
            'goal_difference' => $data['goal_difference'] ?? null
        ]);
    }

    /**
     * Find a specific bet by user, group and match
     */
    public function findByUserGroupMatch(int $userId, int $groupId, int $matchId): ?object
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->tableName}
            WHERE user_id = :user_id AND group_id = :group_id AND match_id = :match_id
        ");
        $stmt->execute([
            'user_id' => $userId,
            'group_id' => $groupId,
            'match_id' => $matchId
        ]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    /**
     * Get all bets for a user in a group with match details
     */
    public function findByGroupAndUser(int $groupId, int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*,
                   m.home_score as real_home_score,
                   m.away_score as real_away_score,
                   m.date as match_date,
                   m.status as match_status,
                   ht.name as home_team_name,
                   at.name as away_team_name
            FROM {$this->tableName} b
            JOIN matches m ON b.match_id = m.id
            JOIN teams ht ON m.home_team_id = ht.id
            JOIN teams at ON m.away_team_id = at.id
            WHERE b.group_id = :group_id AND b.user_id = :user_id
            ORDER BY m.date ASC
        ");
        $stmt->execute(['group_id' => $groupId, 'user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Calculate points for a bet based on match result
     * Returns: 1pt for correct winner, 3pts for correct goal diff, 5pts for exact score
     */
    public function calculatePoints(object $bet, object $match): int
    {
        $points = 0;

        // Skip if scores are null
        if ($bet->home_score === null || $bet->away_score === null) {
            return 0;
        }

        $realWinner = $this->getWinner($match->home_score, $match->away_score);
        $betWinner = $this->getBetWinner($bet, $match);

        if ($betWinner === $realWinner) {
            $points += self::POINTS_CORRECT_WINNER;
        }

        // 2. Check goal difference (use goal_difference field if set, otherwise calculate)
        $realDiff = abs($match->home_score - $match->away_score);
        $betDiff = ($bet->goal_difference !== null)
            ? $bet->goal_difference
            : abs($bet->home_score - $bet->away_score);

        if ($betDiff === $realDiff) {
            $points += self::POINTS_CORRECT_DIFF;
        }

        // 3. Check exact score
        if ($bet->home_score == $match->home_score && $bet->away_score == $match->away_score) {
            $points += self::POINTS_EXACT_SCORE;
        }

        return $points;
    }

    /**
     * Get winner type from scores: 'home', 'away' or 'draw'
     */
    private function getWinner(int $homeScore, int $awayScore): string
    {
        if ($homeScore > $awayScore) return 'home';
        if ($homeScore < $awayScore) return 'away';
        return 'draw';
    }

    /**
     * Get bet winner prediction: 'home', 'away' or 'draw'
     */
    private function getBetWinner(object $bet, object $match): string
    {
        if ($bet->winner_team_id !== null) {
            if ($bet->winner_team_id == $match->home_team_id) return 'home';
            if ($bet->winner_team_id == $match->away_team_id) return 'away';
            return 'draw';
        }
        return $this->getWinner($bet->home_score, $bet->away_score);
    }

    /**
     * Update points for a bet
     */
    public function updatePoints(int $betId, int $points): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->tableName} SET points = :points WHERE id = :id");
        return $stmt->execute(['id' => $betId, 'points' => $points]);
    }

    /**
     * Calculate and save points for all bets on a finished match
     */
    public function calculatePointsForMatch(int $matchId): void
    {
        // Get match with team IDs
        $stmt = $this->db->prepare("
            SELECT id, home_score, away_score, status, home_team_id, away_team_id
            FROM matches WHERE id = :id
        ");
        $stmt->execute(['id' => $matchId]);
        $match = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$match || $match->status !== 'FT') {
            return;
        }

        // Get all bets without points calculated
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->tableName}
            WHERE match_id = :match_id AND points IS NULL
        ");
        $stmt->execute(['match_id' => $matchId]);
        $bets = $stmt->fetchAll(\PDO::FETCH_OBJ);

        foreach ($bets as $bet) {
            $points = $this->calculatePoints($bet, $match);
            $this->updatePoints($bet->id, $points);
        }
    }

    /**
     * Get bet display details (winner name, icons, correct/incorrect status)
     * Used by the view to display bet results
     */
    public function getBetDetails(object $bet, object $match): array
    {
        // Predicted winner name and icon
        $predictedWinner = 'Match nul';
        $winnerIcon = '🤝';

        if (!empty($bet->winner_team_id)) {
            if ($bet->winner_team_id == $match->home_team_id) {
                $predictedWinner = $match->home_team_name;
                $winnerIcon = '🏠';
            } elseif ($bet->winner_team_id == $match->away_team_id) {
                $predictedWinner = $match->away_team_name;
                $winnerIcon = '✈️';
            }
        }

        // Check if predictions are correct
        $realWinner = $this->getWinner($match->home_score, $match->away_score);
        $betWinner = $this->getBetWinner($bet, $match);
        $realDiff = abs($match->home_score - $match->away_score);

        return [
            'predicted_winner' => $predictedWinner,
            'winner_icon' => $winnerIcon,
            'winner_correct' => ($betWinner === $realWinner),
            'score_correct' => ($bet->home_score == $match->home_score && $bet->away_score == $match->away_score),
            'diff_correct' => ($bet->goal_difference !== null && $bet->goal_difference == $realDiff),
            'points' => $bet->points ?? 0
        ];
    }

    /**
     * Get leaderboard for a group (all members with their total points)
     */
    public function getGroupLeaderboard(int $groupId): array
    {
        $stmt = $this->db->prepare("
            SELECT u.id, u.name,
                   COALESCE(SUM(b.points), 0) as total_points,
                   COUNT(b.id) as total_bets
            FROM users u
            JOIN user_groups ug ON u.id = ug.user_id AND ug.group_id = :group_id
            LEFT JOIN {$this->tableName} b ON u.id = b.user_id AND b.group_id = :group_id
            GROUP BY u.id, u.name
            ORDER BY total_points DESC
        ");
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
