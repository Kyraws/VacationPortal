<?php

namespace App\Controllers;

use App\Session\Session;
use App\Models\Request;

use DateTime;
use Exception;

class Employee
{
    /**
     * Displays the employee dashboard.
     */
    public function dashboard(): void
    {
        Session::start();
        if (!Session::has('user_id') || !in_array(Session::get('role'), ['employee', 'manager'])) {
            Session::redirectTo('/index');
            exit;
        }
        $userId = Session::get('user_id');
        $requests = (new Request())->getRequestsByUser($userId);
        include __DIR__ . '/../../app/Views/employee-page.php';
    }

    /**
     * Handles inputs and calls createRequest.
     */
    public function createRequest(): void
    {
        Session::start();

        if (!Session::has('user_id') || Session::get('role') !== 'employee') {
            Session::redirectTo('/index');
            exit;
        }
        $error = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = Session::get('user_id');

            $reason = trim($_POST['reason'] ?? '');
            $startDateInput = trim($_POST['start_date'] ?? '');
            $endDateInput = trim($_POST['end_date'] ?? '');

            if (empty($reason) || empty($startDateInput) || empty($endDateInput)) {
                $error = "All fields are required!";
            } else {

                $startDateObj = DateTime::createFromFormat('d-m-Y', $startDateInput);
                $endDateObj = DateTime::createFromFormat('d-m-Y', $endDateInput);

                if (!$startDateObj || !$endDateObj) {
                    $error = "Invalid date format!";
                } else {
                    $start_date = $startDateObj->format('Y-m-d');
                    $end_date = $endDateObj->format('Y-m-d');

                    if ($start_date > $end_date) {
                        $error = "Start date cannot be later than end date!";
                    } else {
                        try {
                            (new Request())->createRequest($userId, $reason, $start_date, $end_date, 'pending');
                            Session::redirectTo('/employee/dashboard');
                            exit;
                        } catch (Exception $e) {
                            $error = "Error submitting request. Please try again.";
                        }
                    }
                }
            }
        }
        include __DIR__ . '/../../app/Views/create-request.php';
    }

    /**
     * Calls deleteRequest.
     */
    public function deleteRequest(): void
    {
        Session::start();

        if (!Session::has('user_id') || Session::get('role') !== 'employee') {
            http_response_code(403); // Forbidden
            Session::redirectTo('/login');
            exit;
        }

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            http_response_code(400);
            Session::redirectTo('/employee/dashboard');
            exit;
        }

        $userId = Session::get('user_id');
        $requestId = (int)$_POST['id'];

        if ((new Request())->deleteRequest($requestId, $userId)) {
            http_response_code(200);
        } else {
            error_log("DeleteRequest Failed: Request ID $requestId | User ID $userId", 3);
            http_response_code(500);
        }

        Session::redirectTo('/employee/dashboard');
        exit;
    }


}
