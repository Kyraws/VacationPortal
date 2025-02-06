<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    /** Singleton instance */
    private static ?Database $instance = null;

    private ?PDO $connection = null;

    private string $host = "localhost";
    private string $dbname = "vacationportal";
    private string $username = "root";
    private string $password = "";

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            error_log("Database Error in constructor: " . $e->getMessage(), 3);
            throw new PDOException("Database connection failed.");
        }
    }

    /**
     * Returns the singleton instance of the Database class.
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Gets the PDO connection instance.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
