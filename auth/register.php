<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../includes/db.php";

$error = $success = "";
$avatars = ['🧠','🦊','🐼','🦁','🐸','🦋','🤖','👾','🎮','🏆','⚡','🎯'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $pass     = $_POST["password"] ?? "";
    $pass2    = $_POST["password2"] ?? "";
    $avatar   = $_POST["avatar"] ?? "🧠";

    if (!$username || !$email || !$pass) {
        $error = "Please fill in all fields.";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $error = "Username must be 3–20 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($pass !== $pass2) {
        $error = "Passwords do not match.";
    } else {
        try {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hash, $avatar]);
            $userId = $pdo->lastInsertId();

            // Auto-login
            $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $user->execute([$userId]);
            $userData = $user->fetch();
            $_SESSION['user_id'] = $userId;
            $_SESSION['user']    = $userData;
            header("Location: /brainhub/brainhub/?welcome=1");
            exit;
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'username')) $error = "That username is already taken.";
            elseif (str_contains($e->getMessage(), 'email')) $error = "That email is already registered.";
            else $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — BrainHub</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#08090d;color:#e8eaf6;font-family:'Syne',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow-x:hidden;}
.bg-glow{position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:600px;background:radial-gradient(ellipse,rgba(108,99,255,0.15) 0%,transparent 70%);pointer-events:none;}
.auth-box{background:#0f1117;border:1px solid #1e2235;border-radius:24px;padding:44px 44px;width:100%;max-width:480px;box-shadow:0 0 80px rgba(108,99,255,0.1);position:relative;z-index:1;}
.brand{font-family:'Orbitron',monospace;font-size:24px;font-weight:900;text-align:center;margin-bottom:4px;}
.brand span{color:#6c63ff;}
.auth-title{font-family:'Orbitron',monospace;font-size:18px;font-weight:700;text-align:center;margin:20px 0 6px;}
.auth-sub{text-align:center;color:#7b7fa3;font-size:14px;margin-bottom:28px;}
.form-label{display:block;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#7b7fa3;margin-bottom:8px;}
.form-input{width:100%;padding:14px 16px;background:#13151e;border:1px solid #1e2235;border-radius:12px;color:#e8eaf6;font-size:15px;font-family:'Syne',sans-serif;outline:none;transition:border-color 0.2s,box-shadow 0.2s;margin-bottom:18px;}
.form-input:focus{border-color:#6c63ff;box-shadow:0 0 0 3px rgba(108,99,255,0.15);}
.btn-submit{width:100%;padding:15px;background:linear-gradient(135deg,#6c63ff,#9b5de5);color:white;border:none;border-radius:12px;font-family:'Orbitron',monospace;font-size:14px;font-weight:700;letter-spacing:1px;cursor:pointer;transition:all 0.2s;margin-top:4px;}
.btn-submit:hover{box-shadow:0 0 30px rgba(108,99,255,0.4);transform:translateY(-1px);}
.error-box{background:rgba(255,0,110,0.1);border:1px solid rgba(255,0,110,0.3);color:#ff006e;padding:13px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;text-align:center;font-weight:600;}
.avatar-pick{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px;}
.avatar-opt{width:44px;height:44px;border-radius:10px;border:2px solid #1e2235;background:#13151e;font-size:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;}
.avatar-opt input{display:none;}
.avatar-opt:has(input:checked),.avatar-opt.selected{border-color:#6c63ff;background:rgba(108,99,255,0.15);transform:scale(1.1);}
.link-row{text-align:center;margin-top:20px;color:#7b7fa3;font-size:14px;}
.link-row a{color:#6c63ff;text-decoration:none;font-weight:700;}
.back-home{display:block;text-align:center;color:#7b7fa3;text-decoration:none;font-size:13px;margin-top:16px;transition:color 0.2s;}
.back-home:hover{color:#6c63ff;}
</style>
</head>
<body>
<div class="bg-glow"></div>
<div class="auth-box">
    <div class="brand">Brain<span>Hub</span></div>
    <div class="auth-title">Create Account</div>
    <div class="auth-sub">Join and track your brain training progress.</div>

    <?php if($error): ?>
    <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label class="form-label">Choose Your Avatar</label>
        <div class="avatar-pick">
            <?php foreach($avatars as $i => $av): ?>
            <label class="avatar-opt <?php echo $i===0?'selected':''; ?>">
                <input type="radio" name="avatar" value="<?php echo $av; ?>" <?php echo $i===0?'checked':''; ?> onchange="document.querySelectorAll('.avatar-opt').forEach(el=>el.classList.remove('selected'));this.closest('.avatar-opt').classList.add('selected')">
                <?php echo $av; ?>
            </label>
            <?php endforeach; ?>
        </div>

        <label class="form-label">Username</label>
        <input class="form-input" type="text" name="username" placeholder="e.g. BrainMaster99" minlength="3" maxlength="20" required>

        <label class="form-label">Email</label>
        <input class="form-input" type="email" name="email" placeholder="your@email.com" required>

        <label class="form-label">Password</label>
        <input class="form-input" type="password" name="password" placeholder="At least 6 characters" minlength="6" required>

        <label class="form-label">Confirm Password</label>
        <input class="form-input" type="password" name="password2" placeholder="Repeat password" required>

        <button class="btn-submit" type="submit">Create My Account 🚀</button>
    </form>

    <div class="link-row">Already have an account? <a href="/brainhub/brainhub/auth/login.php">Sign in</a></div>
    <a class="back-home" href="/brainhub/brainhub/">← Back to BrainHub</a>
</div>
</body>
</html>
