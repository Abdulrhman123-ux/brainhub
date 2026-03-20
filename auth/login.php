<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../includes/db.php";

$error = "";
$redirect = $_GET['redirect'] ?? '/brainhub/brainhub/';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"] ?? "");
    $pass  = trim($_POST["password"] ?? "");

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user']    = $user;
        header("Location: " . $redirect);
        exit;
    } else {
        $error = "Invalid username/email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — BrainHub</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow:hidden;}
.bg-glow{position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:600px;background:radial-gradient(ellipse,rgba(108,99,255,0.15) 0%,transparent 70%);pointer-events:none;}
.auth-box{background:#0f1117;border:1px solid #1e2235;border-radius:24px;padding:48px 44px;width:100%;max-width:440px;box-shadow:0 0 80px rgba(108,99,255,0.1);position:relative;z-index:1;}
.brand{font-family:'Orbitron',monospace;font-size:24px;font-weight:900;text-align:center;margin-bottom:6px;}
.brand span{color:#6c63ff;}
.auth-title{font-family:'Orbitron',monospace;font-size:18px;font-weight:700;text-align:center;margin-bottom:8px;}
.auth-sub{text-align:center;color:#7b7fa3;font-size:14px;margin-bottom:32px;}
.form-label{display:block;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#7b7fa3;margin-bottom:8px;}
.form-input{width:100%;padding:14px 16px;background:#13151e;border:1px solid #1e2235;border-radius:12px;color:#e8eaf6;font-size:15px;font-family:'Syne',sans-serif;outline:none;transition:border-color 0.2s,box-shadow 0.2s;}
.form-input:focus{border-color:#6c63ff;box-shadow:0 0 0 3px rgba(108,99,255,0.15);}
.btn-submit{width:100%;padding:15px;background:linear-gradient(135deg,#6c63ff,#9b5de5);color:white;border:none;border-radius:12px;font-family:'Orbitron',monospace;font-size:14px;font-weight:700;letter-spacing:1px;cursor:pointer;transition:all 0.2s;margin-top:8px;}
.btn-submit:hover{box-shadow:0 0 30px rgba(108,99,255,0.4);transform:translateY(-1px);}
.error-box{background:rgba(255,0,110,0.1);border:1px solid rgba(255,0,110,0.3);color:#ff006e;padding:13px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;text-align:center;font-weight:600;}
.divider{display:flex;align-items:center;gap:12px;margin:24px 0;color:#3a3d52;font-size:13px;}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:#1e2235;}
.link-row{text-align:center;margin-top:24px;color:#7b7fa3;font-size:14px;}
.link-row a{color:#6c63ff;text-decoration:none;font-weight:700;}
.link-row a:hover{text-decoration:underline;}
.back-home{display:block;text-align:center;color:#7b7fa3;text-decoration:none;font-size:13px;margin-top:20px;transition:color 0.2s;}
.back-home:hover{color:#6c63ff;}
.mb-4{margin-bottom:20px;}
</style>
</head>
<body>
<div class="bg-glow"></div>
<div class="auth-box">
    <div class="brand">Brain<span>Hub</span></div>
    <div class="auth-sub" style="margin-bottom:28px">Welcome back, brain trainer!</div>
    <div class="auth-title">Sign In</div>
    <div class="auth-sub">Track your progress, beat your scores.</div>

    <?php if($error): ?>
    <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
        <div class="mb-4">
            <label class="form-label">Username or Email</label>
            <input class="form-input" type="text" name="login" placeholder="Enter username or email" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input class="form-input" type="password" name="password" placeholder="••••••••" required>
        </div>
        <button class="btn-submit" type="submit">Sign In →</button>
    </form>

    <div class="divider">or</div>

    <div class="link-row">
        Don't have an account? <a href="/brainhub/brainhub/auth/register.php">Create one free</a>
    </div>
    <a class="back-home" href="/brainhub/brainhub/">← Back to BrainHub</a>
</div>
</body>
</html>

