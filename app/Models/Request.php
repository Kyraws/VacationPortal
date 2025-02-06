<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Request
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    /**
     * Creates a new request in the system.
     *
     * @param int $user_id
     * @param string $reason
     * @param string $start_date
     * @param string $end_date
     * @param string $status
     */
    public function createRequest(
        int    $user_id,
        string $reason,
        string $start_date,
        string $end_date,
        string $status
    ): void
    {
        $query = "INSERT INTO `Requests` (user_id, reason, date_submitted, start_date, end_date, status) 
                  VALUES (?, ?, NOW(), ?, ?, ?)";
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([$user_id, $reason, $start_date, $end_date, $status]);
        } catch (PDOException $e) {
            error_log("Database Error in createRequest: " . $e->getMessage(), 3);
        }
    }

    /**
     * Returns all requests that have been made.
     *
     * @return array
     */
    public function getRequests(): array
    {
        $query = "SELECT requests.id, user.name AS requester_name, requests.reason, 
                         requests.date_submitted, requests.start_date, requests.end_date, requests.status
                  FROM requests
                  JOIN user ON requests.user_id = user.id
                  ORDER BY requests.date_submitted DESC";
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error in getRequests: " . $e->getMessage(), 3);
            return [];
        }
    }

    /**
     * Updates the request status.
     *
     * @param int $requestId
     * @param string $status
     * @return bool
     */
    public function updateRequestStatus(int $requestId, string $status): bool
    {
        try {
            $statement = $this->connection->prepare("SELECT id FROM requests WHERE id = ? AND status = 'pending'");
            $statement->execute([$requestId]);
            $request = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                return false;
            }
            $statement = $this->connection->prepare("UPDATE requests SET status = ? WHERE id = ?");
            return $statement->execute([$status, $requestId]);
        } catch (PDOException $e) {
            error_log("Database Error in updateRequestStatus: " . $e->getMessage(), 3);
            return false;
        }
    }

    /**
     * Deletes the selected request made by the user.
     *
     * @param int $requestId
     * @param int $userId
     * @return bool
     */
    public function deleteRequest(int $requestId, int $userId): bool
    {
        $query = "SELECT * FROM requests WHERE id = ? AND user_id = ? AND status = 'pending'";
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([$requestId, $userId]);
            
            $query = "DELETE FROM requests WHERE id = ?";
            $statement = $this->connection->prepare($query);
            return $statement->execute([$requestId]);
        } catch (PDOException $e) {
            error_log("Database Error in deleteRequest: " . $e->getMessage(), 3);
            return false;
        }
    }

    /**
     * Returns all requests made by a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getRequestsByUser(int $userId): array
    {
        $query = "SELECT id, reason, date_submitted, start_date, end_date, status, 
                         DATEDIFF(end_date, start_date) + 1 AS total_days 
                  FROM requests WHERE user_id = ? ORDER BY date_submitted DESC";
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([$userId]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error in getRequestsByUser: " . $e->getMessage(), 3);
            return [];
        }
    }

}