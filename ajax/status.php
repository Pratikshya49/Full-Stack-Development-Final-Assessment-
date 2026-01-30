<?php
require '../config/db.php';
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT status FROM tickets WHERE id = ?");
$stmt->execute([$id]);
$result = $stmt->fetch() ?: ['status' => 'unknown'];
echo json_encode($result);
?>
