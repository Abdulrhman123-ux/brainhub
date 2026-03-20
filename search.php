<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";
$pageTitle = "Search Games";

$q = trim($_GET["q"] ?? "");
$results = [];

if ($q !== "") {
    $stmt = $pdo->prepare("
        SELECT games.*, categories.name AS cat_name, categories.slug AS cat_slug
        FROM games
        JOIN categories ON games.category_id = categories.id
        WHERE games.status = 'active'
          AND (games.title LIKE ? OR games.description LIKE ? OR categories.name LIKE ?)
        ORDER BY games.plays DESC
    ");
    $term = "%" . $q . "%";
    $stmt->execute([$term, $term, $term]);
    $results = $stmt->fetchAll();
}

require_once __DIR__ . "/includes/header.php";
?>

<section class="page-hero">
    <div class="container">
        <h1 class="page-title">Search Games</h1>
        <p class="page-subtitle">Find your next brain challenge.</p>
    </div>
</section>

<section class="section-pad" style="padding-top:20px">
    <div class="container">
        <form method="GET" action="/brainhub/brainhub/search.php" style="display:flex;gap:12px;max-width:500px;margin:0 auto 40px">
            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search games..." class="form-control-dark" style="flex:1;border-radius:50px;padding:14px 20px">
            <button type="submit" class="btn-game btn-game-primary" style="border-radius:50px;padding:14px 24px">Search</button>
        </form>

        <?php if ($q !== ""): ?>
        <div class="section-header">
            <h2 class="section-title">Results for "<?php echo htmlspecialchars($q); ?>"</h2>
            <p class="section-sub"><?php echo count($results); ?> game<?php echo count($results)!==1?'s':''; ?> found</p>
        </div>
        <?php if (!empty($results)): ?>
        <div class="row g-4">
            <?php foreach ($results as $game): ?>
            <div class="col-lg-3 col-sm-6">
                <div class="game-card">
                    <div class="game-card-top">
                        <div class="game-title"><?php echo htmlspecialchars($game['title']); ?></div>
                        
                    </div>
                    <div class="game-plays mb-1">Category: <span><?php echo htmlspecialchars($game['cat_name']); ?></span></div>
                    <p class="game-desc"><?php echo htmlspecialchars($game['description']); ?></p>
                    <a class="btn-play" href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($game['slug']); ?>">▶ Play Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <div style="font-size:60px;margin-bottom:16px">🔍</div>
            <p style="color:var(--text-muted)">No games found for "<?php echo htmlspecialchars($q); ?>". Try another keyword.</p>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
