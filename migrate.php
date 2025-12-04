<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Config\Database;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = (new Database())->getConnection();

$force = in_array('--force', $argv);

echo "Starting migration process...\n";

$db->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $db->query("SELECT migration FROM migrations");
$executedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

$files = glob(__DIR__ . '/migrations/*.sql');

foreach ($files as $file) {
    $migrationName = basename($file);

    if (!$force && in_array($migrationName, $executedMigrations)) {
        echo "Skipping: $migrationName (Already executed)\n";
        continue;
    }

    echo "Migrating: $migrationName... ";
    
    $sql = file_get_contents($file);
    
    try {
        $db->exec($sql);
        
        if (!in_array($migrationName, $executedMigrations)) {
            $stmt = $db->prepare("INSERT INTO migrations (migration) VALUES (:name)");
            $stmt->execute(['name' => $migrationName]);
        }
        
        echo "DONE\n";
    } catch (PDOException $e) {
        echo "FAILED\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "Migration process completed.\n";
