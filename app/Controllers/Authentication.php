<?php

namespace App\Controllers;

use App\Models\User;
use App\Session\Session;

class Authentication
{
    /**
     * Handles the login process and displays the login form.
     *
     */
    public function loginForm(): void
    {
        Session::start();

        if (Session::has('user_id')) {
            $role = Session::get('role');
            if ($role === 'manager') {
                Session::redirectTo('/manager/dashboard');
            } elseif ($role === 'employee') {
                Session::redirectTo('/employee/dashboard');
            }
            exit;
        }

        $error = '';
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"] ?? '');
            $password = trim($_POST["password"] ?? '');
            if (!empty($username) && !empty($password)) {
                if ((new User())->login($username, $password)) {
                    $role = Session::get('role');
                    if ($role === 'manager') {
                        Session::redirectTo('/manager/dashboard');
                    } elseif ($role === 'employee') {
                        Session::redirectTo('/employee/dashboard');
                    }
                    exit;
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Please enter both username and password.";
            }
        }
        include __DIR__ . '/../../app/Views/login.php';
    }


    /**
     * Destroys the session.
     *
     */
    public function logout(): void
    {
        Session::start();
        Session::destroy();
        Session::redirectTo('/index');
    }
}
