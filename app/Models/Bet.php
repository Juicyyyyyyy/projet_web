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
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (id,match_id,user_id,home_score,away_score,winner_team_id,goal_difference,created_at,updated_at) VALUES ( :id, :match_id, :user_id, :home_score, :away_score, :winner_team_id, :goal_difference, :created_at, :updated_at) ON DUPLICATE KEY UPDATE match_id = :match_id, user_id = :user_id, home_score = :home_score, away_score = :away_score, winner_team_id = :winner_team_id, goal_difference = :goal_difference, created_at = :created_at,updated_at = :updated_at");

        return $stmt->execute([
            'id' => $data['id'],
            'match_id' => $data['match_id'],
            'user_id' => $data['user_id'],
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score'],
            'winner_team_id' => $data['winner_team_id'],
            'goal_difference' => $data['goal_difference'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);
    }
}
