<?php

namespace App\Models;

use App\Core\BaseModel;

class FootballMatch extends BaseModel
{
    public function __construct()
    {
        parent::__construct('matches');
    }

    public function createOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (id, date, status, home_team_id, away_team_id, home_score, away_score, league_id, season) VALUES (:id, :date, :status, :home_team_id, :away_team_id, :home_score, :away_score, :league_id, :season) ON DUPLICATE KEY UPDATE date = :date, status = :status, home_team_id = :home_team_id, away_team_id = :away_team_id, home_score = :home_score, away_score = :away_score, league_id = :league_id, season = :season");
        return $stmt->execute([
            'id' => $data['id'],
            'date' => $data['date'],
            'status' => $data['status'],
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score'],
            'league_id' => $data['league_id'],
            'season' => $data['season']
        ]);
    }

    public function getLastMatchDate()
    {
        $stmt = $this->db->query("SELECT date FROM {$this->tableName} ORDER BY date DESC LIMIT 1");
        return $stmt->fetchColumn();
    }
}
