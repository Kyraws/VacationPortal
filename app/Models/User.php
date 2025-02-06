<?php

namespace App\Models;

use App\Core\Database;
use App\Session\Session;
use PDO;
use PDOException;

class User
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    /**
     * Creates a new user.
     *
     * @param string $username
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string|null $employee_code
     * @return bool
     */
    public function createUser(
        string  $username,
        string  $name,
        string  $email,
        string  $password,
        string  $role,
        ?string $employee_code
    ): bool
    {
        $query = "INSERT INTO `User` (username, name, email, password, role, employee_code) VALUES (?, ?, ?, ?, ?, ?)";
        try {
            $statement = $this->connection->prepare($query);
            return $statement->execute([
                $username,
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $role,
                $employee_code
            ]);
        } catch (PDOException $e) {
            error_log("Database Error in createUser: " . $e->getMessage(), 3);
            return false;
        }
    }

    /**
     * Updates user information.
     *
     * @param int $userId
     * @param array $fields
     * @return bool
     */
    public function updateUser(int $userId, array $fields): bool
    {
        if (empty($fields)) {
            return false;
        }

        $allowedFields = ['username', 'name', 'email', 'employee_code', 'role', 'password'];
        $fields = array_intersect_key($fields, array_flip($allowedFields));
        
        if (isset($fields['password']) && empty($fields['password'])) {
            unset($fields['password']);
        } elseif (isset($fields['password'])) {
            $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
        }

        // Generate key-value pairs for query (key = :key)
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($fields)));
        $query = "UPDATE `User` SET $setClause WHERE id = :id";

        try {
            $statement = $this->connection->prepare($query);
            $fields['id'] = $userId;
            return $statement->execute($fields);
        } catch (PDOException $e) {
            error_log("Database Error in updateUser (UserID: $userId): " . $e->getMessage(), 3);
            return false;
        }
    }

    /**
     * Deletes a user.
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool
    {
        try {
            $query = "DELETE FROM `User` WHERE id = ?";
            $statement = $this->connection->prepare($query);
            return $statement->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Database Error in deleteUser: " . $e->getMessage(), 3);
            return false;
        }
    }


    /**
     * Returns all users in the database.
     *
     * @return array
     */
    public function getUsers(): array
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM `User` WHERE role = 'employee'");
            $statement->execute();

            $users = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $users ?: [];
        } catch (PDOException $e) {
            error_log("Database Error in getUsers: " . $e->getMessage(), 3);
            return [];
        }
    }

    /**
     * Retrieves user information by user ID.
     *
     * @param int $userId
     * @return array|null
     */
    public function getUserById(int $userId): ?array
    {
        $query = "SELECT * FROM `User` WHERE id = :id";
        try {
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':id', $userId, PDO::PARAM_INT);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            error_log("Database Error in getUserById: " . $e->getMessage(), 3);
            return null;
        }
    }

    /*                       Shared Functions                       */

    /**
     * Authenticates a user via email and password.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password): bool
    {
        $query = "SELECT id, password, role FROM `User` WHERE username = :username";
        try {
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':username', $username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                Session::set('user_id', $user['id']);
                Session::set('role', $user['role']);
                Session::regenerate();
                return true;
            }
        } catch (PDOException $e) {
            error_log("Database Error in login: " . $e->getMessage(), 3);
        }
        return false;
    }


}
