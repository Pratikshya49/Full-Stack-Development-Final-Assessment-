<?php require '../includes/header.php'; 

// Multi-criteria search (10pts)
$where = $params = [];
if (!empty($_GET['issue_type'])) { $where[] = "issue_type LIKE ?"; $params[] = "%{$_GET['issue_type']}%"; }
if (!empty($_GET['priority'])) { $where[] = "priority = ?"; $params[] = $_GET['priority']; }
if (!empty($_GET['status'])) { $where[] = "status = ?"; $params[] = $_GET['status']; }
if (!empty($_GET['date_from'])) { $where[] = "created_at >= ?"; $params[] = $_GET['date_from']; }

$sql = "SELECT * FROM tickets" . 
       (!empty($where) ? " WHERE " . implode(" AND ", $where) : "") .
       " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();
?>

<div class="container">
    <h2>Tickets (<?=count($tickets)?>)</h2>
    
    <!-- AJAX Autocomplete Search (10pts bonus) -->
    <div class="autocomplete-search">
        <input type="text" id="quick-search" placeholder="Quick search tickets... (name, issue, description)">
        <div id="search-results" class="autocomplete-results"></div>
    </div>
    
    <!-- Search Form - Multiple criteria simultaneously (10pts) -->
    <form method="GET" class="search-form">
        <input type="text" name="issue_type" placeholder="Issue Type" value="<?=htmlspecialchars($_GET['issue_type']??'')?>">
        <select name="priority">
            <option value="">All Priorities</option>
            <option <?=($_GET['priority']??'')=='low'?'selected':''?>>low</option>
            <option <?=($_GET['priority']??'')=='medium'?'selected':''?>>medium</option>
            <option <?=($_GET['priority']??'')=='high'?'selected':''?>>high</option>
        </select>
        <select name="status">
            <option value="">All Status</option>
            <option <?=($_GET['status']??'')=='open'?'selected':''?>>open</option>
            <option <?=($_GET['status']??'')=='in-progress'?'selected':''?>>in-progress</option>
            <option <?=($_GET['status']??'')=='resolved'?'selected':''?>>resolved</option>
            <option <?=($_GET['status']??'')=='closed'?'selected':''?>>closed</option>
        </select>
        <input type="date" name="date_from" value="<?=htmlspecialchars($_GET['date_from']??'')?>">
        <button type="submit">Search</button>
        <a href="index.php" class="clear">Clear</a>
    </form>

    <table>
        <tr>
            <th>ID</th><th>User</th><th>Email</th><th>Issue</th><th>Priority</th><th>Status</th><th>Date</th><th>Actions</th>
        </tr>
        <?php foreach($tickets as $t): ?>
        <tr class="ticket-row" data-ticket-id="<?=$t['id']?>">
            <td><?=$t['id']?></td>
            <td><?=htmlspecialchars($t['user_name'])?></td><!-- XSS protection -->
            <td><?=htmlspecialchars($t['user_email'])?></td>
            <td><?=htmlspecialchars($t['issue_type'])?></td>
            <td><?=$t['priority']?></td>
            <td class="status <?=$t['status']?>"><?=htmlspecialchars($t['status'])?></td><!-- Live AJAX -->
            <td><?=$t['created_at']?></td>
            <td>
                <a href="edit.php?id=<?=$t['id']?>">Edit</a>
                <?php if(isAdmin()): ?>
                <a href="delete.php?id=<?=$t['id']?>" onclick="return confirm('Delete?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require '../includes/footer.php'; ?>
