<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function create(string $name, string $email, string $password): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->tableName} (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
