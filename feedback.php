<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";
$pageTitle = "Feedback";

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");
    if ($name === "" || $message === "") {
        $error = "Please fill in your name and message.";
    } else {
        $pdo->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)")
            ->execute([$name, $email, $message]);
        $success = "Thank you! Your feedback was submitted successfully.";
    }
}

require_once __DIR__ . "/includes/header.php";
?>

<section class="page-hero">
    <div class="container">
        <div style="font-size:64px;margin-bottom:16px">💬</div>
        <h1 class="page-title">Send Feedback</h1>
        <p class="page-subtitle">Found a bug? Have a game idea? We'd love to hear from you.</p>
    </div>
</section>

<section class="section-pad" style="padding-top:20px">
    <div class="container" style="max-width:600px">
        <?php if ($success): ?>
        <div class="alert-custom alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert-custom alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:40px">
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label-dark">Your Name *</label>
                    <input type="text" name="name" class="form-control-dark" placeholder="Enter your name" required>
                </div>
                <div class="mb-4">
                    <label class="form-label-dark">Email (optional)</label>
                    <input type="email" name="email" class="form-control-dark" placeholder="your@email.com">
                </div>
                <div class="mb-4">
                    <label class="form-label-dark">Message *</label>
                    <textarea name="message" class="form-control-dark" rows="6" placeholder="Tell us what you think, suggest a game, or report a bug..." required></textarea>
                </div>
                <button type="submit" class="btn-game btn-game-primary w-100">Send Feedback 🚀</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
