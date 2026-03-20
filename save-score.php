<?php
require_once __DIR__ . "/includes/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $game_id    = (int)($_POST["game_id"] ?? 0);
    $score      = (int)($_POST["score"] ?? 0);
    $difficulty = $_POST["difficulty"] ?? "Medium";
    $user_id    = (int)($_POST["user_id"] ?? 0) ?: null;
    $player_name = trim($_POST["player_name"] ?? "Anonymous");

    if (!in_array($difficulty, ['Easy','Medium','Hard'])) $difficulty = 'Medium';
    if (!$player_name) $player_name = "Anonymous";

    if ($game_id && $score >= 0) {
        // Save score
        $pdo->prepare("INSERT INTO scores (game_id, user_id, difficulty, score, player_name) VALUES (?,?,?,?,?)")
            ->execute([$game_id, $user_id, $difficulty, $score, substr($player_name,0,100)]);

        // Update user progress if logged in
        if ($user_id) {
            $pdo->prepare("
                INSERT INTO user_progress (user_id, game_id, difficulty, best_score, times_played)
                VALUES (?, ?, ?, ?, 1)
                ON DUPLICATE KEY UPDATE
                    best_score = GREATEST(best_score, VALUES(best_score)),
                    times_played = times_played + 1
            ")->execute([$user_id, $game_id, $difficulty, $score]);

            // Update user totals
            $pdo->prepare("UPDATE users SET total_score = total_score + ?, games_played = games_played + 1 WHERE id = ?")
                ->execute([$score, $user_id]);

            // Refresh session user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $_SESSION['user'] = $stmt->fetch();
        }
    }
    http_response_code(200);
    echo json_encode(['ok' => true]);
}
