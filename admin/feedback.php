<?php
session_start();
if (!isset($_SESSION["admin_logged"])) { header("Location: /brainhub/brainhub/admin/login.php"); exit; }
require_once __DIR__ . "/../includes/db.php";

$feedback = $pdo->query("SELECT * FROM feedback ORDER BY submitted_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Feedback – BrainHub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;}body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;padding:40px;}
h1{font-family:'Orbitron',monospace;margin-bottom:24px;}
.back{color:#6c63ff;text-decoration:none;display:inline-block;margin-bottom:20px;}
.card{background:#0f1117;border:1px solid #1e2235;border-radius:14px;padding:24px;margin-bottom:16px;max-width:800px;}
.meta{font-size:13px;color:#7b7fa3;margin-bottom:10px;}
.msg{color:#e8eaf6;font-size:15px;line-height:1.7;}
.empty{color:#7b7fa3;text-align:center;padding:60px;font-size:18px;}
</style>
</head>
<body>
<a class="back" href="/brainhub/brainhub/admin/">← Back to Admin</a>
<h1>💬 Feedback (<?php echo count($feedback); ?>)</h1>
<?php if(empty($feedback)):?>
<div class="empty">No feedback yet.</div>
<?php else: foreach($feedback as $f):?>
<div class="card">
    <div class="meta">
        <strong style="color:#e8eaf6"><?php echo htmlspecialchars($f['name']); ?></strong>
        <?php if($f['email']):?> · <a href="mailto:<?php echo htmlspecialchars($f['email']);?>" style="color:#6c63ff"><?php echo htmlspecialchars($f['email']);?></a><?php endif;?>
        · <?php echo date('M j, Y H:i', strtotime($f['submitted_at']));?>
    </div>
    <div class="msg"><?php echo nl2br(htmlspecialchars($f['message']));?></div>
</div>
<?php endforeach; endif;?>
</body>
</html>
