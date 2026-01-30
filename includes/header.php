<?php require 'functions.php'; requireLogin(); require '../config/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticketing System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav>
        <a href="../public/index.php">Home</a>
        <a href="../public/create.php">New Ticket</a>
        <?php if (isAdmin()): ?>
            <a href="../public/admin.php">Admin</a>
        <?php endif; ?>
        <span>Welcome, <?=htmlspecialchars($_SESSION['username'])?></span>
        <a href="../public/logout.php">Logout</a>
    </nav>
