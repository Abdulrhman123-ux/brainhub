<?php
session_start();
if (!isset($_SESSION["admin_logged"])) { header("Location: /brainhub/brainhub/admin/login.php"); exit; }
require_once __DIR__ . "/../includes/db.php";

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title      = trim($_POST["title"] ?? "");
    $slug       = trim($_POST["slug"] ?? "");
    $desc       = trim($_POST["description"] ?? "");
    $diff       = $_POST["difficulty"] ?? "Medium";
    $cat_id     = (int)($_POST["category_id"] ?? 0);
    $status     = $_POST["status"] ?? "active";

    if (!$title || !$slug || !$cat_id) {
        $error = "Title, slug, and category are required.";
    } else {
        try {
            $pdo->prepare("INSERT INTO games (category_id,title,slug,description,difficulty,status) VALUES (?,?,?,?,?,?)")
                ->execute([$cat_id,$title,$slug,$desc,$diff,$status]);
            $success = "Game added successfully!";
        } catch (PDOException $e) {
            $error = "Could not add game — the slug may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Add Game – BrainHub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;}body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;padding:40px;}
h1{font-family:'Orbitron',monospace;margin-bottom:24px;}
.box{background:#0f1117;border:1px solid #1e2235;border-radius:16px;padding:32px;max-width:700px;}
label{display:block;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#7b7fa3;margin-bottom:8px;}
input,select,textarea{width:100%;padding:13px 15px;background:#13151e;border:1px solid #1e2235;border-radius:10px;color:#e8eaf6;font-size:15px;font-family:'Syne',sans-serif;outline:none;margin-bottom:20px;transition:border-color 0.2s;}
input:focus,select:focus,textarea:focus{border-color:#6c63ff;}
.btn{background:linear-gradient(135deg,#6c63ff,#9b5de5);color:white;border:none;padding:13px 28px;border-radius:10px;font-family:'Orbitron',monospace;font-size:13px;font-weight:700;cursor:pointer;letter-spacing:1px;}
.back{color:#6c63ff;text-decoration:none;display:inline-block;margin-bottom:20px;}
.success{background:rgba(56,176,0,0.1);border:1px solid rgba(56,176,0,0.3);color:#38b000;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-weight:600;}
.error{background:rgba(255,0,110,0.1);border:1px solid rgba(255,0,110,0.3);color:#ff006e;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-weight:600;}
</style>
</head>
<body>
<a class="back" href="/brainhub/brainhub/admin/">← Back to Admin</a>
<h1>Add New Game</h1>
<?php if($success):?><div class="success"><?php echo $success;?></div><?php endif;?>
<?php if($error):?><div class="error"><?php echo $error;?></div><?php endif;?>
<div class="box">
<form method="POST">
    <label>Game Title *</label>
    <input type="text" name="title" required placeholder="e.g. Sudoku Challenge">
    <label>Slug * <span style="color:#7b7fa3;font-size:11px">(URL-safe, no spaces)</span></label>
    <input type="text" name="slug" required placeholder="e.g. sudoku-challenge">
    <label>Category *</label>
    <select name="category_id" required>
        <option value="">Select category...</option>
        <?php foreach($cats as $c):?>
        <option value="<?php echo $c['id'];?>"><?php echo htmlspecialchars($c['name']);?></option>
        <?php endforeach;?>
    </select>
    <label>Difficulty</label>
    <select name="difficulty">
        <option value="Easy">Easy</option>
        <option value="Medium" selected>Medium</option>
        <option value="Hard">Hard</option>
    </select>
    <label>Status</label>
    <select name="status">
        <option value="active">Active</option>
        <option value="draft">Draft</option>
    </select>
    <label>Description</label>
    <textarea name="description" rows="4" placeholder="Brief description of the game..."></textarea>
    <button class="btn" type="submit">Add Game</button>
</form>
</div>
</body>
</html>
