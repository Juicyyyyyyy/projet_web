<?php

namespace App\Models;

use App\Core\BaseModel;

class Bet extends BaseModel
{
    public function __construct()
    {
        parent::__construct('bets');
    }

    public function createOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->tableName}
            (group_id, match_id, user_id, home_score, away_score)
            VALUES (:group_id, :match_id, :user_id, :home_score, :away_score)
            ON DUPLICATE KEY UPDATE
            home_score = :home_score,
            away_score = :away_score
        ");

        return $stmt->execute([
            'group_id' => $data['group_id'],
            'match_id' => $data['match_id'],
            'user_id' => $data['user_id'],
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score']
        ]);
    }

    public function findByUserGroupMatch(int $userId, int $groupId, int $matchId): ?object
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->tableName}
            WHERE user_id = :user_id
            AND group_id = :group_id
            AND match_id = :match_id
        ");
        $stmt->execute([
            'user_id' => $userId,
            'group_id' => $groupId,
            'match_id' => $matchId
        ]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

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
            WHERE b.group_id = :group_id
            AND b.user_id = :user_id
            ORDER BY m.date ASC
        ");
        $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findByGroup(int $groupId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*,
                   m.home_score as real_home_score,
                   m.away_score as real_away_score,
                   m.status as match_status
            FROM {$this->tableName} b
            JOIN matches m ON b.match_id = m.id
            WHERE b.group_id = :group_id
        ");
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function calculatePoints(int $betHome, int $betAway, int $realHome, int $realAway): int
    {
        $points = 0;

        $betWinner = $betHome > $betAway ? 'home' : ($betHome < $betAway ? 'away' : 'draw');
        $realWinner = $realHome > $realAway ? 'home' : ($realHome < $realAway ? 'away' : 'draw');

        if ($betWinner === $realWinner) {
            $points += 1;
        }

        $betDiff = $betHome - $betAway;
        $realDiff = $realHome - $realAway;
        if ($betDiff === $realDiff) {
            $points += 3;
        }

        if ($betHome === $realHome && $betAway === $realAway) {
            $points += 5;
        }

        return $points;
    }

    public function updatePoints(int $betId, int $points): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->tableName} SET points = :points WHERE id = :id");
        return $stmt->execute([
            'id' => $betId,
            'points' => $points
        ]);
    }

    public function calculatePointsForMatch(int $matchId): void
    {
        $stmt = $this->db->prepare("SELECT home_score, away_score, status FROM matches WHERE id = :id");
        $stmt->execute(['id' => $matchId]);
        $match = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$match || $match->status !== 'FT') {
            return;
        }

        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE match_id = :match_id AND points IS NULL");
        $stmt->execute(['match_id' => $matchId]);
        $bets = $stmt->fetchAll(\PDO::FETCH_OBJ);

        foreach ($bets as $bet) {
            $points = $this->calculatePoints(
                $bet->home_score,
                $bet->away_score,
                $match->home_score,
                $match->away_score
            );
            $this->updatePoints($bet->id, $points);
        }
    }

    public function getGroupLeaderboard(int $groupId): array
    {
        $stmt = $this->db->prepare("
            SELECT u.id, u.name,
                   COALESCE(SUM(b.points), 0) as total_points,
                   COUNT(b.id) as total_bets
            FROM users u
            JOIN user_groups ug ON u.id = ug.user_id
            LEFT JOIN {$this->tableName} b ON u.id = b.user_id AND b.group_id = :group_id
            WHERE ug.group_id = :group_id2
            GROUP BY u.id, u.name
            ORDER BY total_points DESC
        ");
        $stmt->execute([
            'group_id' => $groupId,
            'group_id2' => $groupId
        ]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
