<?php require '../includes/header.php'; 
generateCSRF();
$success = $_GET['success'] ?? '';

if ($_POST) {
    // CSRF protection (10pts security)
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF attack detected');
    }
    
    $stmt = $pdo->prepare("INSERT INTO tickets (user_name, user_email, issue_type, priority, description) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([
        htmlspecialchars(trim($_POST['user_name'])), // Input filtering + XSS
        filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) ?: '',
        htmlspecialchars(trim($_POST['issue_type'])),
        $_POST['priority'],
        htmlspecialchars(trim($_POST['description']))
    ]);
    
    if ($result) {
        header('Location: index.php?success=1');
        exit;
    }
}
?>
<div class="container">
    <h2>Create New Ticket</h2>
    <?php if ($success): ?><p class="success">Ticket created successfully!</p><?php endif; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
        <input type="text" name="user_name" placeholder="Your Name" required maxlength="255">
        <input type="email" name="user_email" placeholder="your@email.com" required>
        <input type="text" name="issue_type" placeholder="e.g. Login Issue" required>
        <select name="priority" required>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
        <textarea name="description" placeholder="Describe your issue..." required></textarea>
        <button type="submit">Submit Ticket</button>
    </form>
</div>
<?php require '../includes/footer.php'; ?>
