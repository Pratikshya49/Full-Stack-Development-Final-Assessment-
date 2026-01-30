<?php require '../includes/header.php'; 
$id = (int)($_GET['id'] ?? 0);
if (!$id) header('Location: index.php');

generateCSRF();
$ticket = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$ticket->execute([$id]);
$ticket = $ticket->fetch();
if (!$ticket) header('Location: index.php');

if ($_POST) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die('CSRF');
    
    $stmt = $pdo->prepare("UPDATE tickets SET user_name=?, user_email=?, issue_type=?, priority=?, description=?, status=? WHERE id=?");
    $stmt->execute([
        htmlspecialchars(trim($_POST['user_name'])),
        filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) ?: $ticket['user_email'],
        htmlspecialchars(trim($_POST['issue_type'])),
        $_POST['priority'],
        htmlspecialchars(trim($_POST['description'])),
        $_POST['status'],
        $id
    ]);
    header('Location: index.php?updated=1');
    exit;
}
?>
<div class="container">
    <h2>Edit Ticket #<?=$id?></h2>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
        <input type="text" name="user_name" value="<?=htmlspecialchars($ticket['user_name'])?>" required>
        <input type="email" name="user_email" value="<?=htmlspecialchars($ticket['user_email'])?>" required>
        <input type="text" name="issue_type" value="<?=htmlspecialchars($ticket['issue_type'])?>" required>
        <select name="priority">
            <option <?=($ticket['priority']=='low')?'selected':''?>>low</option>
            <option <?=($ticket['priority']=='medium')?'selected':''?>>medium</option>
            <option <?=($ticket['priority']=='high')?'selected':''?>>high</option>
        </select>
        <textarea name="description"><?=htmlspecialchars($ticket['description'])?></textarea>
        <select name="status">
            <option <?=($ticket['status']=='open')?'selected':''?>>open</option>
            <option <?=($ticket['status']=='in-progress')?'selected':''?>>in-progress</option>
            <option <?=($ticket['status']=='resolved')?'selected':''?>>resolved</option>
            <option <?=($ticket['status']=='closed')?'selected':''?>>closed</option>
        </select>
        <button type="submit">Update</button>
    </form>
</div>
<?php require '../includes/footer.php'; ?>
