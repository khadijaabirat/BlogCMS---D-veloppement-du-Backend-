<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to check if user has a specific role
function hasRole($role) {
    if (!isLoggedIn()) return false;
    return $_SESSION['role'] === $role;
}

// Function to check if user is admin
function isAdmin() {
    return hasRole('admin');
}

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Function into get database connection (if needed globally, currently handled in db.php but good to have a wrapper if we want)
// Check if user has permission (can be array of allowed roles)
function requireRole($allowed_roles) {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        // Redirect to unauthorized page or home
        redirect('index.php');
    }
}
?>