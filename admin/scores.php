<?php
session_start();
if (!isset($_SESSION["admin_logged"])) { header("Location: /brainhub/brainhub/admin/login.php"); exit; }
require_once __DIR__ . "/../includes/db.php";

$scores = $pdo->query("
    SELECT scores.*, games.title AS game_title
    FROM scores JOIN games ON scores.game_id = games.id
    ORDER BY scores.played_at DESC
    LIMIT 100
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Scores – BrainHub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;}body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;padding:40px;}
h1{font-family:'Orbitron',monospace;margin-bottom:24px;}
.back{color:#6c63ff;text-decoration:none;display:inline-block;margin-bottom:20px;}
.table-box{background:#0f1117;border:1px solid #1e2235;border-radius:14px;overflow:hidden;max-width:900px;}
table{width:100%;border-collapse:collapse;}
th{background:#13151e;color:#7b7fa3;font-size:12px;letter-spacing:1px;text-transform:uppercase;padding:14px 16px;text-align:left;}
td{padding:13px 16px;border-bottom:1px solid #1e2235;font-size:14px;color:#b0b4cc;}
tr:hover td{background:rgba(108,99,255,0.04);}
</style>
</head>
<body>
<a class="back" href="/brainhub/brainhub/admin/">← Back to Admin</a>
<h1>🏆 Top Scores</h1>
<div class="table-box">
<table>
<thead><tr><th>#</th><th>Player</th><th>Game</th><th>Score</th><th>Date</th></tr></thead>
<tbody>
<?php foreach($scores as $i=>$s):?>
<tr>
    <td><?php echo $i+1;?></td>
    <td style="color:#e8eaf6;font-weight:600"><?php echo htmlspecialchars($s['player_name']);?></td>
    <td><?php echo htmlspecialchars($s['game_title']);?></td>
    <td style="font-family:'Orbitron',monospace;color:#6c63ff;font-weight:700"><?php echo number_format($s['score']);?></td>
    <td><?php echo date('M j, Y H:i', strtotime($s['played_at']));?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
</body>
</html>
