<?php
session_start();
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
function isAdmin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
}
function generateCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
?>
