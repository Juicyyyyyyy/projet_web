<?php

namespace App\Core;

use Config\Database;

abstract class BaseModel
{
    private string $tableName;
    private \PDO $db;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->db = (new Database())->getConnection();
    }
    
    public function index(): false|array
    {
        return $this->findAll();
    }
    
    public function findAll(): false|array
    {
        $query = "SELECT * FROM $this->tableName ORDER BY created_at DESC";
        
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
