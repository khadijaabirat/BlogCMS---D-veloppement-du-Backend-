<?php
// Démarrer session
session_start();

// Fonction simple: user connecté?
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction simple: user admin?
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction simple: user auteur?
function isAuthor() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'author' || $_SESSION['role'] === 'admin');
}

// Fonction: Se connecter
function login($userId, $username, $email, $role) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;
}

// Fonction: Se déconnecter
function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>