<?php

namespace Config;

use PDO;

class Database
{
private $pdo; 
    
    public function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbName = $_ENV['DB_NAME'] ?? 'web_project';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? 'root';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

        try {
            $this->pdo = new PDO($dsn, $user, $pass);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}