<?php

namespace App\Session;

class Session
{
    /**
     * Starts the session if not already started.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['LAST_ACTIVITY'] = time();
    }

    /**
     * Stores a value in the session.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a value from the session.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Checks if a session key exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Destroys the session and logs the user out.
     */
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Regenerates the session ID to prevent session hijacking.
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Redirects the user to a given URL and ensures security headers are set.
     *
     * @param string $url
     */
    public static function redirectTo(string $url): void
    {
        header("Location: $url");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        exit();
    }
}
