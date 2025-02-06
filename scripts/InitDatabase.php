<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Request.php';

use App\Core\Database;
use App\Models\User;
use App\Models\Request;

$host = "localhost";
$dbname = "vacationportal";
$username = "root";
$password = "";

try {
    $tempConnection = new PDO("mysql:host=$host", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $statement = $tempConnection->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
    $statement->execute([$dbname]);
    $dbExists = (bool)$statement->fetch();

    if (!$dbExists) {
        $tempConnection->exec("CREATE DATABASE `$dbname`");
        echo "Database '$dbname' created successfully.\n";
    } else {
        echo "Database '$dbname' already exists.\n";
    }

    $tempConnection = null;

    $db = Database::getInstance();
    $connection = $db->getConnection();

    $connection->exec("
        CREATE TABLE IF NOT EXISTS User (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            username VARCHAR(255)UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('manager','employee') NOT NULL,
            employee_code CHAR(7) UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $connection->exec("
        CREATE TABLE IF NOT EXISTS Requests (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            user_id INT NOT NULL,
            reason TEXT NOT NULL,
            date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
        )"
    );

    echo "Tables created successfully.\n";

    $user = new User();

    $user->createUser('manager1', 'Alice Johnson', 'manager@example.com', 'manager', 'manager', NULL);
    echo "Manager created.\n";

    $employees = [
        ['emp001', 'Bob Smith', 'bob.smith@example.com', 'password123', '1234567'],
        ['emp002', 'Charlie Adams', 'charlie.adams@example.com', 'password123', '2345678'],
        ['emp003', 'Dana White', 'dana.white@example.com', 'password123', '3456789'],
        ['emp004', 'Evan Black', 'evan.black@example.com', 'password123', '4567890'],
    ];

    foreach ($employees as $emp) {
        $user->createUser($emp[0], $emp[1], $emp[2], $emp[3], 'employee', $emp[4]);
    }

    echo "5 Employees created.\n";

    $request = new Request();

    $requests = [
        [2, 'Vacation', '2025-03-30', '2025-04-06', 'pending'],
        [2, 'Conference Attendance', '2025-03-30', '2025-04-23', 'pending'],
        [2, 'Training Program', '2025-03-30', '2025-04-14', 'pending'],
        [3, 'Evan Black', '2025-03-30', '2025-05-06', 'pending'],
        [4, 'Fiona Green', '2025-03-30', '2025-04-11', 'pending']
    ];

    foreach ($requests as $res) {
        $request->createRequest($res[0], $res[1], $res[2], $res[3], $res[4]);
    }

    echo "10 Sample requests added.\n";
    echo "Database initialization completed successfully!\n";

} catch
(PDOException $e) {
    error_log("Database Error in initialization: " . $e->getMessage(), 3);
    echo "Database initialization failed. Check logs.\n";
}
