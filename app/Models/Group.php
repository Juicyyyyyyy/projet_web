<?php

namespace App\Models;

use App\Core\BaseModel;

class Group extends BaseModel
{
    public function __construct()
    {
        parent::__construct('`groups`');
    }

    public function createOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (id, name, owner_id) VALUES (:id, :name, :owner_id) ON DUPLICATE KEY UPDATE name = :name, owner_id = :owner_id");
        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'owner_id' => $data['owner_id']
        ]);
    }

    public function create(string $name, int $ownerId): int|false
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (name, owner_id) VALUES (:name, :owner_id)");
        $result = $stmt->execute([
            'name' => $name,
            'owner_id' => $ownerId
        ]);
        return $result ? (int)$this->db->lastInsertId() : false;
    }

    public function isOwner(int $groupId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->tableName} WHERE id = :id AND owner_id = :owner_id");
        $stmt->execute([
            'id' => $groupId,
            'owner_id' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT g.*,
                   (g.owner_id = :user_id) as is_owner,
                   u.name as owner_name
            FROM {$this->tableName} g
            JOIN users u ON g.owner_id = u.id
            LEFT JOIN user_groups ug ON g.id = ug.group_id
            WHERE g.owner_id = :user_id2 OR ug.user_id = :user_id3
            GROUP BY g.id
        ");
        $stmt->execute([
            'user_id' => $userId,
            'user_id2' => $userId,
            'user_id3' => $userId
        ]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findWithDetails(int $groupId): ?object
    {
        $stmt = $this->db->prepare("
            SELECT g.*, u.name as owner_name
            FROM {$this->tableName} g
            JOIN users u ON g.owner_id = u.id
            WHERE g.id = :id
        ");
        $stmt->execute(['id' => $groupId]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function isMember(int $groupId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM (
                SELECT 1 FROM {$this->tableName} WHERE id = :group_id AND owner_id = :user_id
                UNION
                SELECT 1 FROM user_groups WHERE group_id = :group_id2 AND user_id = :user_id2
            ) as membership
        ");
        $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId,
            'group_id2' => $groupId,
            'user_id2' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }
}
