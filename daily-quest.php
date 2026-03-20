<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";
$pageTitle = "Daily Quest";
require_once __DIR__ . "/includes/header.php";

$today = date("Y-m-d");

// Check if quests exist for today
$stmt = $pdo->prepare("SELECT COUNT(*) FROM daily_quests WHERE quest_date = ?");
$stmt->execute([$today]);
$count = $stmt->fetchColumn();

// Generate if missing
if ($count == 0) {
    $stmt = $pdo->query("SELECT id, title FROM games WHERE status = 'active' ORDER BY RAND() LIMIT 3");
    $randomGames = $stmt->fetchAll();
    $insert = $pdo->prepare("INSERT INTO daily_quests (quest_date, game_id, quest_title, quest_type) VALUES (?, ?, ?, ?)");
    foreach ($randomGames as $game) {
        $insert->execute([$today, $game['id'], $game['title'], 'daily']);
    }
}

// Load today's quests with full game info
$stmt = $pdo->prepare("
    SELECT dq.*, games.slug, games.description, categories.name AS cat_name
    FROM daily_quests dq
    JOIN games ON dq.game_id = games.id
    JOIN categories ON games.category_id = categories.id
    WHERE dq.quest_date = ?
");
$stmt->execute([$today]);
$quests = $stmt->fetchAll();
?>

<section class="page-hero">
    <div class="container">
        <div style="font-size:64px;margin-bottom:16px">⚔️</div>
        <h1 class="page-title">Daily Quest</h1>
        <p class="page-subtitle">Three brain challenges, refreshed every day. Complete them all!</p>
        <div style="display:inline-block;background:rgba(108,99,255,0.1);border:1px solid rgba(108,99,255,0.2);border-radius:50px;padding:10px 24px;margin-top:16px;font-family:var(--font-display);font-size:14px;color:var(--primary);letter-spacing:1px">
            📅 <?php echo date("l, F j, Y"); ?>
        </div>
    </div>
</section>

<section class="section-pad" style="padding-top:20px">
    <div class="container" style="max-width:900px">
        <div class="row g-4">
            <?php foreach ($quests as $i => $q): ?>
            <div class="col-md-4">
                <div class="game-card" style="text-align:center;align-items:center;position:relative;overflow:visible">
                    <div style="position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,var(--primary),#9b5de5);color:white;font-family:var(--font-display);font-size:12px;font-weight:700;letter-spacing:1px;padding:5px 16px;border-radius:50px;white-space:nowrap">
                        Quest <?php echo $i+1; ?>
                    </div>
                    <div style="font-size:40px;margin:20px 0 12px">
                        <?php echo $i===0?'🥇':($i===1?'🥈':'🥉'); ?>
                    </div>
                    <div class="game-title mb-2"><?php echo htmlspecialchars($q['quest_title']); ?></div>
                    
                    <p class="game-desc"><?php echo htmlspecialchars($q['description']); ?></p>
                    <div class="game-plays mb-3">Category: <span><?php echo htmlspecialchars($q['cat_name']); ?></span></div>
                    <a class="btn-play" href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($q['slug']); ?>">▶ Accept Quest</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:32px 40px;max-width:600px;margin:0 auto">
                <div style="font-size:40px;margin-bottom:12px">💡</div>
                <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:10px">Why Daily Quests?</h3>
                <p style="color:var(--text-muted);font-size:15px">Consistent daily brain training — even just 10 minutes — builds long-term cognitive strength. The quests refresh every day so your brain never gets too comfortable.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
