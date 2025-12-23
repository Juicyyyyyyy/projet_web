<?php

namespace App\Models;

use App\Core\BaseModel;

class GroupMatch extends BaseModel
{
    public function __construct()
    {
        parent::__construct('group_matches');
    }

    public function addMatchToGroup(int $groupId, int $matchId): bool
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO {$this->tableName} (group_id, match_id) VALUES (:group_id, :match_id)");
        return $stmt->execute([
            'group_id' => $groupId,
            'match_id' => $matchId
        ]);
    }

    // Supprimer un match d'un groupe
    public function removeMatchFromGroup(int $groupId, int $matchId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->tableName} WHERE group_id = :group_id AND match_id = :match_id");
        return $stmt->execute([
            'group_id' => $groupId,
            'match_id' => $matchId
        ]);
    }

    // Recuperer tous les matchs d'un groupe avec les infos des equipes
    public function getMatchesByGroup(int $groupId): array
    {
        $stmt = $this->db->prepare("
            SELECT m.*,
                   ht.name as home_team_name, ht.logo as home_team_logo,
                   at.name as away_team_name, at.logo as away_team_logo
            FROM {$this->tableName} gm
            JOIN matches m ON gm.match_id = m.id
            JOIN teams ht ON m.home_team_id = ht.id
            JOIN teams at ON m.away_team_id = at.id
            WHERE gm.group_id = :group_id
            ORDER BY m.date ASC
        ");
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Verifier si un match est dans un groupe
    public function isMatchInGroup(int $groupId, int $matchId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->tableName} WHERE group_id = :group_id AND match_id = :match_id");
        $stmt->execute([
            'group_id' => $groupId,
            'match_id' => $matchId
        ]);
        return $stmt->fetchColumn() > 0;
    }
}
