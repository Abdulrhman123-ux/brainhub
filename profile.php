<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";
requireLogin();

$user = currentUser();
$userId = $_SESSION['user_id'];

// Refresh user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Progress per game
$progress = $pdo->prepare("
    SELECT up.*, games.title, games.slug, categories.name AS cat_name, categories.icon
    FROM user_progress up
    JOIN games ON up.game_id = games.id
    JOIN categories ON games.category_id = categories.id
    WHERE up.user_id = ?
    ORDER BY up.last_played DESC
");
$progress->execute([$userId]);
$progressRows = $progress->fetchAll();

// Recent scores
$recentScores = $pdo->prepare("
    SELECT scores.*, games.title, games.slug
    FROM scores
    JOIN games ON scores.game_id = games.id
    WHERE scores.user_id = ?
    ORDER BY scores.played_at DESC
    LIMIT 10
");
$recentScores->execute([$userId]);
$recentRows = $recentScores->fetchAll();

// Stats
$totalBest = array_sum(array_column($progressRows, 'best_score'));
$gamesUnlocked = count(array_unique(array_column($progressRows, 'game_id')));

$pageTitle = $user['username'] . "'s Profile";
require_once __DIR__ . "/includes/header.php";
?>

<section class="section-pad">
<div class="container" style="max-width:960px">

    <!-- Profile Header -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:40px;margin-bottom:28px;display:flex;gap:28px;align-items:center;flex-wrap:wrap">
        <div style="font-size:80px;line-height:1;flex-shrink:0"><?php echo $user['avatar']; ?></div>
        <div style="flex:1;min-width:200px">
            <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;margin-bottom:6px"><?php echo htmlspecialchars($user['username']); ?></h1>
            <p style="color:var(--text-muted);font-size:14px;margin-bottom:20px">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
            <div style="display:flex;gap:20px;flex-wrap:wrap">
                <div class="score-box"><span class="score-num"><?php echo number_format($user['total_score']); ?></span><span class="score-label">Total Score</span></div>
                <div class="score-box"><span class="score-num"><?php echo $user['games_played']; ?></span><span class="score-label">Games Played</span></div>
                <div class="score-box"><span class="score-num"><?php echo $gamesUnlocked; ?></span><span class="score-label">Games Tried</span></div>
                <div class="score-box"><span class="score-num"><?php echo count($progressRows); ?></span><span class="score-label">Difficulties</span></div>
            </div>
        </div>
        <a href="/brainhub/brainhub/auth/logout.php" style="color:var(--text-muted);text-decoration:none;font-size:13px;border:1px solid var(--border);padding:10px 18px;border-radius:50px;transition:all 0.2s;align-self:flex-start" onmouseover="this.style.borderColor='var(--accent-pink)';this.style.color='var(--accent-pink)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">Logout</a>
    </div>

    <!-- Progress Grid -->
    <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:20px">📊 Your Progress</h2>

    <?php if(empty($progressRows)): ?>
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:50px;text-align:center;margin-bottom:28px">
        <div style="font-size:50px;margin-bottom:16px">🎮</div>
        <p style="color:var(--text-muted);margin-bottom:20px">You haven't played any games yet. Start playing to track your progress!</p>
        <a href="/brainhub/brainhub/" class="btn-primary-hero">Start Playing</a>
    </div>
    <?php else: ?>
    <div class="row g-3 mb-4">
        <?php foreach($progressRows as $row):
            $diffColor = $row['difficulty']==='Easy'?'var(--accent-green)':($row['difficulty']==='Medium'?'var(--accent-orange)':'var(--accent-pink)');
        ?>
        <div class="col-md-4">
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:20px;height:100%">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                    <div style="font-family:var(--font-display);font-size:14px;font-weight:700;color:var(--text-main)"><?php echo htmlspecialchars($row['title']); ?></div>
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:50px;background:<?php echo $diffColor; ?>20;color:<?php echo $diffColor; ?>;border:1px solid <?php echo $diffColor; ?>40;white-space:nowrap"><?php echo $row['difficulty']; ?></span>
                </div>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:12px"><?php echo $row['icon']; ?> <?php echo htmlspecialchars($row['cat_name']); ?></div>
                <div style="display:flex;gap:16px">
                    <div><span style="font-family:var(--font-display);font-size:20px;font-weight:900;color:var(--primary)"><?php echo number_format($row['best_score']); ?></span><br><span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">Best</span></div>
                    <div><span style="font-family:var(--font-display);font-size:20px;font-weight:900;color:var(--accent-cyan)"><?php echo $row['times_played']; ?></span><br><span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">Plays</span></div>
                </div>
                <a href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($row['slug']); ?>&diff=<?php echo $row['difficulty']; ?>" class="btn-play mt-3 d-block" style="text-align:center">Play Again</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Recent Scores -->
    <?php if(!empty($recentRows)): ?>
    <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:20px">🕐 Recent Games</h2>
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:28px">
        <table class="leaderboard-table">
            <thead><tr><th>Game</th><th>Difficulty</th><th>Score</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach($recentRows as $r): ?>
            <tr>
                <td style="color:var(--text-main);font-weight:600"><a href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($r['slug']); ?>" style="color:var(--primary);text-decoration:none"><?php echo htmlspecialchars($r['title']); ?></a></td>
                <td><?php echo $r['difficulty']; ?></td>
                <td style="font-family:var(--font-display);font-weight:700;color:var(--primary)"><?php echo number_format($r['score']); ?></td>
                <td><?php echo date('M j, H:i', strtotime($r['played_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
