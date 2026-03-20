<?php
$pageTitle = "About BrainHub";
require_once __DIR__ . "/includes/header.php";
?>

<section class="page-hero">
    <div class="container">
        <div style="font-size:64px;margin-bottom:16px">🧠</div>
        <h1 class="page-title">About BrainHub</h1>
        <p class="page-subtitle">A free brain training platform built to make your mind sharper, one game at a time.</p>
    </div>
</section>

<section class="section-pad" style="padding-top:20px">
    <div class="container" style="max-width:800px">
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="game-card h-100">
                    <div style="font-size:40px;margin-bottom:16px">🎯</div>
                    <div class="game-title mb-2">What is BrainHub?</div>
                    <p class="game-desc">BrainHub is a collection of 16 free browser-based brain training games organized into 4 cognitive categories. It's designed to be fun, addictive, and actually beneficial for your mind.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="game-card h-100">
                    <div style="font-size:40px;margin-bottom:16px">📚</div>
                    <div class="game-title mb-2">Built With</div>
                    <p class="game-desc">PHP · MySQL · HTML5 · CSS3 · JavaScript · Bootstrap 5. No frameworks, no external game engines — pure web fundamentals and clean code.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="game-card h-100">
                    <div style="font-size:40px;margin-bottom:16px">🔬</div>
                    <div class="game-title mb-2">The Science</div>
                    <p class="game-desc">Regular cognitive training in memory, reaction speed, and pattern recognition has been shown to improve working memory, processing speed, and problem-solving skills.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="game-card h-100">
                    <div style="font-size:40px;margin-bottom:16px">⚔️</div>
                    <div class="game-title mb-2">Daily Quests</div>
                    <p class="game-desc">Three random games are selected each day as your Daily Quest. Keep the habit going — consistent short sessions beat occasional long ones for memory improvement.</p>
                </div>
            </div>
        </div>

        <div style="background:linear-gradient(135deg,rgba(108,99,255,0.08),rgba(0,180,216,0.05));border:1px solid rgba(108,99,255,0.2);border-radius:var(--radius);padding:40px;text-align:center">
            <h2 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:16px">Game Categories</h2>
            <div class="row g-3 mt-2">
                <?php
                $cats = [
                    ['🧠','Memory Games','Flip cards, match patterns, recall faces. Train your working memory.'],
                    ['⚡','Reaction Games','Click at the right moment. Train your neural response speed.'],
                    ['🎵','Sequence Games','Watch, listen, repeat. Train your sequential memory.'],
                    ['🔢','Logic & Numbers','Count, calculate, recall digits. Train your numerical mind.'],
                ];
                foreach ($cats as $c):
                ?>
                <div class="col-sm-6 text-start">
                    <div style="padding:20px;background:rgba(255,255,255,0.03);border-radius:12px;border:1px solid var(--border)">
                        <div style="font-size:28px;margin-bottom:8px"><?php echo $c[0]; ?></div>
                        <div style="font-family:var(--font-display);font-size:15px;font-weight:700;margin-bottom:6px"><?php echo $c[1]; ?></div>
                        <div style="font-size:13px;color:var(--text-muted)"><?php echo $c[2]; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/brainhub/brainhub/" class="btn-primary-hero">Start Playing →</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
