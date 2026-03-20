<?php
session_start();
if (!isset($_SESSION["admin_logged"])) { header("Location: /brainhub/brainhub/admin/login.php"); exit; }
require_once __DIR__ . "/../includes/db.php";

$totalGames    = $pdo->query("SELECT COUNT(*) FROM games")->fetchColumn();
$totalCats     = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalFeedback = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$totalScores   = $pdo->query("SELECT COUNT(*) FROM scores")->fetchColumn();
$totalPlays    = $pdo->query("SELECT SUM(plays) FROM games")->fetchColumn();

$games = $pdo->query("
    SELECT games.*, categories.name AS cat_name
    FROM games JOIN categories ON games.category_id = categories.id
    ORDER BY games.id DESC
")->fetchAll();

$deleted = isset($_GET["deleted"]) ? "Game deleted successfully." : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>BrainHub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
*{box-sizing:border-box;}
body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;margin:0;}
.admin-wrap{display:grid;grid-template-columns:260px 1fr;min-height:100vh;}
.sidebar{background:#0f1117;border-right:1px solid #1e2235;padding:28px 18px;position:sticky;top:0;height:100vh;}
.brand{font-family:'Orbitron',monospace;font-size:20px;font-weight:900;color:#e8eaf6;margin-bottom:6px;}
.brand span{color:#6c63ff;}
.brand-sub{font-size:12px;color:#7b7fa3;letter-spacing:1px;margin-bottom:28px;}
.nav-link{display:block;color:#7b7fa3;text-decoration:none;padding:11px 14px;border-radius:10px;font-weight:600;font-size:15px;margin-bottom:4px;transition:all 0.2s;}
.nav-link:hover,.nav-link.active{background:#1e2235;color:#e8eaf6;}
.nav-link.danger:hover{background:rgba(255,0,110,0.1);color:#ff006e;}
.main{padding:36px;}
h1{font-family:'Orbitron',monospace;font-size:24px;font-weight:900;margin-bottom:28px;}
.stat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:28px;}
.stat{background:#0f1117;border:1px solid #1e2235;border-radius:14px;padding:18px 20px;}
.stat h3{font-size:13px;color:#7b7fa3;margin:0 0 8px;letter-spacing:1px;text-transform:uppercase;}
.stat p{font-family:'Orbitron',monospace;font-size:28px;font-weight:900;margin:0;color:#6c63ff;}
.success-msg{background:rgba(56,176,0,0.1);border:1px solid rgba(56,176,0,0.3);color:#38b000;padding:12px 18px;border-radius:10px;font-weight:600;margin-bottom:18px;}
.table-box{background:#0f1117;border:1px solid #1e2235;border-radius:14px;overflow:hidden;}
table{width:100%;border-collapse:collapse;}
th{background:#13151e;color:#7b7fa3;font-size:12px;letter-spacing:1px;text-transform:uppercase;padding:14px 16px;text-align:left;}
td{padding:14px 16px;border-bottom:1px solid #1e2235;font-size:14px;color:#b0b4cc;}
tr:hover td{background:rgba(108,99,255,0.04);}
.badge-active{background:rgba(56,176,0,0.15);color:#38b000;border:1px solid rgba(56,176,0,0.3);padding:3px 12px;border-radius:50px;font-size:11px;font-weight:700;}
.badge-draft{background:rgba(255,165,0,0.1);color:#f7931e;border:1px solid rgba(247,147,30,0.3);padding:3px 12px;border-radius:50px;font-size:11px;font-weight:700;}
.btn-edit{background:#3b82f620;color:#3b82f6;border:1px solid #3b82f640;padding:6px 14px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;}
.btn-del{background:#ff006e20;color:#ff006e;border:1px solid #ff006e40;padding:6px 14px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;}
.btn-add{display:inline-block;background:linear-gradient(135deg,#6c63ff,#9b5de5);color:white;padding:12px 22px;border-radius:12px;text-decoration:none;font-weight:700;font-family:'Orbitron',monospace;font-size:13px;letter-spacing:1px;margin-bottom:18px;}
@media(max-width:900px){.admin-wrap{grid-template-columns:1fr;}.sidebar{height:auto;position:static;}.stat-grid{grid-template-columns:repeat(2,1fr);}}
</style>
</head>
<body>
<div class="admin-wrap">
<aside class="sidebar">
    <div class="brand">Brain<span>Hub</span></div>
    <div class="brand-sub">Admin Panel</div>
    <a class="nav-link active" href="/brainhub/brainhub/admin/">📊 Dashboard</a>
    <a class="nav-link" href="/brainhub/brainhub/admin/add-game.php">➕ Add Game</a>
    <a class="nav-link" href="/brainhub/brainhub/admin/feedback.php">💬 Feedback</a>
    <a class="nav-link" href="/brainhub/brainhub/admin/scores.php">🏆 Scores</a>
    <a class="nav-link" href="/brainhub/brainhub/" target="_blank">🌐 View Site</a>
    <a class="nav-link danger" href="/brainhub/brainhub/admin/logout.php">🚪 Logout</a>
</aside>
<main class="main">
    <h1>Dashboard</h1>
    <div class="stat-grid">
        <div class="stat"><h3>Games</h3><p><?php echo $totalGames; ?></p></div>
        <div class="stat"><h3>Categories</h3><p><?php echo $totalCats; ?></p></div>
        <div class="stat"><h3>Total Plays</h3><p><?php echo number_format($totalPlays); ?></p></div>
        <div class="stat"><h3>Scores</h3><p><?php echo $totalScores; ?></p></div>
        <div class="stat"><h3>Feedback</h3><p><?php echo $totalFeedback; ?></p></div>
    </div>

    <?php if ($deleted): ?><div class="success-msg"><?php echo $deleted; ?></div><?php endif; ?>

    <a class="btn-add" href="/brainhub/brainhub/admin/add-game.php">+ Add New Game</a>
    <h2 style="font-family:'Orbitron',monospace;font-size:18px;margin-bottom:16px">All Games</h2>
    <div class="table-box">
        <table>
            <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Difficulty</th><th>Plays</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($games as $g): ?>
            <tr>
                <td><?php echo $g['id']; ?></td>
                <td style="color:#e8eaf6;font-weight:600"><?php echo htmlspecialchars($g['title']); ?></td>
                <td><?php echo htmlspecialchars($g['cat_name']); ?></td>
                <td><?php echo $g['difficulty']; ?></td>
                <td><?php echo number_format($g['plays']); ?></td>
                <td><span class="badge-<?php echo $g['status']; ?>"><?php echo $g['status']; ?></span></td>
                <td>
                    <a class="btn-edit" href="/brainhub/brainhub/admin/edit-game.php?id=<?php echo $g['id']; ?>">Edit</a>
                    <a class="btn-del" href="/brainhub/brainhub/admin/delete-game.php?id=<?php echo $g['id']; ?>" onclick="return confirm('Delete this game?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
</body>
</html>
