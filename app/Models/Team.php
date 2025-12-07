<?php

namespace App\Models;

use App\Core\Model;

class Team extends Model
{
    public function __construct()
    {
        parent::__construct('teams');
    }

    public function createOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (id, name, logo, venue_name, city) VALUES (:id, :name, :logo, :venue_name, :city) ON DUPLICATE KEY UPDATE name = :name, logo = :logo, venue_name = :venue_name, city = :city");
        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'logo' => $data['logo'],
            'venue_name' => $data['venue_name'],
            'city' => $data['city']
        ]);
    }
}