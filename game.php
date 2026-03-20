<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";

$slug = $_GET['slug'] ?? '';
if (!$slug) { header("Location: /brainhub/brainhub/"); exit; }

$stmt = $pdo->prepare("
    SELECT games.*, categories.name AS cat_name, categories.slug AS cat_slug, categories.icon AS cat_icon
    FROM games JOIN categories ON games.category_id = categories.id
    WHERE games.slug = ? AND games.status = 'active'
");
$stmt->execute([$slug]);
$game = $stmt->fetch();
if (!$game) { die("Game not found."); }

$pdo->prepare("UPDATE games SET plays = plays + 1 WHERE id = ?")->execute([$game['id']]);

$difficulty = $_GET['diff'] ?? 'Medium';
if (!in_array($difficulty, ['Easy','Medium','Hard'])) $difficulty = 'Medium';

$pageTitle = $game['title'];
$_user = currentUser();
require_once __DIR__ . "/includes/header.php";
?>

<section class="section-pad">
<div class="container">

    <!-- Game Header -->
    <div class="game-header mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <div class="game-meta mb-2">
                    <a href="/brainhub/brainhub/category.php?slug=<?php echo $game['cat_slug']; ?>">
                        <?php echo $game['cat_icon']; ?> <?php echo htmlspecialchars($game['cat_name']); ?>
                    </a>
                    <span style="color:var(--border)"> / </span>
                    <?php echo htmlspecialchars($game['title']); ?>
                </div>
                <h1 class="game-page-title"><?php echo htmlspecialchars($game['title']); ?></h1>
                <p style="color:var(--text-muted);margin-top:8px"><?php echo htmlspecialchars($game['description']); ?></p>
            </div>
        </div>

        <!-- Difficulty Selector -->
        <div style="margin-top:20px">
            <span style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--text-muted);margin-right:14px">Difficulty:</span>
            <?php foreach(['Easy','Medium','Hard'] as $d):
                $active = $d === $difficulty;
                $colors = ['Easy'=>'var(--accent-green)','Medium'=>'var(--accent-orange)','Hard'=>'var(--accent-pink)'];
                $col = $colors[$d];
            ?>
            <a href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($slug); ?>&diff=<?php echo $d; ?>"
               style="display:inline-block;margin-right:8px;padding:8px 20px;border-radius:50px;font-family:var(--font-display);font-size:12px;font-weight:700;letter-spacing:1px;text-decoration:none;border:2px solid <?php echo $col; ?><?php echo $active?'':'40'; ?>;background:<?php echo $active?$col.'20':'transparent'; ?>;color:<?php echo $active?$col:'var(--text-muted)'; ?>;transition:all 0.2s">
                <?php echo $d; ?>
            </a>
            <?php endforeach; ?>

            <?php if($_user): ?>
            <span style="margin-left:16px;font-size:13px;color:var(--text-muted)">
                <?php
                $pb = $pdo->prepare("SELECT best_score,times_played FROM user_progress WHERE user_id=? AND game_id=? AND difficulty=?");
                $pb->execute([$_user['id'],$game['id'],$difficulty]);
                $myBest = $pb->fetch();
                if($myBest && $myBest['best_score']>0):
                ?>
                🏅 Your best: <strong style="color:var(--primary)"><?php echo number_format($myBest['best_score']); ?></strong>
                &nbsp;· Played <strong style="color:var(--accent-cyan)"><?php echo $myBest['times_played']; ?></strong> times
                <?php else: ?>
                🎮 No score yet on <?php echo $difficulty; ?>
                <?php endif; ?>
            </span>
            <?php else: ?>
            <span style="margin-left:16px;font-size:13px;color:var(--text-muted)">
                <a href="/brainhub/brainhub/auth/login.php" style="color:var(--primary)">Sign in</a> to track progress
            </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Game Area -->
    <div class="game-area">
        <?php
        // Pass difficulty as JS variable
        echo "<script>const DIFFICULTY = " . json_encode($difficulty) . ";</script>";
        switch ($game['slug']) {
            case 'card-match':     require __DIR__ . "/games/card-match.php"; break;
            case 'grid-memory':    require __DIR__ . "/games/grid-memory.php"; break;
            case 'word-flash':     require __DIR__ . "/games/word-flash.php"; break;
            case 'face-memory':    require __DIR__ . "/games/face-memory.php"; break;
            case 'reaction-time':  require __DIR__ . "/games/reaction-time.php"; break;
            case 'color-rush':     require __DIR__ . "/games/color-rush.php"; break;
            case 'aim-trainer':    require __DIR__ . "/games/aim-trainer.php"; break;
            case 'dont-click-red': require __DIR__ . "/games/dont-click-red.php"; break;
            case 'simon-says':     require __DIR__ . "/games/simon-says.php"; break;
            case 'number-sequence':require __DIR__ . "/games/number-sequence.php"; break;
            case 'pattern-repeat': require __DIR__ . "/games/pattern-repeat.php"; break;
            case 'rhythm-tap':     require __DIR__ . "/games/rhythm-tap.php"; break;
            case 'number-guess':   require __DIR__ . "/games/number-guess.php"; break;
            case 'digit-span':     require __DIR__ . "/games/digit-span.php"; break;
            case 'math-sprint':    require __DIR__ . "/games/math-sprint.php"; break;
            case 'count-dots':     require __DIR__ . "/games/count-dots.php"; break;
            default: echo '<div class="text-center"><p style="color:var(--text-muted)">Game coming soon!</p></div>';
        }
        ?>
    </div>

    <!-- Hidden score form -->
    <form id="scoreForm" method="POST" action="/brainhub/brainhub/save-score.php" style="display:none">
        <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
        <input type="hidden" name="difficulty" value="<?php echo $difficulty; ?>">
        <input type="hidden" name="score" id="finalScore">
        <input type="hidden" name="user_id" value="<?php echo $_user ? $_user['id'] : ''; ?>">
        <input type="hidden" name="player_name" value="<?php echo $_user ? htmlspecialchars($_user['username']) : 'Anonymous'; ?>">
    </form>

    <div class="text-center mt-4" style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="/brainhub/brainhub/category.php?slug=<?php echo $game['cat_slug']; ?>" class="btn-outline-hero">
            ← More <?php echo htmlspecialchars($game['cat_name']); ?>
        </a>
        <?php if($_user): ?>
        <a href="/brainhub/brainhub/profile.php" class="btn-outline-hero">📊 My Progress</a>
        <?php endif; ?>
    </div>
</div>
</section>

<!-- Score submission JS helper -->
<script>
function submitScore(score) {
    document.getElementById('finalScore').value = score;
    fetch('/save-score.php', {
        method: 'POST',
        body: new FormData(document.getElementById('scoreForm'))
    });
}
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
