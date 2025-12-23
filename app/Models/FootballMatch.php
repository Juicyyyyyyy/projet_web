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

    public function findAllWithTeams(): array
    {
        $query = "
            SELECT m.*, 
                   ht.name as home_team_name, 
                   at.name as away_team_name
            FROM {$this->tableName} m
            LEFT JOIN teams ht ON m.home_team_id = ht.id
            LEFT JOIN teams at ON m.away_team_id = at.id
            ORDER BY m.date ASC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findUpcomingWithTeams(int $days = 7): array
    {
        $query = "
            SELECT m.*, 
                   ht.name as home_team_name, 
                   at.name as away_team_name
            FROM {$this->tableName} m
            LEFT JOIN teams ht ON m.home_team_id = ht.id
            LEFT JOIN teams at ON m.away_team_id = at.id
            WHERE m.date >= CURDATE() 
            AND m.date <= DATE_ADD(CURDATE(), INTERVAL :days DAY)
            ORDER BY m.date ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':days', $days, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}