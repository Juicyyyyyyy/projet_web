<?php

namespace App\Core;

use Config\Database;

abstract class Model
{
    protected $db;
    protected string $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->db = (new Database())->getConnection();
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM $this->tableName";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function findById(int $id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM $this->tableName WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
