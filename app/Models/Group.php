<?php

namespace App\Models;

use App\Core\Model;

class Group extends Model
{
    public function __construct()
    {
        parent::__construct('groups');
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
}
