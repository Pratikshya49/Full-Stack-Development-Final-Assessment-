<?php
require '../includes/header.php';
require '../config/db.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: index.php?deleted=1');
exit;
?>
