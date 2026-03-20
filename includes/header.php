<?php
require_once __DIR__ . "/auth.php";
$_user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — BrainHub' : 'BrainHub — Train Your Brain'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/brainhub/brainhub/assets/css/style.css">
</head>
<body>
<div class="bg-particles" id="bgParticles"></div>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/brainhub/brainhub/">
            <span class="brand-icon">🧠</span>
            <span class="brand-text">Brain<span class="brand-accent">Hub</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item"><a class="nav-link" href="/brainhub/brainhub/">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Categories</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/brainhub/brainhub/category.php?slug=memory">🧠 Memory Games</a></li>
                        <li><a class="dropdown-item" href="/brainhub/brainhub/category.php?slug=reaction">⚡ Reaction Games</a></li>
                        <li><a class="dropdown-item" href="/brainhub/brainhub/category.php?slug=sequence">🎵 Sequence Games</a></li>
                        <li><a class="dropdown-item" href="/brainhub/brainhub/category.php?slug=logic">🔢 Logic & Numbers</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="/brainhub/brainhub/daily-quest.php">⚔️ Daily Quest</a></li>
                <li class="nav-item"><a class="nav-link" href="/brainhub/brainhub/leaderboard.php">🏆 Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/brainhub/brainhub/about.php">About</a></li>

                <?php if($_user): ?>
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                        <span style="font-size:20px"><?php echo $_user['avatar']; ?></span>
                        <span style="color:var(--primary);font-weight:700"><?php echo htmlspecialchars($_user['username']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                        <li><a class="dropdown-item" href="/brainhub/brainhub/profile.php">📊 My Progress</a></li>
                        <li><hr class="dropdown-divider" style="border-color:var(--border)"></li>
                        <li><a class="dropdown-item" href="/brainhub/brainhub/auth/logout.php" style="color:#ff006e">🚪 Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item ms-1"><a class="nav-link" href="/brainhub/brainhub/auth/login.php">Sign In</a></li>
                <li class="nav-item ms-1">
                    <a class="btn btn-play-now" href="/brainhub/brainhub/auth/register.php">Join Free</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="main-wrapper">
