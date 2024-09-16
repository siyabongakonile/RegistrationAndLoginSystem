<?php
declare(strict_types = 1);

namespace App;

use \PDO;

class Database{
    private static ?Database $database = null;

    private \PDO $dbObj;

    private function __construct(string $platform, string $host, string $username, string $password, string $dbName, string $charset){
        $dsn = "{$platform}:host={$host};dbname={$dbName}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try{
            $this->dbObj = new PDO($dsn, $username, $password, $options);
        }catch(\PDOException $e){
            error_log($e->getMessage());
            throw new \Exception("Database connection failed.");
        }
    }

    private function __clone(){}

    private function __wakeup(){}

    public static function getInstance(): static{
        if(static::$database === null)
            static::$database = new static(DB_PLATFORM, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_CHARSET);
        return static::$database;
    }

    public function execute(string $sql, array $params): bool{
        $stmt = $this->dbObj->prepare($sql);
        return $stmt->execute($params);
    }

    public function getLastInsertedId(): ?int{
        $id = $this->dbObj->lastInsertId();
        return $id === null? null: (int) $id;
    }

    public function fetchAll(string $sql, array $params): array{
        $stmt = $this->dbObj->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}