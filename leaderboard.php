<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";
$pageTitle = "Leaderboard";

$games = $pdo->query("SELECT id, title, slug FROM games WHERE status='active' ORDER BY title")->fetchAll();
$selectedGame = $_GET['game'] ?? ($games[0]['id'] ?? null);
$selectedDiff = $_GET['diff'] ?? 'All';

$topScores = [];
if ($selectedGame) {
    if ($selectedDiff !== 'All') {
        $stmt = $pdo->prepare("SELECT scores.*, games.title AS game_title FROM scores JOIN games ON scores.game_id = games.id WHERE scores.game_id = ? AND scores.difficulty = ? ORDER BY scores.score DESC LIMIT 20");
        $stmt->execute([$selectedGame, $selectedDiff]);
    } else {
        $stmt = $pdo->prepare("SELECT scores.*, games.title AS game_title FROM scores JOIN games ON scores.game_id = games.id WHERE scores.game_id = ? ORDER BY scores.score DESC LIMIT 20");
        $stmt->execute([$selectedGame]);
    }
    $topScores = $stmt->fetchAll();
}

require_once __DIR__ . "/includes/header.php";
?>
<section class="page-hero">
    <div class="container">
        <div style="font-size:64px;margin-bottom:16px">🏆</div>
        <h1 class="page-title">Leaderboard</h1>
        <p class="page-subtitle">Top scores across all brain games.</p>
    </div>
</section>

<section class="section-pad" style="padding-top:20px">
    <div class="container" style="max-width:820px">

        <!-- Game selector -->
        <div class="mb-3 text-center">
            <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center">
                <?php foreach ($games as $g): ?>
                <a href="/brainhub/brainhub/leaderboard.php?game=<?php echo $g['id']; ?>&diff=<?php echo urlencode($selectedDiff); ?>"
                   style="padding:9px 16px;border-radius:50px;border:1px solid var(--border);font-size:12px;font-weight:700;text-decoration:none;transition:all 0.2s;<?php echo $g['id']==$selectedGame?'background:var(--primary);color:white;border-color:var(--primary)':'color:var(--text-muted)'; ?>">
                    <?php echo htmlspecialchars($g['title']); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Difficulty selector -->
        <div class="mb-4 text-center">
            <?php foreach (['All','Easy','Medium','Hard'] as $d):
                $colors = ['All'=>'var(--primary)','Easy'=>'var(--accent-green)','Medium'=>'var(--accent-orange)','Hard'=>'var(--accent-pink)'];
                $col = $colors[$d];
            ?>
            <a href="/brainhub/brainhub/leaderboard.php?game=<?php echo $selectedGame; ?>&diff=<?php echo urlencode($d); ?>"
               style="display:inline-block;margin:4px;padding:8px 20px;border-radius:50px;font-size:12px;font-weight:700;text-decoration:none;border:2px solid <?php echo $col; ?><?php echo $selectedDiff===$d?'':'40'; ?>;background:<?php echo $selectedDiff===$d?$col.'20':'transparent'; ?>;color:<?php echo $selectedDiff===$d?$col:'var(--text-muted)'; ?>;transition:all 0.2s">
                <?php echo $d; ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
            <?php if (empty($topScores)): ?>
            <div class="text-center py-5" style="color:var(--text-muted)">
                <div style="font-size:48px;margin-bottom:16px">🎮</div>
                <p>No scores yet. Be the first to play!</p>
            </div>
            <?php else: ?>
            <table class="leaderboard-table">
                <thead>
                    <tr><th>Rank</th><th>Player</th><th>Difficulty</th><th>Score</th><th>Date</th></tr>
                </thead>
                <tbody>
                <?php foreach ($topScores as $i => $row): ?>
                <tr class="<?php echo $i<3?'rank-'.($i+1):''; ?>">
                    <td><?php echo $i===0?'🥇':($i===1?'🥈':($i===2?'🥉':'#'.($i+1))); ?></td>
                    <td style="font-weight:600"><?php echo htmlspecialchars($row['player_name']); ?></td>
                    <td><?php echo $row['difficulty']; ?></td>
                    <td style="font-family:var(--font-display);font-weight:700"><?php echo number_format($row['score']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['played_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
