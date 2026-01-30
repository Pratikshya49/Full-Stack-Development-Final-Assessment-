<?php
require '../config/db.php';
header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Search across multiple fields (10pts advanced search)
$stmt = $pdo->prepare("
    SELECT DISTINCT 
        CONCAT(id, ': ', user_name, ' - ', issue_type) as display,
        id, user_name, issue_type, priority, status
    FROM tickets 
    WHERE user_name LIKE ? 
       OR issue_type LIKE ? 
       OR description LIKE ?
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute(["%$query%", "%$query%", "%$query%"]);
$results = $stmt->fetchAll();

echo json_encode($results);
?>
