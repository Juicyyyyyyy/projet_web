<?php

namespace App\Models;

use App\Core\BaseModel;

class UserGroups extends BaseModel
{
    public function __construct()
    {
        parent::__construct('user_groups');
    }

    public function addUserToGroup(int $groupId, int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (group_id, user_id) VALUES (:group_id, :user_id)");
        return $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId,
        ]);
    }

    public function removeUserFromGroup(int $groupId, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->tableName} WHERE group_id = :group_id AND user_id = :user_id");
        return $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId,
        ]);
    }

    public function getUserGroups(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getGroupUsers(int $groupId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE group_id = :group_id");
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getGroupsWithDetails(int $userId): array
    {
        $sql = "
            SELECT g.id, g.name, g.owner_id, 
            (SELECT COUNT(*) FROM user_groups ug2 WHERE ug2.group_id = g.id) as member_count 
            FROM groups g 
            JOIN {$this->tableName} ug ON g.id = ug.group_id 
            WHERE ug.user_id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}