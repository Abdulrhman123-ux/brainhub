<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";

$slug = $_GET['slug'] ?? '';
if (!$slug) { header("Location: /brainhub/brainhub/"); exit; }

$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->execute([$slug]);
$cat = $stmt->fetch();
if (!$cat) { die("Category not found."); }

$stmt = $pdo->prepare("SELECT * FROM games WHERE category_id = ? AND status = 'active' ORDER BY plays DESC");
$stmt->execute([$cat['id']]);
$games = $stmt->fetchAll();

$pageTitle = $cat['name'];
require_once __DIR__ . "/includes/header.php";

$catColors = [
    'memory'   => ['var(--primary)','rgba(108,99,255,0.1)'],
    'reaction' => ['#f7931e','rgba(247,147,30,0.1)'],
    'sequence' => ['#00b4d8','rgba(0,180,216,0.1)'],
    'logic'    => ['#38b000','rgba(56,176,0,0.1)'],
];
$cc = $catColors[$cat['slug']] ?? ['var(--primary)', 'rgba(108,99,255,0.1)'];
?>

<section class="page-hero">
    <div class="container">
        <div style="font-size:72px; margin-bottom:16px"><?php echo $cat['icon']; ?></div>
        <h1 class="page-title"><?php echo htmlspecialchars($cat['name']); ?></h1>
        <p class="page-subtitle"><?php echo htmlspecialchars($cat['description']); ?></p>
    </div>
</section>

<section class="section-pad" style="padding-top: 20px">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($games as $game): ?>
            <div class="col-lg-3 col-sm-6">
                <div class="game-card">
                    <div class="game-card-top">
                        <div class="game-title"><?php echo htmlspecialchars($game['title']); ?></div>
                        
                    </div>
                    <p class="game-desc"><?php echo htmlspecialchars($game['description']); ?></p>
                    <div class="game-plays">Played <span><?php echo number_format($game['plays']); ?></span> times</div>
                    <a class="btn-play" href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($game['slug']); ?>">▶ Play Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="/brainhub/brainhub/" class="btn-outline-hero">← Back to All Games</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
