<?php
session_start();
if (isset($_SESSION["admin_logged"])) { header("Location: /brainhub/brainhub/admin/"); exit; }
require_once __DIR__ . "/../includes/db.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["username"] ?? "";
    $pass = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$user]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($pass, $admin["password"])) {
        $_SESSION["admin_logged"] = true;
        header("Location: /brainhub/brainhub/admin/");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>BrainHub Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;}
.login-box{background:#0f1117;border:1px solid #1e2235;border-radius:20px;padding:48px 40px;width:360px;box-shadow:0 0 60px rgba(108,99,255,0.1);}
.brand{font-family:'Orbitron',monospace;font-size:26px;font-weight:900;text-align:center;margin-bottom:8px;}
.brand span{color:#6c63ff;}
.sub{text-align:center;color:#7b7fa3;font-size:14px;margin-bottom:32px;}
label{display:block;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#7b7fa3;margin-bottom:8px;}
input{width:100%;padding:14px 16px;background:#13151e;border:1px solid #1e2235;border-radius:10px;color:#e8eaf6;font-size:15px;font-family:'Syne',sans-serif;outline:none;margin-bottom:18px;transition:border-color 0.2s;}
input:focus{border-color:#6c63ff;box-shadow:0 0 0 3px rgba(108,99,255,0.15);}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#6c63ff,#9b5de5);color:white;border:none;border-radius:10px;font-family:'Orbitron',monospace;font-size:13px;font-weight:700;letter-spacing:1px;cursor:pointer;transition:all 0.2s;margin-top:4px;}
.btn:hover{box-shadow:0 0 25px rgba(108,99,255,0.4);transform:translateY(-1px);}
.error{background:rgba(255,0,110,0.1);border:1px solid rgba(255,0,110,0.3);color:#ff006e;padding:12px 16px;border-radius:8px;font-size:14px;margin-bottom:18px;text-align:center;}
</style>
</head>
<body>
<div class="login-box">
    <div class="brand">Brain<span>Hub</span></div>
    <div class="sub">Admin Panel</div>
    <?php if ($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="admin" required autofocus>
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
        <button class="btn" type="submit">Login →</button>
    </form>
</div>
</body>
</html>
