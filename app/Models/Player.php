<?php

namespace App\Models;

use App\Core\BaseModel;

class Player extends BaseModel
{
    public function __construct()
    {
        parent::__construct('players');
    }

    public function createOrUpdate(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (id, name, firstname, lastname, age, nationality, height, weight, photo, team_id) VALUES (:id, :name, :firstname, :lastname, :age, :nationality, :height, :weight, :photo, :team_id) ON DUPLICATE KEY UPDATE name = :name, firstname = :firstname, lastname = :lastname, age = :age, nationality = :nationality, height = :height, weight = :weight, photo = :photo, team_id = :team_id");
        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'age' => $data['age'],
            'nationality' => $data['nationality'],
            'height' => $data['height'],
            'weight' => $data['weight'],
            'photo' => $data['photo'],
            'team_id' => $data['team_id']
        ]);
    }
}