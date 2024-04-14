<?php

namespace Marianna\FamilyTree;

use PDO;
use PDOStatement;

/**
 * Singleton клас Database
 * @package Marianna\FamilyTree
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $this->connection = new PDO('sqlite:' . __DIR__ . '/../database.db');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initializeDatabase();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function initializeDatabase(): void
    {
        $commands = [
            "CREATE TABLE IF NOT EXISTS people (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                gender TEXT NOT NULL,
                birth_date DATE NOT NULL,
                death_date DATE DEFAULT NULL,
                father_id INTEGER DEFAULT NULL,
                mother_id INTEGER DEFAULT NULL,
                FOREIGN KEY (father_id) REFERENCES people(id),
                FOREIGN KEY (mother_id) REFERENCES people(id),
                CHECK (death_date > birth_date),
                CHECK (death_date IS NULL OR death_date > birth_date),
                CHECK (father_id != mother_id AND mother_id != id AND father_id != id)
            )",
            "CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            )"
        ];
        foreach ($commands as $command) {
            $this->connection->exec($command);
        }
    }

    public static function executeQuery($sql, array $params = []): ?PDOStatement
    {
        $stmt = self::getInstance()->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function __wakeup() {}
    private function __clone() {}
}
