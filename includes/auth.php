<?php
// includes/auth.php — call this before header on any page that needs user awareness
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /brainhub/brainhub/auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}
