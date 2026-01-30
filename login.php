<?php
session_start();
require 'config/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: public/index.php');
    exit;
}

$error = '';
if ($_POST) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([htmlspecialchars($_POST['username'])]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: public/index.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticketing System - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Customer Support Ticketing System</h2>
        <?php if ($error): ?><p class="error"><?=$error?></p><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="admin" required>
            <input type="password" name="password" placeholder="admin123" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
