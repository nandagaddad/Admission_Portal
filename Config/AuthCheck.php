<?php
/**
 * Authentication Middleware
 * Include this file at the top of all protected pages
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching of authenticated pages
header("Cache-Control: no-cache, no-store, must-revalidate, private");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['username'])) {
    // Destroy any partial session and redirect to login
    session_destroy();
    header("Location: /Admission_Portal/Views/auth/login.php");
    exit("Unauthorized Access");
}
?>
