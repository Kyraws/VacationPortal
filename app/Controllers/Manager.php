<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Request;
use App\Session\Session;

class Manager
{

    /**
     * Displays the manager dashboard.
     */
    public function Dashboard(): void
    {
        Session::start();
        if (!Session::has('user_id') || Session::get('role') !== 'manager') {
            Session::redirectTo("/index");
        }
        $result = (new User())->getUsers();
        include __DIR__ . '/../../app/Views/manager-page.php';
    }

    /**
     * Handles inputs and calls createUser.
     */
    public function createEmployee(): void
    {
        Session::start();

        if (!Session::has('user_id') || Session::get('role') !== 'manager') {
            Session::redirectTo('/index');
        }
        $error = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username']);
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $employee_code = trim($_POST['employee_code']);

            if (empty($username) || empty($name) || empty($email) || empty($password) || empty($employee_code)) {
                $error = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif (!preg_match('/^\d{7}$/', $employee_code)) {
                $error = "Employee code must be exactly 7 digits.";
            } else {
                new User();
                if ((new User)->createUser($username, $name, $email, $password, 'employee', $employee_code)) {
                    Session::redirectTo('/manager/dashboard');
                } else {
                    $error = "Error: Could not create employee. Please try again.";
                }
            }
        }
        include __DIR__ . '/../../app/Views/create-employee.php';
    }

    /**
     * Handles inputs and calls updateRequest.
     */
    public function editEmployee(): void
    {
        Session::start();

        if (!Session::has('user_id') || Session::get('role') !== 'manager') {
            Session::redirectTo('/index');
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400);
            error_log("Invalid User ID requested: " . ($_GET['id'] ?? 'NULL'), 3);
            Session::redirectTo('ManagerPage.php?error=InvalidUserId');
        }

        $userId = (int)$_GET['id'];
        $userData = (new User())->getUserById($userId);
        $error = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $updatedFields = [
                'username' => trim($_POST['username']),
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'employee_code' => trim($_POST['employee_code']),
            ];

            if (!filter_var($updatedFields['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif (!preg_match('/^\d{7}$/', $updatedFields['employee_code'])) {
                $error = "Employee code must be exactly 7 digits.";
            } else {
                if (!empty($_POST['password'])) {
                    $updatedFields['password'] = $_POST['password'];
                }

                if ((new User())->updateUser($userId, $updatedFields)) {
                    Session::redirectTo('/manager/dashboard');
                } else {
                    $error = "Error updating user. Please try again.";
                }
            }
        }
        include __DIR__ . '/../../app/Views/edit-employee.php';
    }

    public function deleteEmployee(): void
    {
        Session::start();
        if (!Session::has('user_id') || Session::get('role') !== 'manager') {
            http_response_code(403); // Forbidden
            Session::redirectTo('/index');
        }

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            http_response_code(400);
            Session::redirectTo('/manager/dashboard');
        }

        $userId = (int)$_POST['id'];

        if ((new User())->deleteUser($userId)) {
            http_response_code(200);
        } else {
            error_log("DeleteUser Failed: User ID $userId", 3);
            http_response_code(500);
        }
        Session::redirectTo('/manager/dashboard');

    }

    public function manageRequests(): void
    {
        Session::start();

        if (!Session::has('user_id') || Session::get('role') !== 'manager') {
            Session::redirectTo('/index');
        }

        $result = (new Request())->getRequests();

        include __DIR__ . '/../../app/Views/manage-requests.php';
    }

    public function updateRequestStatus(): void
    {
        Session::start();

        // Only managers can update request status
        if (!Session::has('user_id') || Session::get('role') !== 'manager') {
            http_response_code(403);
            Session::redirectTo('/login');
            exit;
        }

        // Validate required POST parameters
        if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['action'])) {
            http_response_code(400);
            Session::redirectTo('/manager/manage-requests');
            exit;
        }

        $requestId = (int)$_POST['id'];
        $action = $_POST['action'];

        // Validate the action value
        if (!in_array($action, ['approve', 'reject'], true)) {
            http_response_code(400);
            Session::redirectTo('/manager/manage-requests');
            exit;
        }

        // Determine the new status based on the action
        $status = ($action === 'approve') ? 'approved' : 'rejected';

        // Update the request status and redirect accordingly
        if ((new Request())->updateRequestStatus($requestId, $status)) {
            http_response_code(200);
        } else {
            http_response_code(500);
            error_log("ProcessRequest Failed: Request ID $requestId | Status Attempted: $status", 3);
        }
        Session::redirectTo('/manager/manage-requests');
        exit;
    }


}