<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";
$_user = currentUser();
$pageTitle = "Train Your Brain";

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$featuredGames = $pdo->query("
    SELECT games.*, categories.name AS cat_name, categories.slug AS cat_slug
    FROM games
    JOIN categories ON games.category_id = categories.id
    WHERE games.status = 'active'
    ORDER BY games.plays DESC
    LIMIT 8
")->fetchAll();

$today = date("Y-m-d");
$todayQuests = $pdo->prepare("
    SELECT dq.*, games.slug, games.description
    FROM daily_quests dq
    JOIN games ON dq.game_id = games.id
    WHERE dq.quest_date = ?
    LIMIT 3
");
$todayQuests->execute([$today]);
$quests = $todayQuests->fetchAll();

require_once __DIR__ . "/includes/header.php";

$catColors = [
    'memory'   => ['--primary','rgba(108,99,255,0.1)'],
    'reaction' => ['#f7931e','rgba(247,147,30,0.1)'],
    'sequence' => ['#00b4d8','rgba(0,180,216,0.1)'],
    'logic'    => ['#38b000','rgba(56,176,0,0.1)'],
];
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-glow"></div>
    <div class="container">
        <div class="hero-badge">
            <span>⚡</span> DAILY QUESTS AVAILABLE
        </div>
        <h1 class="hero-title">
            <span class="line1">Train Your Mind,</span><br>
            <span class="line2">Beat Your Brain.</span>
        </h1>
        <p class="hero-sub">16 free browser games designed to sharpen memory, boost reaction time, and keep your mind at peak performance.</p>
        <div class="hero-actions">
            <a href="/brainhub/brainhub/daily-quest.php" class="btn-primary-hero">⚔️ Play Daily Quest</a>
            <a href="#categories" class="btn-outline-hero">Explore Games ↓</a>
        </div>
        <div class="hero-stats">
            <div class="hstat">
                <span class="hstat-num">16+</span>
                <span class="hstat-label">Brain Games</span>
            </div>
            <div class="hstat">
                <span class="hstat-num">4</span>
                <span class="hstat-label">Categories</span>
            </div>
            <div class="hstat">
                <span class="hstat-num">Free</span>
                <span class="hstat-label">Always</span>
            </div>
            <div class="hstat">
                <span class="hstat-num">Daily</span>
                <span class="hstat-label">Challenges</span>
            </div>
        </div>
    </div>
</section>

<!-- WELCOME BANNER -->
<?php if(isset($_GET["welcome"]) && $_user): ?>
<div class="container" style="max-width:1200px;padding:0 16px">
    <div class="welcome-banner">
        <span style="font-size:40px"><?php echo $_user["avatar"]; ?></span>
        <div>
            <div style="font-family:var(--font-display);font-size:18px;font-weight:700">Welcome, <?php echo htmlspecialchars($_user["username"]); ?>! 🎉</div>
            <div style="color:var(--text-muted);font-size:14px">Your account is ready. Start playing and track your progress!</div>
        </div>
        <a href="/brainhub/brainhub/profile.php" class="btn-play" style="margin-left:auto;white-space:nowrap">My Profile</a>
    </div>
</div>
<?php endif; ?>

<!-- CATEGORIES -->
<section class="section-pad" id="categories">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Game Modes</span>
            <h2 class="section-title">Choose Your Challenge</h2>
            <p class="section-sub">4 categories, each targeting a different cognitive skill.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $i => $cat):
                $cc = $catColors[$cat['slug']] ?? ['var(--primary)', 'rgba(108,99,255,0.1)'];
                $count = $pdo->prepare("SELECT COUNT(*) FROM games WHERE category_id = ? AND status='active'");
                $count->execute([$cat['id']]);
                $gameCount = $count->fetchColumn();
            ?>
            <div class="col-lg-3 col-sm-6 fade-in-delay-<?php echo min($i+1,3); ?>">
                <a href="/brainhub/brainhub/category.php?slug=<?php echo urlencode($cat['slug']); ?>"
                   class="category-card"
                   style="--cat-color:<?php echo $cc[0]; ?>; --cat-glow:<?php echo $cc[1]; ?>">
                    <span class="cat-icon"><?php echo $cat['icon']; ?></span>
                    <div class="cat-name"><?php echo htmlspecialchars($cat['name']); ?></div>
                    <div class="cat-desc"><?php echo htmlspecialchars($cat['description']); ?></div>
                    <div class="cat-count"><?php echo $gameCount; ?> Games →</div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED GAMES -->
<section class="section-pad" style="padding-top:0">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Most Played</span>
            <h2 class="section-title">🔥 Top Games</h2>
            <p class="section-sub">The games players keep coming back to.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredGames as $game): ?>
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
    </div>
</section>

<!-- DAILY QUEST SECTION -->
<section class="section-pad" style="padding-top:0">
    <div class="container">
        <div class="quest-section">
            <div class="row align-items-center g-4">
                <div class="col-lg-4">
                    <span class="section-label">Today's Challenge</span>
                    <h2 class="section-title">⚔️ Daily Quest</h2>
                    <p class="section-sub" style="text-align:left">3 new challenges every day. Complete them all for maximum brain gains.</p>
                    <a href="/brainhub/brainhub/daily-quest.php" class="btn-primary-hero mt-3" style="display:inline-flex">View All Quests</a>
                </div>
                <div class="col-lg-8">
                    <?php if (!empty($quests)): ?>
                    <div class="row g-3">
                        <?php foreach ($quests as $q): ?>
                        <div class="col-md-4">
                            <div class="quest-card">
                                
                                <div class="game-title mb-2"><?php echo htmlspecialchars($q['quest_title']); ?></div>
                                <p class="game-desc"><?php echo htmlspecialchars($q['description']); ?></p>
                                <a class="btn-play" href="/brainhub/brainhub/game.php?slug=<?php echo urlencode($q['slug']); ?>">▶ Play</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="quest-card text-center p-4">
                        <p class="text-muted mb-3">No quests generated yet for today.</p>
                        <a href="/brainhub/brainhub/daily-quest.php" class="btn-play">Generate Today's Quests</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- WHY BRAIN GAMES -->
<section class="section-pad" style="padding-top:0">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Science-Backed</span>
            <h2 class="section-title">Why Play Brain Games?</h2>
        </div>
        <div class="row g-4">
            <?php
            $benefits = [
                ['🧠','Stronger Memory','Regularly exercising working memory helps retain more information in everyday life.'],
                ['⚡','Faster Reactions','Reaction games train your nervous system to respond to stimuli more quickly.'],
                ['🎯','Better Focus','Sequence and logic games strengthen sustained attention and concentration.'],
                ['🔢','Number Skills','Math and digit games keep your numerical reasoning sharp and agile.'],
            ];
            foreach ($benefits as $b):
            ?>
            <div class="col-lg-3 col-sm-6">
                <div class="game-card" style="text-align:center; align-items:center">
                    <div style="font-size:48px; margin-bottom:16px"><?php echo $b[0]; ?></div>
                    <div class="game-title mb-2"><?php echo $b[1]; ?></div>
                    <p class="game-desc"><?php echo $b[2]; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
