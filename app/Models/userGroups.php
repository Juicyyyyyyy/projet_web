<?php

namespace App\Models;

use App\Core\Model;

class UserGroups extends Model
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
}
