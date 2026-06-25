<?php
session_start();

// Remove all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Clear any cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page using absolute path
header("Location: /Admission_Portal/Views/auth/login.php");
exit();
?>